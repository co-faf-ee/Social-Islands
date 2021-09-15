@extends('appsite')

@section('title', 'Chat')

@section('pagetitle', '')

@section('content')

<div class="ui grid">
    <div class="one wide column"></div>
    <div class="twelve wide column">
      <div class="ui very padded segment">
        <h1 class="ui center aligned icon header">
          <i class="circular massive orange database icon"></i>
          Offline
          <div class="content">
            <div class="sub header">
              The chat has been removed due to connection issues and message shortages; the chat will be improved and will return.
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
