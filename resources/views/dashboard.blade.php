@extends('appsite')

@section('title', 'Welcome')

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
      <div class="ui segment">
        <h3 class="ui header">Hey {{Auth::user()->username}}!</h3>
        <div class="ui labels">
          @if(Auth::user()->verified == 0)
          <div class="ui red basic label">You are not verified!</div>
          @else
          <div class="ui green basic label">You are verified!</div>
          @endif
          <br>
          @if(Auth::user()->vip == 1)
          <div class="ui purple label">You have VIP status!<br><br> Thank you for support this project!</div>
          @else
          <div class="ui basic purple label">You do not have VIP</div>
          @endif
        </div>
      </div>
    </div>
    <div class="eight wide column">
      <div class="ui segment">
        <h3 class="ui header">Recent Site Activity</h3>
        <p>Work in Progress</p>
      </div>
      <div class="ui segment">
        <h3 class="ui header">Island Server Status</h3>
        <div class="ui large labels">
          <a class="ui basic grey label">Island-Jericho-City-UK</a><a class="ui basic red label">Offline</a><br>
          <!-- <a class="ui basic grey label">Island-Rocky-Mount-UK</a><a class="ui basic red label">Offline</a><br>
          <a class="ui basic grey label">Island-Yellow-Brick-Road-UK</a><a class="ui basic red label">Offline</a>-->
        </div>
      </div>
      <div class="ui segment">
        <h3 class="ui header">Current Upgrade Deals</h3>
        <p>None</p>
      </div>
    </div>
    <div class="four wide column">
      @include('inc.advert')
    </div>
</div>


@endsection

@section('footer')
