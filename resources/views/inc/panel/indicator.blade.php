<div class="ui secondary vertical menu">
  <a href="/panel" class="{{ Request::path() == 'panel' ? 'active' : '' }} item">
    Overview
  </a>
  <a onclick="a('/store/assets/moderate')" class="{{ Request::path() == 'store/assets/moderate' ? 'active' : '' }} item">
    Assets
  </a>
  <a href="/panel/#badges" class="{{ Request::path() == 'panel/badges' ? 'active' : '' }} item">
    Badges
  </a>
  <a href="/panel/#Bans" class="{{ Request::path() == 'panel/bans' ? 'active' : '' }} item">
    Bans
  </a>
</div>
