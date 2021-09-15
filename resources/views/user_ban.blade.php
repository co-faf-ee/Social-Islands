@extends('appsite')

@section('title', 'Settings')

@section('pagetitle', '')

@section('content')

<div class="ui grid">
    <div class="one wide column"></div>
    <div class="twelve wide column">
      <div class="ui very padded segment">
        <h2 class="ui header">
          <i class="circular red gavel icon"></i>
          <div class="content">
            Ban {{$user->username}}
            <div class="sub header">Edit ban preferences</div>
          </div>
        </h2>
        <form id="mainform" class="ui form" method="post">
          @csrf
          <div class="field">
            <label>Length of Ban</label>
            <select form="mainform" name="ban_till" class="ui dropdown">
              <option value='-1'>Warn</option>
              <option value='10800'>3 Hours</option>
              <option value='21600'>6 Hours</option>
              <option value='43200'>12 Hours</option>
              <option value='86400'>1 Day</option>
              <option value='259200'>3 Days</option>
              <option value='604800'>1 Week</option>
              <option value='2629743'>1 Month</option>
            </select>
          </div>
        <div class="field">
          <label>Reason of Ban</label>
          <textarea class="ui textarea" name="reason" placeholder="Ban Reason" rows="2" required></textarea>
        </div>
        <button type="submit" class="ui red button">Send {{$user->username}} to BanLand!</button>
        </form>
      </div>
    </div>
    <div class="three wide column">
      @include('inc.advert')
    </div>
</div>
<script>
$('select.dropdown').dropdown();
</script>
<!--
<div class="container is-fluid">
  <div class="notification">
    <strong class="title is-3">Ban {{$user->username}}</strong>
  </div>
  <div class="notification">
    <form method="post">
      @csrf
      <div class="field">
        <label class="label">Ban until</label>
        <div class="select">
          <select name="ban_till">
            <option value='-1'>Warn</option>
            <option value='10800'>3 Hours</option>
            <option value='21600'>6 Hours</option>
            <option value='43200'>12 Hours</option>
            <option value='86400'>1 Day</option>
            <option value='259200'>3 Days</option>
            <option value='604800'>1 Week</option>
            <option value='2629743'>1 Month</option>
          </select>
        </div>
      </div>
      <div class="field">
        <textarea class="textarea" name="reason" placeholder="Ban reason" required></textarea>
      </div>
      <button class="button has-background-grey-light">Ban</button>
    </form><br>
    <button class="button is-danger">Terminate account</button>
    <button class="button">Poison ban accounts</button>
    <button class="button">Shadow ban account</button>
    <button class="button is-info">Freeze account</button>
  </div>
</div>-->
@endsection

@section('footer')
