@extends('app')

@section('title', 'Login')

@section('pagetitle', 'Login to an existing account')

@section('content')

  <form method="POST" class="ui form">
    @csrf
    <div class="field">
      <label>Username</label>
      <input name="username" class="input {{ $errors->has('username') ? 'is-danger' : '' }}" type="text" value="{{ old('username') }}" placeholder="Builderman" required>
    </div>
    <div class="field">
      <label>Password</label>
      <input name="password" class="input {{ $errors->has('password') ? 'is-danger' : '' }}" type="password" placeholder="" required>
    </div>
    <button class="ui button" type="submit">Submit</button>
  </form>
@endsection

@section('footer')
