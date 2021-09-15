@extends('appsite')

@section('title', 'Settings')

@section('pagetitle', '')

@section('content')

<div class="ui grid">
    <div class="thirteen wide column">
      <div class="ui padded segment">
        <h2 class="ui header">
          <i class="@if(Auth::user()->vip == 1) purple @else grey @endif user icon"></i>
          <div class="content" title="You know the drill">Account Settings (WIP)<div class="sub header">Manage your account settings here</div></div>
        </h2>
        <div class="ui form">
          <div class="field">
            <label class="label">Username</label> <input class="ui input" placeholder="{{Auth::user()->username}}" disabled></input>
          </div>
          <div class="field">
            <label class="label">Password</label> <input type="password" class="input" value="heytherewannabehacker" disabled></input>
          </div>
          <div class="field">
            <label class="label">Email</label> <input class="input" value="{{ $obe }}" disabled></input>
          </div>
        </div>
        <div class="ui divider"></div>
        <form class="ui form" method="POST" action="/account/bio">
          @csrf
          <div class="field">
            <label class="label">Biography</label> <textarea class="textarea" value="{{Auth::user()->Bio}}" name="bio" maxlength="375" placeholder="{{Auth::user()->Bio}}">{{Auth::user()->Bio}}</textarea>
          </div>
          <br><button class="ui button blue">Update Biography</button>
        </form>
        <div class="ui divider"></div>
        <h1 class="ui header">
          Island Authkey
          <div class="sub header">This is your secret special key used to authenticate yourself on the Islands Launcher. <a href="https://script.socialislands.net/install">Learn More</a></div>
        </h1>
        <div class="ui inverted segment">
          <form class="ui form">
          <div class="field">
            <div class="ui inverted transparent input"><input class="ui input" value="{{$key}}" disabled></input></div>
          </div>
          </form>
        </div>
        <a onclick="a('/account/genkey')" class="ui button">Generate key</a>
        <div class="ui divider"></div>
        <a onclick="a('/account/dark')" class="ui black button">Toggle Dark theme</a>
        <a class="ui green disabled button">Toggle Islands theme</a>
        <button class="ui disabled button">Change password</button>
        <button class="ui disabled button">Change email</button>
        <button class="ui disabled red button">Deactivate account</button>
      </div>
    </div>
    <div class="three wide column">
      @include('inc.advert')
    </div>
</div>
@endsection

@section('footer')
