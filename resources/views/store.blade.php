@extends('appsite')

@section('title', 'Store')

@section('pagetitle', '')

@section('content')

<div class="ui grid">
  <div class="five wide column">
    <div class="ui segment">
      <h3 class="ui header">Store categories</h3>
    </div>
    <div class="ui orange message"><p>Filters are being added and obsolete items will not be shown in store at the moment.</p></div>
    @auth
    <div class="ui segment">
    @if(Auth::user()->power == "senior_engineer" || Auth::user()->power == "engineer" || Auth::user()->power == "artist")
    <a onclick="a('/store/create/asset')" class="ui basic button"><i class="icon plus"></i>Add Asset</a>
    <a onclick="a('/store/create')" class="ui basic blue button"><i class="icon upload"></i>Add item</a>
    @else
    <button class="ui basic button"><i class="icon plus"></i>Add Asset</button>
    @endif
    </div>
    @endauth
    <div class="ui segment">
      <div class="ui link list">
        <a class="{{ strpos($_SERVER['REQUEST_URI'], 'store') == true ? 'active' : '' }} item">Recent Items</a>
        <a class="item">Hats</a>
        <a class="item">Eyes</a>
        <a class="item">Mouths</a>
        <a class="item">Face</a>
        <a class="item">Gear</a>
        <a class="item">Backgrounds</a>
        <br>
        <a onclick="a('/store/shirts')" class="item">Shirts</a>
        <a onclick="a('/store/trousers')" class="item">Trousers</a>
      </div>
    </div>
    <div class="ui segment">
      <h5 class="ui header">Filters</h5>
      <div class="ui link list">
        <a class="item">VIP only</a>
        <a class="item">Free</a>
        <a class="item">Hidden</a>
      </div>
      <div class="ui form">
        <div class="two fields">
          <div class="ui small number field"><input type="number" placeholder="Min"></div>
          <div class="ui small number field"><input type="number" placeholder="Max"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="eleven wide column">
    <div class="ui padded segment">
      <div class="ui three link cards">
        @foreach($items->all() as $item)
          <div onclick="a('/store/{{$item->id}}')" class="ui card">
            <div class="center aligned image">
              @if($item->rare == 1)<a class="ui red ribbon label">R A R E</a>@endif
              <svg width="200" height="300"><image xlink:href="{{$item->img_url}}" x="0" y="0" width="100%" height="100%"></image></svg>
            </div>
            <div class="content">
              <a class="header">{{ $item->item_name }}</a>
              <div class="meta">
                <span class="date">
                  @if($item->layer != "Shirt" && $item->layer != "Trousers")
                    Uploaded by @if($item->creator_id == -1) Staff @else {{$users[$loop->index]->username}} @endif
                  @else
                    Made by @if($item->creator_id == -1) Staff @else {{$users[$loop->index]->username}} @endif
                  @endif
                </span>
              </div>
              <div class="description">
                @if(strlen($item->item_description) > 45)
                 @php echo substr($item->item_description, 0, 45)." <i>...</i>"; @endphp
                @else
                  {{ $item->item_description }}
                @endif
              </div>
            </div>
            <div class="extra content">
              <div class="ui labels">
                @if($item->offsale == 1)
                  <a class="ui grey basic label"><i style="color:#d1b200;" class="fas fa-coins"></i> @php echo number_format($item->price); @endphp</a>
                  <a class="ui grey basic label">This item is offsale</a>
                @else
                  <div class="ui basic label">
                    <b><i style="color:#d1b200;" class="fas fa-coins"></i> @php echo number_format($item->price); @endphp</b>
                  </div>
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
                    <a class="ui {{$raretag}} basic label">{{$item->rare_quantity}} Left!</a>
                  @endif
                  @if($item->gold_only == 1)
                    <a class="ui purple basic label">VIP Only</a>
                  @endif
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
    @if($items->total() > $items->perPage())
    <div class="ui segment">
      {{ $items->links('pagination.default') }}
    </div>
    @endif
  </div>
</div>

@endsection

@section('footer')
