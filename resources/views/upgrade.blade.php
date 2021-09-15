@extends('appsite')

@section('title', 'Upgrade')

@section('pagetitle', '')

@section('content')

<div class="ui grid">
    <div class="one wide column"></div>
    <div class="twelve wide column">
      <div class="ui very padded segment">
        <h1 class="ui center aligned icon header">
          <i class="circular grey credit card outline icon"></i>
          Maintenance
          <div class="content">
            <div class="sub header">
              The Upgrade page is currently in maintenace, please try again later.
            </div>
          </div>
        </h1>
      </div>
    </div>
    <div class="three wide column">
      @include('inc.advert')
    </div>
</div>

@endsection

@section('footer')
