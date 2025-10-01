@extends('user.layouts.theme')
@push('title')
  Register
@endpush

@push('style_css')
<style>
  body {
      background-color: #6399c4;
  }
</style>
@endpush

@section('content')
<div class="row container-register">
  <div class="col-md-6 left-panel-register">
    <img src="{{url('img/bg-login2.png')}}" alt="3D Illustration">
  </div>
  <div class="col-md-6 right-panel-register">
    <h2>Create Account</h2>
    {{-- <div class="divider">Register</div> --}}
    <div class="form-floating mb-3">
        <input type="text" class="form-control" name="user_name" id="user_name" placeholder="User Name">
        <label for="floatingEmail">User Name</label>
    </div>
    <div class="form-floating mb-3">
        <input type="email" class="form-control" name="user_email" id="user_email" placeholder="Email">
        <label for="floatingEmail">Email</label>
    </div>
    <div class="form-floating mb-3 position-relative">
        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
        <label for="password">Password</label>
        <i class="bi bi-eye-slash toggle-password" onclick="togglePassword()" 
            style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:1.2rem;">
        </i>
    </div>
    <button class="create">Create Account</button>
    <p>Already have an account? <a href="/login">Log in</a></p>
  </div>
</div>
@endsection
