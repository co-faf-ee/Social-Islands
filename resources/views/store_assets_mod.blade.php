@extends('appsite')

@section('title', 'All Assets')

@section('pagetitle', '')

@section('content')

<div class="ui grid">
  <div class="four wide column">
    @include('inc.panel.indicator')
  </div>
  <div class="twelve wide column">
    <h1>Assets</h1>
    <i>Reject and Accept Assets. <br>
    (Any accepted assets that are suppose to be rejected will be considered a warning to your moderation status. This is manually checked)</i>
    <div class="ui seven cards">
      @if(count($assets))
        @foreach($assets as $asset)
        <div class="ui card">
          <div class="image">
            <img src="{{$asset->img_url}}">
          </div>
          <div class="content">
            <a class="header">{{$asset->item_name}}</a>
            <div class="description">
              {{str_limit($asset->item_description,20,'...')}}
            </div>
          </div>
          <div class="extra content">
            <a href="/store/asset/accept/{{$asset->id}}" onclick="javascript:return confirm('Are you sure you want to reject this asset?')"  class="ui green fluid button">Accept</a><br>
            <a href="/store/asset/reject/{{$asset->id}}" onclick="javascript:return confirm('Are you sure you want to reject this asset?')"  class="ui red fluid button">Reject</a>
          </div>
        </div>
        @endforeach
        </div>
      @else
        <div class="ui basic segment">
          <br><br>No user uploads
          <a onclick="a('/store/create/asset')">Upload a new one?</a>
        </div>
      @endif
    </div>
  </div>
@endsection

@section('footer')
