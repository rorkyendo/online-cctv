<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers\LogHelper;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getCommonData();
        $user = session()->get('user');

        $pengguna = DB::table('cv_pengguna')
            ->where('id_pengguna', $user['id_pengguna'])
            ->first();

        $data['pengguna'] = $pengguna;
        $data['title']    = 'Profil Saya';
        $data['content']  = 'module.profile.main';

        return view('module.content', ['data' => $data]);
    }

    public function updateProfile(Request $request)
    {
        $user = session()->get('user');

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email'        => 'nullable|email|max:100',
            'foto_profil'  => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $updateData = [
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
        ];

        // Handle foto upload
        if ($request->hasFile('foto_profil')) {
            $dir = public_path('assets/img/profil');
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            // Delete old foto if exists
            $existing = DB::table('cv_pengguna')
                ->where('id_pengguna', $user['id_pengguna'])
                ->value('foto');
            if ($existing && file_exists($dir . '/' . $existing)) {
                @unlink($dir . '/' . $existing);
            }

            $ext      = $request->file('foto_profil')->getClientOriginalExtension();
            $filename = 'profil_' . $user['id_pengguna'] . '_' . time() . '.' . $ext;
            $request->file('foto_profil')->move($dir, $filename);
            $updateData['foto'] = $filename;
        }

        DB::table('cv_pengguna')
            ->where('id_pengguna', $user['id_pengguna'])
            ->update($updateData);

        // Refresh session
        $pengguna = DB::table('cv_pengguna')
            ->where('id_pengguna', $user['id_pengguna'])
            ->first();

        $sessionUser = session()->get('user');
        $sessionUser['nama_lengkap'] = $pengguna->nama_lengkap;
        $sessionUser['email']        = $pengguna->email;
        $sessionUser['foto']         = $pengguna->foto;
        session()->put('user', $sessionUser);

        LogHelper::log('Update Profil', 'Profile', 'Update profil user: ' . $pengguna->nama_lengkap);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = session()->get('user');

        $request->validate([
            'password_lama'  => 'required',
            'password_baru'  => 'required|min:6',
            'konfirmasi_password' => 'required|same:password_baru',
        ]);

        // Check old password
        $pengguna = DB::table('cv_pengguna')
            ->where('id_pengguna', $user['id_pengguna'])
            ->where('password', sha1($request->password_lama))
            ->first();

        if (!$pengguna) {
            return redirect()->route('profile')->with('error', 'Password lama tidak sesuai.');
        }

        DB::table('cv_pengguna')
            ->where('id_pengguna', $user['id_pengguna'])
            ->update(['password' => sha1($request->password_baru)]);

        LogHelper::log('Ganti Password', 'Profile', 'User mengganti password: ' . $pengguna->nama_lengkap);

        return redirect()->route('profile')->with('success', 'Password berhasil diperbarui.');
    }

    public function userGuide(Request $request)
    {
        $data = $this->getCommonData();

        $data['title']    = 'Panduan Penggunaan';
        $data['content']  = 'module.panduan.main';

        return view('module.content', ['data' => $data]);
    }
}
