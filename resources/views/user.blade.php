@extends('appsite')

@section('title', $user->username)

@section('pagetitle', $user->username)

@section('content')

<div class="ui grid">
<div class="five wide column">
  <div class="ui segment"><h2 class="header" @if($user->username == "Kwame") style="color:green;" @endif> {{$user->username}}</h2>
    <div class="ui circular labels">
    @switch($user->power)
      @case("senior_engineer")
      <a style="font-size:12px;" class="ui basic red label badge-size" data-tooltip="This user is staff" data-position="top center"><i class="shield alternate icon"></i> Sr. Engineer</a>
      @break
      @case("engineer")
      <a style="font-size:12px;" class="ui basic yellow label badge-size" data-tooltip="This user is staff" data-position="top center"><i class="shield alternate icon"></i> Engineer</a>
      @break
      @case("administrator")
      <a style="font-size:12px;" class="ui basic grey label badge-size" data-tooltip="This user is staff" data-position="top center"><i class="shield alternate icon"></i> Administrator</a>
      @break
      @case("moderator")
      <a style="font-size:12px;" class="ui basic blue label badge-size" data-tooltip="This user is staff" data-position="top center"><i class="shield alternate icon"></i> Moderator</a>
      @break
      @case("artist")
      <a style="font-size:12px;" class="ui basic pink label badge-size" data-tooltip="This user is staff" data-position="top center"><i class="fas fa-paint-brush"></i> Artist</a>
      @break
    @endswitch
    @if($user->vip == 1)
      <a style="font-size:12px;" class="ui purple label badge-size" data-tooltip="This user is supports the site!" data-position="top center"><i class="fas fa-user-secret"></i> V I P</a>
    @endif
    @if($user->banned==1)
      <div class="ui black label">This user is in banland</div>
    @endif
    </div>
  </div>
  @if(Auth::user()->id != $user->id)
    @if(Auth::user()->power != "member" && Auth::user()->power != "artist")
      <div class="ui segment">
        <button onclick="a('/user/{{$user->id}}/ban/')" class="ui red button">Ban</button>
        <button class="ui disabled blue button">Purge</button>
        <button class="ui basic disabled grey button">Edit Account</button>
      </div>
    @endif
  @endif
  <div class="ui @if($online == true) green @else red @endif  segment">
    <div class="ui image">
      <svg width="450" height="800">
        <image xlink:href="{{ $user->avatar_url."?fastcache=".time() }}" x="0" y="0" width="100%" height="100%"></image>
      </svg>
    </div>
  </div>
</div>
<div class="eleven wide column">
  <div class="ui basic segment">
    <h2 class="ui sub header">{{$user->username}}'s Bio</h2><br>
    <div class="ui raised segment">
      <pre style="white-space: pre-wrap;"><p>{{$user->Bio}}</p></pre>
    </div>
  </div>
  <div class="ui basic segment">
    <h2 class="ui sub header">{{$user->username}}'s Stats</h2><br>
    <div class="ui two mini statistics">
      <div class="statistic">
        <div class="value">{{ $joined }}</div>
        <div class="label">Joined</div>
      </div>
      <div class="statistic">
        <div class="value">0</div>
        <div class="label">Island Visits</div>
      </div>
    </div>
  </div>
  <div class="ui basic segment">
    <h2 class="ui sub header">{{$user->username}}'s Inventory</h2><br>
    <div class="ui top attached segment">
      @if(count($items))
        <div class="ui six cards">
          @foreach($items as $item)
          <div class="ui card">
            <a onclick="a('/store/{{$item->id}}')" class="image">
              <img src="{{$item->img_url}}">
            </a>
            <div class="content">
              <a style="word-wrap: break-word;" onclick="a('/store/{{$item->id}}')" class="header">
                @if(strlen($item->item_name) > 14)
                 @php echo substr($item->item_name, 0, 14)." <i>...</i>"; @endphp
                @else
                  {{ $item->item_name }}
                @endif
              </a>
              <div class="meta">
                @if($item->rare == 1)
                  <div class="ui basic mini red label"><i class="hashtag icon"></i>{{$item->serial}} Serial</div>
                @endif
              </div>
            </div>
          </div>
          @endforeach
        </div>
      @else
        {{$user->username}} has no items
      @endif
    </div>
    <div onclick="alert('filters being added')" class="ui bottom attached tabular menu">
      <a class="active item">All</a>
      <a class="item">Gear</a>
      <a class="item">Hats</a>
      <a class="item">Hair</a>
      <a class="item">Mask</a>
      <a class="item">Eyes
      <a class="item">Mouth</a>
      <a class="item">Torso</a>
      <a class="item">Shirts & Pants</a>
      <a class="item">Backgrounds</a>
    </div>
    @if(count($items))
      @if(!empty($pagi->links()->html))
      <div class="ui segment">
        {{ $pagi->links('pagination.default') }}
      </div>
      @endif
    @endif
  </div>
  <div class="ui basic segment">
    <h2 class="ui sub header">{{$user->username}}'s Badges</h2><br>
    {{$user->username}} has no badges
  </div>
  <div class="ui basic segment">
    <h2 class="ui sub header">{{$user->username}}'s Islands</h2><br>
    @if(count($islands) == 0)
      {{$user->username}} has no islands :(
    @else
      <div class="ui small images">
        @foreach($islands as $island)
          <a href='/islands/{{$island->id}}'><img src='{{$island->thumbnail1}}'></img></a>
        @endforeach
      </div>
    @endif
  </div>
</div>
</div>

@endsection

@section('footer')
