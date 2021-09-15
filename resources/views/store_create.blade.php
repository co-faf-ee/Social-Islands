@extends('appsite')

@section('title', 'Create Item')

@section('pagetitle', 'Create a new item')

@section('content')
<div class="ui grid">
    <div class="four wide column">
<form class="ui form" method="POST" id="mainform" enctype="multipart/form-data">@csrf
      <div class="ui segment">
        <div class="field">
          <input class="input" type="text" name="name" placeholder="Item Name" required>
        </div>
        <div class="field">
          <textarea class="textarea" name="desc" placeholder="Item Description" rows="5" required></textarea>
        </div>
      </div>
      <div class="ui segment">
        <div class="field">
          <label class="label">Price:</label>
            <input class="input" type="number" name="price" required>
        </div>
        <div class="field">
          <label class="label">Rare Quantity</label>
          <input class="input" type="number" name="rare_quantity" value="0">
        </div>
        <div class="inline field">
          <div class="simple ui checkbox">
            <input name="rare" type="hidden" value="0"><input type="checkbox" onclick="this.previousSibling.value=1-this.previousSibling.value"></input>
            <label>Rare</label>
          </div>
        </div>
        <div class="inline field">
          <div class="ui checkbox">
            <input name="offsale" type="hidden" value="0"><input type="checkbox" onclick="this.previousSibling.value=1-this.previousSibling.value"></input>
            <label>Offsale</label>
          </div>
        </div>
        <div class="inline field">
          <div class="ui checkbox">
            <input name="gold" type="hidden" value="0"><input type="checkbox" onclick="this.previousSibling.value=1-this.previousSibling.value"></input>
            <label>VIP Only</label>
          </div>
        </div>
      </div>
    </div>
    <div class="six wide column">
        <div class="ui segment">
          <div class="ui icon message">
            <i class="upload icon"></i>
            <div class="content">
              <h3 class="ui header">Image Upload</h3>
              <p class="file-name">Choose your item image (with transparency)</p>
              <input class="file-input" type="file" id="file-upload" name="item_file"></input>
              <b>Image must be with <i>450px</i> x <i>800px</i></b>
            </div>
          </div>
        </div>
        <div class="ui segment">
          <div class="field">
            <label>Layer</label><br>
            <select form="mainform" name="layer" class="ui dropdown">
              <option value="Gear" selected>Gear</option>
              <option value="Hat1">Top Hat</option>
              <option value="Hat2">Bottom Hat</option>
              <option value="Hair">Hair</option>
              <option value="Mask">Mask</option>
              <option value="Eyewear">Eyewear</option>
              <option value="Eyes">Eyes</option>
              <option value="Mouth">Mouth</option>
              <option value="Torso">Body</option>
              <option value="Shirt">Shirt</option>
              <option value="Trousers">Trousers</option>
              <option value="Background">Background</option>
            </select>
          </div>
        </div>
      </div>
    <div class="five wide column">
      <div class="ui segment">
        <p>☢ Any attempt to upload scripts, bots, malware, cracked files will result in the termination of your account followed by an extended IP ban
          <br>2. --
          <br>3. --</p>
      </div>
      <button class="ui blue button" type="submit">Upload</button>
    </div>
  </form>
</div>
<script>
$('#file-upload').change(function() {
  //var i = $(this).prev('label').clone();
  var file = $('#file-upload')[0].files[0].name;
  $('span.file-name').html(file);
});
//$('.ui.checkbox').checkbox();
$('select.dropdown').dropdown();
</script>
@endsection

@section('footer')
