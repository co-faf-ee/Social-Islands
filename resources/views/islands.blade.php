@extends('appsite')

@section('title', 'Islands')

@section('pagetitle', '')

@section('content')
<div class="ui grid">
  <div class="three wide column">
    <div class="ui segment"><h3 class="ui header">Islands</h3></div>
    <div class="ui segment"><a onclick="a('/islands/create')" class="ui basic blue button"><i class="add icon"></i>Add Island</a></div>
    <div class="ui segment">
      <div class="ui vertical text menu">
        <div class="header item">Sort By</div>
        <a class="item">
          Featured
        </a>
        <a class="active item">
          Most Players
        </a>
        <a class="item">
          Most Visited
        </a>
        <a class="item">
          Most Likes
        </a>
        <a class="item">
          VIP Only
        </a>
        <a class="item">
          Island Status
        </a>
      </div>
    </div>
  </div>
  <div class="twelve wide column">
    <div class="ui four link cards">
      @foreach($islands->all() as $island)
      <a onclick="a('/islands/{{$island->id}}')" class="card">
        <div class="image">
          <img src="{{$island->thumbnail1}}">
        </div>
        <div class="content">
          <div class="header">{{$island->serverName}}</div>
          <div class="meta">
            {{$makers[($loop->iteration - 1)]}}
          </div>
          <div class="description">
            @if(strlen($island->description) > 45)
             @php echo substr($island->description, 0, 45)." <i>...</i>"; @endphp
            @else
              {{ $island->description }}
            @endif
          </div>
        </div>
        <div class="extra content">
          <span class="right floated">
            Nought
          </span>
          <span>
            <i class="@if($island->playersOnline == 0) red @elseif($island->playersOnline != $island->maxplayers) green  @else yellow @endif user icon"></i>
            {{$island->playersOnline}}
          </span>
        </div>
      </a>
      @endforeach
    </div>
    @if($islands->total() > $islands->perPage())
      <div class="ui basic segment">
        {{$islands->links('pagination.default')}}
      </div>
    @endif
  </div>
  <div class="one wide column">

  </div>
</div>

<!--
<div class="container is-fullhd">
  <div class="tile is-ancestor" style="display:-webkit-flex;display:flex;-webkit-flex-wrap:wrap;flex-wrap:wrap;">
    <div class="tile is-parent is-3">
      <div class="tile is-child">
        <div class="tile">
          <div style='margin-right:45px;'></div>
          <a href='/islands/create'><img src='/imgs/landing/islandcreatenew.png'></a><br><br>
        </div>
      </div>
    </div>
  @foreach($islands->all() as $island)
  <div class="tile is-parent box is-3 is-vertical" style='margin-right:15px;'>
    <div class="tile is-child ">
      <div class="tile">
        <div style='margin-right:20px;'></div>
        <img src='{{$island->thumbnail1}}'>
      </div><br>
    </div>
    <div class="tile is-child">
    <a href='/islands/{{$island->id}}'>"{{$island->serverName}}"</a> by <a href='/user/{{$island->creator_id}}'>{{$makers[($loop->iteration - 1)]}}</a>
      <div style='float:right;'>
        <span class='tag is-small @if($island->playersOnline == 0) is-danger @elseif($island->playersOnline != $island->maxplayers) is-success  @else is-warning @endif'><i class="fas fa-user-friends" style='margin-right:5px;'></i>{{$island->playersOnline}}</span>
        <a href='/islands/{{$island->id}}' class='button is-success is-small'>Play</a>
      </div>
    </div>
  </div>
  @endforeach
  </div>
</div>
-->
@endsection

@section('footer')
