@extends('appsite')

@section('title', 'Create Island')

@section('pagetitle', 'Create a new island')

@section('content')
<div class="ui grid">
    <div class="four wide column">
<form class="ui form" method="POST" id="mainform" enctype="multipart/form-data">@csrf
      <div class="ui segment">
        <div class="field">
          <input class="input" type="text" name="name" placeholder="Island Name" required>
        </div>
        <div class="field">
          <textarea class="textarea" name="desc" placeholder="Island Description" rows="15" required></textarea>
        </div>
      </div>
      <div class="ui segment">
        <div class="field">
          <label class="label">Max Players:</label>
          <input class="input" type="number" name="maxplayers" required>
        </div>
        <div class="field">
          <label class="label">Password</label>
          <input class="input" maxlength='255' name="islandpass">
        </div>
        <div class="inline field">
          <div class="ui checkbox">
            <input name="locked" type="hidden" value="0"><input type="checkbox" onclick="this.previousSibling.value=1-this.previousSibling.value"></input>
            <label>Locked with Password</label>
          </div>
        </div>
        <div class="inline field">
          <div class="ui checkbox">
            <input name="active" type="hidden" value="0"><input type="checkbox" onclick="this.previousSibling.value=1-this.previousSibling.value"></input>
            <label>Active</label>
          </div>
        </div>
        <div class="inline field">
          <div class="ui checkbox">
            <input name="copylocked" type="hidden" value="0"><input type="checkbox" onclick="this.previousSibling.value=1-this.previousSibling.value"></input>
            <label>Copylocked</label>
          </div>
        </div>
        <div class="inline field">
          <div class="ui checkbox">
            <input name="goldonly" type="hidden" value="0"><input type="checkbox" onclick="this.previousSibling.value=1-this.previousSibling.value"></input>
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
              <h3 class="ui header">Default Upload</h3>
              <p class="file-name">Click `Choose file` to upload an image</p>
              <input class="file-input" type="file" id="file-upload" name="item_file"></input>
            </div>
          </div>
        </div>
        <div class="ui segment">
          <div class="ui icon message">
            <i class="upload icon"></i>
            <div class="content">
              <h3 class="ui header">Extra Image</h3>
              <p class="file-name">Click `Choose file` to upload an image</p>
              <input class="file-input" type="file" id="file-upload2" name="item_file2"></input>
            </div>
          </div>
        </div>
        <div class="ui segment">
          <div class="ui icon message">
            <i class="upload icon"></i>
            <div class="content">
              <h3 class="ui header">Even Extra Image</h3>
              <p class="file-name">Click `Choose file` to upload an image</p>
              <input class="file-input" type="file" id="file-upload3" name="item_file3"></input>
            </div>
          </div>
        </div>
        <div class="ui segment">
          <div class="ui field">
            <label>Genre</label><br>
            <select form="mainform" class="ui search dropdown" name="genre" size="9">
              <option value="city">City</option>
              <option value="fantasy">Fantasy</option>
              <option value="horror">Horror</option>
              <option value="scifi">Sci-Fi</option>
              <option value="wildwest">Wild West</option>
              <option value="building">Building</option>
              <option value="social">Social</option>
              <option value="AAA">Production</option>
              <option value="militry">Militry</option>
              <option value="comedy">Comedy</option>
              <option value="medieval">Medieval</option>
              <option value="naval">Naval</option>
              <option value="fps">Shooter</option>
              <option value="rpg">Roleplay</option>
              <option value="fighting">Fighting</option>
              <option value="sports">Sports</option>
            </select>
          </div>
          <div class="field">
            <label>Status</label><br>
            <select name="status" form="mainform" class="ui search dropdown" size="4">
              <option value="wip" selected>WIP</option>
              <option value="alpha">Alpha Build</option>
              <option value="beta">Beta Build</option>
              <option value="release">Release Build</option>
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

$('#file-upload2').change(function() {
  //var i = $(this).prev('label').clone();
  var file = $('#file-upload2')[0].files[0].name;
  $('span.file-name2').html(file);
});

$('#file-upload3').change(function() {
  //var i = $(this).prev('label').clone();
  var file = $('#file-upload3')[0].files[0].name;
  $('span.file-name3').html(file);
});

$('select.dropdown').dropdown();
</script>

@endsection

@section('footer')
