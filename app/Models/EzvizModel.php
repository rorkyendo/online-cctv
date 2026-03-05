<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EzvizModel
{
    /**
     * Authenticate with EZVIZ API and get access token.
     * Endpoint: POST {api_url}/api/lapp/token/get
     *
     * @param object $akun cv_ezviz_akun record
     * @return array ['success' => bool, 'access_token' => string|null, 'expiry' => datetime|null]
     */
    public function getAccessToken($akun)
    {
        try {
            $response = Http::timeout(15)->asForm()->post($akun->api_url . '/api/lapp/token/get', [
                'appKey'    => $akun->app_key,
                'appSecret' => $akun->app_secret,
            ]);

            $result = $response->json();

            if (isset($result['code']) && $result['code'] == '200') {
                $tokenData  = $result['data'] ?? [];
                $accessToken = $tokenData['accessToken'] ?? $tokenData['access_token'] ?? null;
                $expireRaw   = $tokenData['expireTime'] ?? $tokenData['expire_time'] ?? 0;
                // expireTime bisa berupa integer ms atau string — pastikan numerik
                $expireMs    = is_numeric($expireRaw) ? intval($expireRaw) : 0;
                $expiryTime  = $expireMs > 0
                    ? date('Y-m-d H:i:s', intval($expireMs / 1000))
                    : date('Y-m-d H:i:s', strtotime('+7 days'));

                // Update token in database
                DB::table('cv_ezviz_akun')->where('id_ezviz_akun', $akun->id_ezviz_akun)->update([
                    'access_token' => $accessToken,
                    'token_expiry'  => $expiryTime,
                    'last_sync'     => date('Y-m-d H:i:s'),
                ]);

                return [
                    'success'      => true,
                    'access_token' => $accessToken,
                    'expiry'       => $expiryTime,
                ];
            }

            return [
                'success' => false,
                'message' => ($result['msg'] ?? $result['message'] ?? 'Failed to get token')
                           . ' [code=' . ($result['code'] ?? '?') . ']',
            ];
        } catch (\Throwable $e) {
            Log::error('EZVIZ getAccessToken error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get valid access token for an account (refresh if expired).
     *
     * @param int $idEzvizAkun
     * @return string|null
     */
    public function getValidToken($idEzvizAkun)
    {
        $akun = DB::table('cv_ezviz_akun')
            ->where('id_ezviz_akun', $idEzvizAkun)
            ->where('status', 'aktif')
            ->first();

        if (!$akun) {
            return null;
        }

        // Check if token is still valid (with 5 minute buffer)
        if ($akun->access_token && $akun->token_expiry) {
            $expiry = strtotime($akun->token_expiry);
            if ($expiry > (time() + 300)) {
                return $akun->access_token;
            }
        }

        // Token expired or not set - refresh it
        $result = $this->getAccessToken($akun);
        return $result['success'] ? $result['access_token'] : null;
    }

    /**
     * Get live stream URL for a CCTV device.
     * Supports multiple protocols: ezopen (HLS/RTMP/WebRTC)
     *
     * @param object $cctv cv_cctv record
     * @param string $protocol 'hls' | 'rtmp' | 'ezopen'
     * @return array
     */
    public function getLiveStreamUrl($cctv, $protocol = 'hls')
    {
        $token = $this->getValidToken($cctv->id_ezviz_akun);

        if (!$token) {
            return ['success' => false, 'message' => 'Cannot get valid EZVIZ token'];
        }

        $akun = DB::table('cv_ezviz_akun')->where('id_ezviz_akun', $cctv->id_ezviz_akun)->first();

        try {
            // quality: API EZVIZ pakai integer 1=HD, 2=SD
            $qualityMap = ['hd' => 1, 'sd' => 2, '1' => 1, '2' => 2];
            $quality    = $qualityMap[strtolower((string)($cctv->stream_type ?? 1))] ?? 1;
            $channelNo  = intval($cctv->channel_no ?? 1);

            // ── ezopen: construct URL directly — no API call needed ──────
            if (strtolower($protocol) === 'ezopen') {
                $suffix = ($quality === 1) ? '.hd.live' : '.live';
                return [
                    'success'      => true,
                    'url'          => "ezopen://open.ezviz.com/{$cctv->device_serial}/{$channelNo}{$suffix}",
                    'access_token' => $token,
                    'api_url'      => $akun->api_url ?? 'https://isgpopen.ezvizlife.com',
                    'validCode'    => $cctv->validCode ?? null,
                ];
            }

            // HLS: force quality=2 (SD / H.264) — browsers cannot decode H.265 (quality=1/HD)
            $hlsQuality = (strtolower($protocol) === 'hls') ? 2 : $quality;

            $response = Http::timeout(15)->asForm()->post($akun->api_url . '/api/lapp/live/address/get', [
                'accessToken'  => $token,
                'deviceSerial' => $cctv->device_serial,
                'channelNo'    => $channelNo,
                'protocol'     => $this->mapProtocol($protocol),
                'quality'      => $hlsQuality,
            ]);

            $result = $response->json();

            if (isset($result['code']) && $result['code'] == '200') {
                $expireRaw = $result['data']['expireTime'] ?? $result['data']['expire_time'] ?? 0;
                $expireMs  = is_numeric($expireRaw) ? intval($expireRaw) : 0;
                return [
                    'success'    => true,
                    'url'        => $result['data']['url'] ?? null,
                    'expireTime' => $expireMs > 0
                        ? date('Y-m-d H:i:s', intval($expireMs / 1000))
                        : null,
                ];
            }

            return ['success' => false, 'message' => ($result['msg'] ?? $result['message'] ?? 'Failed to get stream URL') . ' [code=' . ($result['code'] ?? '?') . ']'];
        } catch (\Throwable $e) {
            Log::error('EZVIZ getLiveStreamUrl error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get device list from an EZVIZ account.
     *
     * @param int $idEzvizAkun
     * @param int $pageStart
     * @param int $pageSize
     * @return array
     */
    public function getDeviceList($idEzvizAkun, $pageStart = 0, $pageSize = 50)
    {
        $token = $this->getValidToken($idEzvizAkun);
        $akun = DB::table('cv_ezviz_akun')->where('id_ezviz_akun', $idEzvizAkun)->first();

        if (!$token || !$akun) {
            return ['success' => false, 'message' => 'Invalid account or token', 'devices' => []];
        }

        try {
            $response = Http::timeout(15)->asForm()->post($akun->api_url . '/api/lapp/device/list', [
                'accessToken' => $token,
                'pageStart'   => $pageStart,
                'pageSize'    => $pageSize,
            ]);

            $result = $response->json();

            if (isset($result['code']) && $result['code'] == '200') {
                return [
                    'success' => true,
                    'devices' => $result['data']['deviceInfos'] ?? [],
                    'total'   => $result['data']['total'] ?? 0,
                ];
            }

            return ['success' => false, 'message' => $result['msg'] ?? 'Failed', 'devices' => []];
        } catch (\Throwable $e) {
            Log::error('EZVIZ getDeviceList error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage(), 'devices' => []];
        }
    }

    /**
     * Get device info / status.
     *
     * @param int $idEzvizAkun
     * @param string $deviceSerial
     * @return array
     */
    public function getDeviceInfo($idEzvizAkun, $deviceSerial)
    {
        $token = $this->getValidToken($idEzvizAkun);
        $akun = DB::table('cv_ezviz_akun')->where('id_ezviz_akun', $idEzvizAkun)->first();

        if (!$token || !$akun) {
            return ['success' => false, 'message' => 'Invalid account or token'];
        }

        try {
            $response = Http::timeout(15)->asForm()->post($akun->api_url . '/api/lapp/device/info', [
                'accessToken'  => $token,
                'deviceSerial' => $deviceSerial,
            ]);

            $result = $response->json();

            if (isset($result['code']) && $result['code'] == '200') {
                return ['success' => true, 'data' => $result['data'] ?? null];
            }

            return ['success' => false, 'message' => $result['msg'] ?? 'Failed'];
        } catch (\Throwable $e) {
            Log::error('EZVIZ getDeviceInfo error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get capture image (screenshot) from device.
     *
     * @param int $idEzvizAkun
     * @param string $deviceSerial
     * @param int $channelNo
     * @return array
     */
    public function captureImage($idEzvizAkun, $deviceSerial, $channelNo = 1)
    {
        $token = $this->getValidToken($idEzvizAkun);
        $akun = DB::table('cv_ezviz_akun')->where('id_ezviz_akun', $idEzvizAkun)->first();

        if (!$token || !$akun) {
            return ['success' => false, 'message' => 'Invalid account or token'];
        }

        try {
            $response = Http::timeout(15)->asForm()->post($akun->api_url . '/api/lapp/device/capture', [
                'accessToken'  => $token,
                'deviceSerial' => $deviceSerial,
                'channelNo'    => $channelNo,
            ]);

            $result = $response->json();

            if (isset($result['code']) && $result['code'] == '200') {
                return ['success' => true, 'pic_url' => $result['data']['picUrl'] ?? null];
            }

            return ['success' => false, 'message' => $result['msg'] ?? 'Failed'];
        } catch (\Throwable $e) {
            Log::error('EZVIZ captureImage error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Map protocol string to EZVIZ API integer.
     * 1 = ezopen, 2 = hls, 3 = rtmp, 6 = flv
     */
    private function mapProtocol($protocol)
    {
        $map = [
            'ezopen' => 1,
            'hls'    => 2,
            'rtmp'   => 3,
            'flv'    => 6,
        ];
        return $map[strtolower($protocol)] ?? 2;
    }

    /**
     * Add a physical device to an EZVIZ Open Platform account.
     * Endpoint: POST {api_url}/api/lapp/device/add
     *
     * @param int    $idEzvizAkun
     * @param string $deviceSerial  9-character device serial (from sticker)
     * @param string $deviceCode    Verification/validation code (from sticker)
     * @return array
     */
    public function addDeviceToAccount($idEzvizAkun, $deviceSerial, $deviceCode)
    {
        $token = $this->getValidToken($idEzvizAkun);
        $akun  = DB::table('cv_ezviz_akun')->where('id_ezviz_akun', $idEzvizAkun)->first();

        if (!$token || !$akun) {
            return ['success' => false, 'message' => 'Token atau akun tidak valid'];
        }

        try {
            $response = Http::timeout(15)->asForm()->post($akun->api_url . '/api/lapp/device/add', [
                'accessToken'  => $token,
                'deviceSerial' => strtoupper(trim($deviceSerial)),
                'deviceCode'   => trim($deviceCode),
            ]);

            $result = $response->json();

            if (isset($result['code']) && $result['code'] == '200') {
                return [
                    'success' => true,
                    'message' => 'Device berhasil ditambahkan ke akun EZVIZ',
                    'data'    => $result['data'] ?? [],
                ];
            }

            // Map known EZVIZ error codes to Indonesian messages
            $codeMsg = [
                '10002' => 'Serial number tidak ditemukan. Periksa kembali nomor serial.',
                '10003' => 'Verification code salah.',
                '10004' => 'Device sudah terdaftar di akun lain.',
                '10005' => 'Device sudah terdaftar di akun ini.',
                '20002' => 'appKey tidak valid.',
            ];
            $code    = $result['code'] ?? '?';
            $message = $codeMsg[$code]
                ?? (($result['msg'] ?? $result['message'] ?? 'Gagal menambahkan device') . ' [code=' . $code . ']');

            return ['success' => false, 'message' => $message];
        } catch (\Throwable $e) {
            Log::error('EZVIZ addDeviceToAccount error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
