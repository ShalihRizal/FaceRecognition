@extends('user.layouts.theme')
@push('title')
  Login
@endpush
@push('style_css')
  <style>
    body{
      background: url('img/bg-login.png') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

  </style>
@endpush

@section('content')
  <div class="login-container">
    <!-- Left Panel -->
    <div class="left-panel-login">
        <img src="{{url('img/bg-login1.png')}}" class="img-fluid" alt="">
    </div>
    <!-- Right Panel -->
    <div class="right-panel-login">
        <div class="card card-login">
            <div class="card-body">
                <div class="login-box">
                    <h3>Hello Again!</h3>
                    <p>Selamat Datang Kembali ke Sistem HRIS</p>
                    <form action="{{ url('do_login') }}" method="post">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="user_username" id="user_username" placeholder="Email">
                            <label for="floatingEmail">Email</label>
                        </div>
                        <div class="form-floating mb-3 position-relative">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                            <label for="password">Password</label>
                            <i class="bi bi-eye-slash toggle-password" onclick="togglePassword()" 
                                style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:1.2rem;">
                            </i>
                        </div>
                        <button type="submit">Login</button>
                    </form>
                    <div class="signup-text">
                        Don't have an account yet? <a href="/register">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection