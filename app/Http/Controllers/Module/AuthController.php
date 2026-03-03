<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralModel;
use App\Facades\AuthModelFacade as AuthModel;
use App\Helpers\LogHelper;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        if (session()->get('isLogin')) {
            return redirect()->to('/panel/dashboard');
        }

        $data['title']     = 'Login';
        $data['identitas'] = GeneralModel::getByIdGeneral('cv_identitas', 'first', 'id_profile', '1');
        return view('auth.login', ['data' => $data]);
    }

    public function doLogin(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'password.required' => 'Password tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            session()->flash('error', $validator->errors()->first());
            return redirect()->back()->withInput();
        }

        $user = AuthModel::getPengguna($request->username, sha1($request->password));

        if ($user) {
            if ($user->status !== 'actived') {
                session()->flash('error', 'Akun anda belum aktif atau sudah dinonaktifkan. Hubungi admin.');
                return redirect()->back()->withInput();
            }

            GeneralModel::updateById('cv_pengguna', [
                'last_login'      => date('Y-m-d H:i:s'),
                'activity_status' => 'online',
            ], 'id_pengguna', $user->id_pengguna);

            $request->session()->put(['user' => (array) $user, 'isLogin' => true]);

            LogHelper::log('Login berhasil', 'Auth', 'User ' . $user->username . ' login');

            return redirect()->to('/panel/dashboard');
        }

        session()->flash('error', 'Username atau password salah!');
        return redirect()->back()->withInput();
    }

    public function logout(Request $request)
    {
        $user = session()->get('user');
        if ($user) {
            GeneralModel::updateById('cv_pengguna', [
                'last_logout'     => date('Y-m-d H:i:s'),
                'activity_status' => 'offline',
            ], 'id_pengguna', $user['id_pengguna']);

            LogHelper::log('Logout', 'Auth', 'User ' . $user['username'] . ' logout');
        }

        $request->session()->flush();
        session()->flash('success', 'Logout berhasil.');
        return redirect()->to('/login');
    }
}
