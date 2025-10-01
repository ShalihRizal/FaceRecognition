<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Helpers\DataHelper;
use Modules\Users\Repositories\UsersRepository;
use App\Repositories\UserForgotRepository;

class UserController extends Controller
{

    public function __construct()
    {

        $this->_userRepository      = new UsersRepository;
        $this->_forgotRepository    = new UserForgotRepository;
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect('beranda');
        }

        return view('user.login');
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect('beranda');
        }

        return view('user.register');
    }

    public function block()
    {
        if (Auth::check()) {
            return redirect('beranda');
        }

        return view('user.block');
    }

    public function forgot()
    {

        if (Auth::check()) {
            return view('user.forgot');
        }

        return view('user.login');
    }

    public function authenticate(Request $request)
    {
        if (strlen($request->user_username) < 6) {
            return redirect('login')->with('error', 'Username yang anda masukan terlalu pendek!');
        }

        $validator = Validator::make($request->all(), $this->_rules());

        if ($validator->fails()) {
            return redirect('login')
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('user_username', 'password');

        if (Auth::attempt([
            filter_var($credentials['user_username'], FILTER_VALIDATE_EMAIL) ? 'user_email' : 'user_username' => $credentials['user_username'], 'user_password' => $credentials['password'], 'user_status' => 1
        ])) {
            // return redirect()->intended('beranda');
            return redirect('beranda');
        } else {
            return redirect('login')->with('error', 'Username atau kata sandi salah!');
        }
    }

    public function profile()
    {
        $getDetail  = $this->_userRepository->getById(Auth::user()->user_id);

        return view('user.setting', compact('getDetail'));
    }

    public function changepassword(Request $request)
    {

        $currpass   = $request->input('current_password');
        $password   = $request->input('user_password');

        if (!Hash::check($currpass, Auth::user()->user_password)) {
            return redirect('setting')->with('error', 'Kata sandi sekarang salah');
        }

        $this->_userRepository->update(DataHelper::_normalizeParams(['user_password' => $password], false, true), Auth::user()->user_id);

        return redirect('setting')->with('message', 'Kata sandi berhasil diubah');
    }

    public function sendforgot(Request $request)
    {

        $username   = $request->input('username');

        $getUser    = $this->_userRepository->getByUsername($username);

        if (!$getUser) {
            return redirect('forgot')->with('error', 'Nama pengguna tidak ditemukan!');
        }

        try {

            $this->_forgotRepository->insert(DataHelper::_normalizeParams(['user_id' => $getUser->user_id, 'created_by' => $getUser->user_id, 'created_at' => date('Y-m-d H:i:s')], false));

            return redirect('forgot')->with('success', 'Lupa kata sandi berhasil dikirim!');
        } catch (Exception $e) {
            return redirect('forgot')->with('error', 'Terjadi kesalahan!');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect('login');
    }

    private function _rules()
    {

        $rules = array(
            'user_username'     => 'required',
            'password'     => 'required',
            // 'g-recaptcha-response' => 'recaptcha',
        );

        return $rules;
    }
}
