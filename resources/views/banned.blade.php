@extends('appsite')

@section('title', 'Banland')

@if(Auth::user()->banned != 1)
  <script>window.location = "/dashboard";</script>
@endif

@section('pagetitle', 'Access Restricted')

@section('content')


<div class="ui grid">
    <div class="one wide column"></div>
    <div class="twelve wide column">
      <div class="ui very padded segment">
        <h2 class="ui header">
          <i class="circular black balance scale icon"></i>
          <div class="content">
            You have been banned!
            <div class="sub header">We have reviewed your account and determined that you have violated our terms of services.</div>
          </div>
        </h2>
        <center>
          @foreach($nbans as $ban)
          <div class="ui basic padded segment">
            <strong> Reason for ban </strong><br>
            <p>"{{$ban->reason}}"</p>
            <strong> Ban will expire </strong><br>
            {{$ban->expire}}
          </div>
          @endforeach
          <h6 class="ui sub header">Current Server time is {{ date("h:iA",time()) }}</h6>
          <h6 class="ui sub header">Our systems will automatically unban you upon your return.</h6>
        </center>
      </div>
    </div>
    <div class="three wide column">
      @include('inc.advert')
    </div>
</div>
@endsection

@section('footer')
