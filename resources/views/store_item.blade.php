@extends('appsite')

@section('title', $item->item_name)

@section('pagetitle', '')

@section('content')

<div class="ui grid">
  <div class="five wide column">
    <div class="ui segment">
      <h1 class="ui header">{{$item->item_name}}</h1>
      <div class="center aligned image">
        @if($item->rare == 1)
          <a class="ui red ribbon label">R A R E</a>
        @endif
        <svg width="300" height="533"><image xlink:href="{{$item->img_url}}" x="0" y="0" width="100%" height="100%"></image></svg>
      </div>
    </div>
    @if($item->rare == 1)
    <div class="ui segment">
      @if($sellers == false)
        There are no sellers
      @else
        @foreach($sellers as $seller)
        <div class="ui cards">
          <div class="card">
            <div class="content">
              <div class="cropavatar right floated">
                <img src="{{ $seller->avatar_url."?fastcache=".time() }}">
              </div>
              <div class="header">
                {{$seller->username}}
              </div>
              <div class="meta">
                @if($seller->isStaff == true)
                <div class="ui orange label"><i class="certificate icon"></i> Staff</div>
                @else
                  @if($seller->isVip == true)
                    <div class="ui purple label"><i class="user secret icon"></i> VIP</div>
                  @else
                    Member
                  @endif
                @endif
              </div>
              <div class="padded description">
                <div class="ui basic large fluid label"><i style="color:#d1b200;" class="fas fa-coins"></i> {{number_format($seller->price)}} </div><br>
                <div class="ui basic label" style="margin-top:5px;border:0px;"><i class="fas fa-hashtag"></i> {{$seller->serial}} </div>
              </div>
            </div>
            <div class="extra content">
              <div class="ui two small buttons">
                @auth
                  @if($seller->user_id == Auth::user()->id)
                    <button onclick="a('/store/{{$item->id}}/remove/{{$seller->sale_id}}')" class="ui basic red button">Remove</button>
                  @else
                    <button onclick="a('/store/{{$item->id}}/buy/{{$seller->sale_id}}')" class="ui basic green button">Buy</button>
                  @endif
                @endauth
              </div>
            </div>
          </div>
        </div>
        @endforeach
      @endif
      </div>
    @endif
  </div>
  <div class="eight wide column">
    <div class="ui very padded segment">
      <h3 class="ui header">Description</h3>
      <p>{{ $item->item_description }}</p>
      <div class="meta">
        <span class="date" style="color:grey;">Created {{ date("jS M Y",$item->created) }}</span><br>
        <span class="date" style="color:grey;">Last updated @if($item->updated != 0) {{date("jS M Y",$item->updated)}} @else around creation time @endif</span>
      </div>
      @auth
        <br><button onclick="buy()" class="ui basic green medium button">Buy {{$item->item_name}}</button>
        @if($show == true)
          <button onclick="a('/store/{{$item->id}}/sell')" class="ui basic orange button">Sell {{$item->item_name}}</button>
        @endif
      @endauth
    </div>
    <div class="ui very padded segment">
      <h3 class="ui header">Attributes</h3>
      <div class="ui centered large labels">
        <div class="ui basic label"><i style="color:#d1b200;" class="fas fa-coins"></i> {{number_format($item->price)}}</div>
        @if($item->rare == 1)
          @if($item->rare_quantity == $item->rare_quantity_original)
            @php $raretag = "green"; @endphp
          @endif
          @if($item->rare_quantity < 2)
            @php  $raretag = "red"; @endphp
          @endif
          @if($item->rare_quantity < $item->rare_quantity_original && $item->rare_quantity >= 2)
            @php  $raretag = "yellow"; @endphp
          @endif
          <a class="ui {{$raretag}} basic label">{{ $item->rare_quantity }} Left!</a>
        @endif
        @if($item->gold_only == 1)
          <a class="ui purple basic label">VIP Only</a>
        @endif
        @if($item->offsale == 1)
          <a class="ui grey basic label">Offsale</a>
        @endif
        @if($item->offsale == 0 && $item->gold_only == 0 && $item->rare == 0)
          <a class="ui grey basic label">Normal</a>
        @endif
        @if($item->craftable == 1)
          <a onclick="alert('Crafting coming soon')" class="ui yellow basic label">Craftable</a>
        @endif
        <div class="ui basic label">{{$item->sold}} Sold</div>
      </div>
    </div>
    @guest
    <div class="ui padded segment">
      <h3 class="ui header">You must login to view the rest of this page</h3>
    </div>
    @endguest
    @auth
    <div class="ui padded segment">
      <form method="post" action="/store/{{$item->id}}/c" class="ui reply form">
      @csrf
        <div class="field">
          <textarea name="comment" rows="3"></textarea>
        </div>
        <button class="ui blue labeled submit icon button" type="submit">
          <i class="icon edit"></i> Add Comment
        </button>
      </form><br>
      @if(count($item_com))
        @foreach($item_com as $comment)
        <div class="ui comments">
        <div class="comment">
          <a class="avatar">
            <img src="{{$comment->avatar_url}}">
          </a>
          <div class="content">
            <a class="author">{{$comment->username}}</a>
            <div class="metadata">
              <span class="date">{{$comment->time_sent}}</span>
            </div>
            <div class="text">
              {{$comment->comment}}
            </div>
            <div class="actions">
              @if(Auth::user()->id == $comment->user_id)
                <a class="reply">Remove</a>
              @else
                <a class="reply">Reply</a>
                <a class="reply">Report</a>
              @endif
              @if(Auth::user()->power == "senior_engineer" || Auth::user()->power == "engineer" || Auth::user()->power == "administrator" || Auth::user()->power == "moderator")
                <a class="reply">Pin</a>
              @endif

            </div>
          </div>
        </div>
        @endforeach
        <br>
        @if(!empty($pagi->links()->html))
          <div class="ui segment">
            {{$pagi->links('pagination.default')}}
          </div>
        @endif
      @else
        No Comments avalible on this item
      @endif
    </div>

    @endauth
    </div>
  </div>
  <div class="one wide column">

  </div>
</div>
<form id="buy" method="post">@csrf</form>
<script>
function buy(){
  // Warning, this code is a temporary solution for the buy function
  var d = confirm('Are you sure you want to buy this item?\n\n\n(Sorry for this ugly popup will update)');
  if(d == true){document.getElementById("buy").submit();}
}
</script>
@endsection

@section('footer')
