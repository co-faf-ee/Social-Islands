@extends('app')

@section('title', 'Sign Up')

@section('pagetitle', 'Create a new account')

@section('content')

<form method="POST" class="ui form">
  @csrf
  <div class="field">
    <label class="label">Username</label>
    <input name="username" class="input {{ $errors->has('username') ? 'is-danger' : '' }}" type="text" value="{{ old('username') }}" placeholder="Builderman" required>
  </div>
  <div class="field">
    <label class="label">Password</label>
    <input name="password" class="input" type="password" placeholder="" required>
  </div>
  <div class="field">
    <label class="label">Retype password</label>
    <input name="password_confirmation" class="input  {{ $errors->has('password') ? 'is-danger' : '' }}" type="password" placeholder="" required>
  </div>
  <div class="field">
    <label class="label">Email</label>
    <input name="email" class="input {{ $errors->has('email') ? 'is-danger' : '' }}" type="email" value="{{ old('email') }}" placeholder="acme@richardlabs.com" required>
  </div>
  <div class="field">
      <label class="label">Beta key</label>
      <input name="beta" class="input" placeholder="Alohmora" required>
    </div>
  <button class="ui button" type="submit">Submit</button><br>
  <sub>By creating an account you hereby agree to Social Island's <a href='#' class="has-text-grey-light">Terms and conditions</a>
  and <a class="has-text-grey-light" href='#'>Privacy policy</a></sub>
</form>
@endsection

@section('footer')
