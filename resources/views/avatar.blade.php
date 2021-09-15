@extends('appsite')

@section('title', 'Customise your avatar')

@section('pagetitle', '')

@section('content')

<div class="ui grid">
  <div class="three wide column">
    <div class="ui segment"><h3 class="ui header">Customise</h3></div>
    <div class="ui segment">
      <div class="ui image">
        <svg width="450" height="800">
          <image xlink:href="{{ Auth::user()->avatar_url."?fastcache=".time() }}" x="0" y="0" width="100%" height="100%"></image>
        </svg>
      </div>
    </div>
    <div class="ui segment">
      <button onclick="a('/avatarclean')" class="ui fluid medium red button">Reset Avatar</button>
    </div>
    <div class="ui segment">
      <center>
        <i onclick="skintone(1)" style="color:#8d5524;font-size:26px;" class="fas fa-brush"></i>
        <i onclick="skintone(2)" style="color:#c68642;font-size:26px;" class="fas fa-brush"></i>
        <i onclick="skintone(3)" style="color:#e0ac69;font-size:26px;" class="fas fa-brush"></i>
        <i onclick="skintone(4)" style="color:#f1c27d;font-size:26px;" class="fas fa-brush"></i>
        <i onclick="skintone(5)" style="color:#ffdbac;font-size:26px;" class="fas fa-brush"></i>
        <br><br>
        <i onclick="skintone(6)" style="color:red;font-size:21px;" class="fas fa-brush"></i>
        <i onclick="skintone(7)" style="color:yellow;font-size:21px;" class="fas fa-brush"></i>
        <i onclick="skintone(8)" style="color:green;font-size:21px;" class="fas fa-brush"></i>
        <i onclick="skintone(9)" style="color:lime;font-size:21px;" class="fas fa-brush"></i>
        <i onclick="skintone(10)" style="color:cyan;font-size:21px;" class="fas fa-brush"></i>
        <i onclick="skintone(11)" style="color:#ede9e8;font-size:21px;" class="fas fa-brush"></i>
        <i onclick="skintone(12)" style="color:grey;font-size:21px;" class="fas fa-brush"></i>
        <i onclick="skintone(13)" style="color:black;font-size:21px;" class="fas fa-brush"></i>
        <i onclick="skintone(14)" style="color:fuchsia;font-size:21px;" class="fas fa-brush"></i>
      <center>
    </div>
  </div>
  <div class="thirteen wide column">
    <div class="ui basic segment">
      <div class="ui ten item menu">
        <a onclick="a('/avatar')" class="@if(strpos($_SERVER['REQUEST_URI'], 'avatar') == true && strpos($_SERVER['REQUEST_URI'], 'avatar/c') != true) active @endif item">All</a>
        <a onclick="a('/avatar/category/gear')" class="{{ strpos($_SERVER['REQUEST_URI'], 'avatar/category/gear') == true ? 'active' : '' }} item">Gear</a>
        <a onclick="a('/avatar/category/hats')" class="{{ strpos($_SERVER['REQUEST_URI'], 'avatar/category/hats') == true ? 'active' : '' }} item">Hats</a>
        <a onclick="a('/avatar/category/hair')" class="{{ strpos($_SERVER['REQUEST_URI'], 'avatar/category/hair') == true ? 'active' : '' }} item">Hair</a>
        <a onclick="a('/avatar/category/mask')" class="{{ strpos($_SERVER['REQUEST_URI'], 'avatar/category/mask') == true ? 'active' : '' }} item">Mask</a>
        <a onclick="a('/avatar/category/eyes')" class="{{ strpos($_SERVER['REQUEST_URI'], 'avatar/category/eyes') == true ? 'active' : '' }} item">Eyes</a>
        <a onclick="a('/avatar/category/mouth')" class="{{ strpos($_SERVER['REQUEST_URI'], 'avatar/category/mouth') == true ? 'active' : '' }} item">Mouth</a>
        <a onclick="a('/avatar/category/body')" class="{{ strpos($_SERVER['REQUEST_URI'], 'avatar/category/body') == true ? 'active' : '' }} item">Torso</a>
        <a onclick="a('/avatar/category/clothes')" class="{{ strpos($_SERVER['REQUEST_URI'], 'avatar/category/clothes') == true ? 'active' : '' }} item">Shirts & Pants</a>
      <a onclick="a('/avatar/category/backgrounds')" class="{{ strpos($_SERVER['REQUEST_URI'], 'avatar/category/backgrounds') == true ? 'active' : '' }} item">Backgrounds</a>
      </div>
    </div>
    <div class="ui padded segment">
      @if(isset($items))
      <div class="ui six link cards">
        @foreach($items as $item)
        <div onclick="a('/avatar/{{$item->id}}')" class="ui {{ $item->hidden == 1 ? 'orange' : 'green' }} card">
          <div class="center aligned image">
            @if($item->rare == 1)<a class="ui red ribbon label">R A R E</a>@endif
            <svg width="118" height="210"><image xlink:href="{{$item->img_url}}" x="0" y="0" width="100%" height="100%"></image></svg>
          </div>
          <div class="content">
            <a class="header">{{$item->item_name}}</a>
          </div>
        </div>
        @endforeach
      </div>
      @if($items->isEmpty())
        <div class="ui basic very padded segment">
          No items found
        </div>
      @endif
      @else
        You do not have any items
      @endif
    </div>
    @if(isset($pagi))
      @if($pagi->total() > $pagi->perPage())
        <div class="basic segment">
          {{ $pagi->links('pagination.default') }}
        </div>
      @endif
    @else
    <script>setTimeout(function (){location.replace("/avatar");}, 1000);</script>
    @endif
    <div class="ui padded segment">
      <h3 class="ui header">Currently worn items<div class="ui sub header">Click to Remove items</div></h3>
      @if(!empty($wearing))
        <!-- RED TO REMOVE, GREEN TO ADD -->
        <div class="ui six red link cards">
        @foreach($wearing as $layer_information)
          @foreach ($layer_information as $layer => $item)
          <div onclick="a('/avatar/{{$item->id}}')" class="ui card">
            <div class="center aligned image">
              @if($item->rare == 1)<a class="ui red ribbon label">R A R E</a>@endif
              <svg width="118" height="210"><image xlink:href="{{$item->img_url}}" x="0" y="0" width="100%" height="100%"></image></svg>
            </div>
            <div class="content">
              <a class="header">{{$item->item_name}}</a>
            </div>
          </div>
          @endforeach
        @endforeach
        </div>
      @else
        Nothing<br><a onclick="a('/store')">Shop?</a>
      @endif
    </div>
    <!--INSERT INC, E.G. /shirt/ INC PAGE WILL HAVE @route check /shirt/ show -->
  </div>
</div>
@endsection

@section('footer')
