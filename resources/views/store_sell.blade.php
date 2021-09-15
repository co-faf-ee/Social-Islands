@extends('appsite')

@section('title', 'Selling '.$item->item_name)

@section('pagetitle', '')

@section('content')
<div class="ui grid">
    <div class="four wide column">
      <div class="ui segment">
        <div class="ui image">
          <svg width="450" height="800">
            <image xlink:href="{{$item->img_url}}" x="0" y="0" width="100%" height="100%"></image>
          </svg>
        </div>
      </div>
    </div>
    <div class="eight wide column">
      <div class="ui very padded segment"><center>
        <h2 class="ui header">You are selling {{$item->item_name}}</h2>
        <form method="post" class="ui form">
          @csrf
          <div class="field">
            <label class="label">Price</label>
            <div class="ui input">
              <input name="price" class="input" min="1" type="number" placeholder="1337"></input>
            </div>
          </div>
          <div class="field">
              <label>Serial Number</label>
                @if(count($serials) == 0)
                  <b>You are already selling your {{$item->item_name}}(s)!</b>
                @else
                  <select class="ui search dropdown" name="serial">
                    @foreach($serials as $s)
                      <option value="{{$s->serial}}">Serial #{{$s->serial}}</option>
                    @endforeach
                  </select>
                @endif
          </div>
          <button class="ui green button">Sell</button> <button class="ui green button disabled">Sell Back</button> <button onclick="a(/store/{$item->id})" class="ui red button">Cancel</button>
        </form>
        <script>$('select.dropdown').dropdown();</script>
      </center></div>
    </div>
    <div class="four wide column">
      @include('inc.advert')
    </div>
</div>
@endsection

@section('footer')
