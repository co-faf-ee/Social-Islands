@extends('appsite')

@section('title', $island->serverName)

@section('pagetitle', $island->serverName)

@section('content')

<div class="ui grid">
  <div class="one wide column"></div>
  <div class="fourteen wide column">
    <div class="ui padded segment">
      <div class="ui grid">
        <div class="three wide column">
          <div class="segment">
            <h2 class="ui header">{{$island->serverName}}</h2>
            <div class="ui instant move reveal">
              <div class="visible content">
                <img src="{{$island->thumbnail1}}" class="ui fluid image">
              </div>
              <div class="hidden content">
                <img src="{{$island->thumbnail3}}" class="ui fluid image">
              </div>
            </div>
            <img src="{{$island->thumbnail2}}" class="ui fluid image">
          </div>
        </div>
        <div class="thirteen wide column">
          <div class="segment">
            <div class="ui padded segment">{{$island->description}}</div>
            <div class="ui grid">
              <div class="three wide column">
              <div class="ui padded segment">
                  Creators
                  <a href='/user/{{$creatorid}}'><div class="cropavatar"><img class="image" src="{{$creatorimg}}"></img></div><b>{{$creatorname}}</b></a>
              </div>
              </div>
              <div class="four wide column">
              <div class="ui segment">
                <button class="ui fluid button green">Play</button>
                @auth
                  @if(Auth::user()->id == $creatorid)
                    <br><button onclick="a('/islands/edit/{{$island->id}}')" class="ui fluid button yellow">Edit</button>
                    <br><button class="ui fluid button red">Remove</button>
                    <br><button class="ui fluid button blue">Develop</button>
                  @endif
                  @if($island->copylocked == 0 and $creatorid != Auth::user()->id ) 
                    <br><button class="ui fluid button blue">Develop</button>
                  @endif
                @endauth
              </div>
              </div>
              <div class="nine wide column">
              <div class="ui padded segment">
                <div class="ui large labels">
                  @if($island->active == 1)
                  <div class="ui basic green label">Active</div>
                  @else
                  <div class="ui basic red label">Inactive</div>
                  @endif
                  @if($island->goldonly == 1)
                  <div class="ui basic purple label">VIP Only</div>
                  @endif
                  @if($island->copylocked == 1)
                  <div class="ui circular yellow label"><i class="lock icon"></i>Copy</div>
                  @endif
                  <div class="meta">
                    <br>
                    <span class="date" style="color:grey;">Created {{ date("jS M Y",$island->created) }}</span><br>
                    <span class="date" style="color:grey;">Last updated @if($island->updated != 0) {{date("jS M Y",$island->updated)}} @else around creation time @endif</span>
                  </div>
                </div>
              </div>
              <div class="ui padded segment">
                <div class="ui two mini statistics">
                  <div class="statistic">
                    <div class="value">{{$island->visits}}</div>
                    <div class="label">Vists</div>
                  </div>
                  <div class="statistic">
                    <div class="value">{{$island->playersOnline}}</div>
                    <div class="label">Playing</div>
                </div>
              </div>
              </div>
            </div>
          </div>
          <div class="ui basic segment">
            <div class="ui top attached tabular menu">
              <a onclick="alert('sOooon paapaaaaaa');" class="active item">
                Servers
              </a>
              <a <a onclick="alert('sOooon paapaaaaaa');"  class="item">
                Packages
              </a>
              <a <a onclick="alert('sOooon paapaaaaaa');"  class="item">
                Comments
              </a>
            </div>
            <div class="ui bottom attached segment">
              <p>Currently no running games</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="one wide column">
    @include('inc.advert')
  </div>
</div>

@endsection

@section('footer')
