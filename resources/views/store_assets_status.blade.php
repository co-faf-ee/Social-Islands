@extends('appsite')

@section('title', 'All Assets')

@section('pagetitle', '')

@section('content')
  <div class="ui segment">
    <h1 class="ui header">Uploaded Assets</h1>
    <h6 class="ui sub header">All uploaded assets wether accepted or rejected will be shown here</h6>
  </div>
  <div class="ui segment">
    <div class="ui seven cards">
      @if(count($uploads))
        @foreach($uploads as $asset)
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
            @switch($asset->status)
              @case("accepted")
              <div class="ui fluid small green label">Accepted</div>
              @break

              @case("pending")
              <div class="ui fluid small orange label">Pending</div>
              @break

              @case("rejected")
              <div class="ui fluid small red label">Rejected</div>
              @break
            @endswitch
          </div>
        </div>
        @endforeach
      @else
        You have no uploads :(
        <a onclick="a('/store/create/asset')">Upload a new one?</a>
      @endif
    </div>
  </div>
  @if(count($uploads))
    @if($uploads->total() > $uploads->perPage())
      <div class="ui segment">
        {{$uploads->links('pagination.default')}}
      </div>
    @endif
  @endif

@endsection

@section('footer')
