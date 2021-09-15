@extends('appsite')

@section('title', 'Verification')

@section('pagetitle', '')

@section('content')
<div class="ui grid">
    <div class="four wide column">
      <div class="ui segment">
        <div class="ui image">
          <svg width="450" height="800">
            <image xlink:href="{{ Auth::user()->avatar_url."?fastcache=".time() }}" x="0" y="0" width="100%" height="100%"></image>
          </svg>
        </div>
      </div>
    </div>
    <div class="eight wide column">
      <div class="ui segment"><center>
        @if(Auth::user()->verified == 1)
          <h2 class="ui header">You are verified!</h2>
          <i class="far fa-check-circle" style="font-size:175px;color:green;"></i><br><br>
          You're account has been verified via email!
        @else
        <h2 class="ui header">You're account is not verified!</h2>
        <i class="far fa-times-circle" style="font-size:175px;color:red;"></i><br><br>
        <b class="is-danger">You are not verified! Click the verify button for a verification email for your account.</b>
        <a href="/verify/request" class="button is-fullwidth">Verify!</a>
        @endif

      </center></div>
    </div>
    <div class="four wide column">
      @include('inc.advert')
    </div>
</div>
@endsection

@section('footer')
