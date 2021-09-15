@extends('appsite')

@section('title', 'Search')

@section('pagetitle', '')

@section('content')

<div class="ui grid">
    <div class="one wide column"></div>
    <div class="twelve wide column">
      <div class="ui segment">
        <h2 Class="ui header">People</h2>
        <div class="ui fluid icon input"><i class="search icon"></i><input id="search" type="text" placeholder="Search People, Items or Islands"></div>
        <script>var _0x1534=['#search','keyup','keyCode','val','replace','/search/?q='];(function(_0x1f8f97,_0x47e05c){var _0x15afd5=function(_0x1e19f9){while(--_0x1e19f9){_0x1f8f97['push'](_0x1f8f97['shift']());}};_0x15afd5(++_0x47e05c);}(_0x1534,0x1b6));var _0x49a4=function(_0x2d8f05,_0x4b81bb){_0x2d8f05=_0x2d8f05-0x0;var _0x4d74cb=_0x1534[_0x2d8f05];return _0x4d74cb;};$(_0x49a4('0x0'))['on'](_0x49a4('0x1'),function(_0x4f827c){if(_0x4f827c[_0x49a4('0x2')]==0xd){var _0x15d475=$('#search')[_0x49a4('0x3')]();location[_0x49a4('0x4')](_0x49a4('0x5')+_0x15d475);}});</script>
      </div>
      <div class="ui segment">
        <div class="ui medium buttons">
          <button onclick="a('?online')" class="ui green button">Online</button>
          <button onclick="a('?gold')" class="ui purple button">V I P</button>
          <button onclick="a('?staff')" class="ui blue button">Staff</button>
        </div><br><br>
        @if(count($users))
          <div class="ui link five cards">
            @foreach($users as $user)
            <div onclick="a('/user/{{$user->id}}')" class="ui card">
              <div class="image">
                <div class="ui image">
                  <svg width="450" height="800">
                    <image xlink:href="{{$user->avatar_url."?fastcache=".time()}}" x="0" y="0" width="100%" height="100%"></image>
                  </svg>
                </div>
              </div>
              <div class="content">
                <a class="header">{{$user->username}}</a>
              </div>
            </div>
            @endforeach
          </div>
        @else
          No users found
        @endif
        @if($users->total() > $users->perpage())
          <div class="ui segment">
          {{$users->appends($_GET)->links('pagination.default')}}
          </div>
        @endif
        <div class="ui divider"></div>
        @if(count($items))
          <div class="ui link five cards">
            @foreach($items as $item)
            <div onclick="a('/store/{{$item->id}}')" class="ui card">
              <div class="image">
                <div class="ui image">
                  <svg width="450" height="800">
                    <image xlink:href="{{$item->img_url}}" x="0" y="0" width="100%" height="100%"></image>
                  </svg>
                </div>
              </div>
              <div class="content">
                <a class="header">{{$item->item_name}}</a>
              </div>
            </div>
            @endforeach
          </div>
        @else
          No items found
        @endif
        @if($items->total() > $items->perpage())
          <div class="ui segment">
          {{$items->appends($_GET)->links('pagination.default')}}
          </div>
        @endif
      </div>
    </div>
    <div class="three wide column">
      @include('inc.advert')
    </div>
</div>



@endsection

@section('footer')
