@if (Auth::check())
<div class="ui secondary pointing menu">
  <a class="item">
    <div class="ui small header"><b>Social Islands</b></div>
  </a>
  <a onclick="a('/dashboard')" class="item {{ strpos($_SERVER['REQUEST_URI'], 'dashboard') == true ? 'active' : '' }}">
    Dashboard
  </a>
  <a onclick="a('/islands')" class="{{ strpos($_SERVER['REQUEST_URI'], 'islands') == true ? 'active' : '' }} item">
    Islands
  </a>
  <a onclick="a('/store')" class="{{ strpos($_SERVER['REQUEST_URI'], 'store') == true ? 'active' : '' }} item">
    Store
  </a>
  <a onclick="a('/search')" class="item {{ strpos($_SERVER['REQUEST_URI'], 'search') == true ? 'active' : '' }} {{ strpos($_SERVER['REQUEST_URI'], 'user') == true ? 'active' : '' }}">
    People
  </a>
  <a onclick="a('/chat')" class="item {{ strpos($_SERVER['REQUEST_URI'], 'chat') == true ? 'active' : '' }}">
    Chat
  </a>
  <div class="right menu">
    <a onclick="a('/upgrade')" class="ui icon {{ strpos($_SERVER['REQUEST_URI'], 'upgrade') == true ? 'active' : '' }} item">
      <b><i style="color:#d1b200;" class="fas fa-coins"></i> @php echo number_format(Auth::user()->cash); @endphp</b>
    </a>
    <div class="ui dropdown icon item">
       {{ Auth::user()->username }}
       <div class="menu">
         <a onclick="a('/user/{{Auth::user()->id}}')" class="item">
           Your Profile
         </a>
         <a onclick="a('/avatar')" class="item">
           Avatar
         </a>
         <div class="item">
           <i class="dropdown left icon"></i>
           <span class="text">New</span>
           <div class="menu">
             <a onclick="a('/islands/create')" class="item"><i class="gamepad icon"></i>Island</a>
             <a onclick="a('/store/create/asset')" class="item"><i class="shopping bag icon"></i>  Asset</a>
           </div>
         </div>
         @if(Auth::user()->power == "administrator" || Auth::user()->power == "engineer" || Auth::user()->power == "senior_engineer" || Auth::user()->power == "moderator")
          <div onclick="a('/panel')" class="item"><i class="red gavel icon"></i>Admin</div>
         @endif
         <div class="divider"></div>
         <a onclick="a('/account')" class="grey item">
           Settings
         </a>
         <div class="grey item">
           Credits
         </div>
         <a onclick="location.reload();" class="grey item">
           Refresh
         </a>
         <a href="/logout" class="item">
           Logout
         </a>
       </div>
     </div>
     <script>$('.ui.dropdown').dropdown();</script>
  </div>
</div>
<div class="ui container">
  <div id="stillonline" class="ui tiny @if(Auth::user()->vip == 1) purple @else teal @endif progress" data-value="1" data-total="2">
    <div class="bar">
      <div class="progress"></div>
    </div>
  </div>
@else
<div class="ui pointing menu">
  <a class="item">
    <div class="ui small header"><b>Social Islands</b></div>
  </a>
  <a href="/islands" class="{{ strpos($_SERVER['REQUEST_URI'], 'islands') == true ? 'active' : '' }} item">
    Islands
  </a>
  <a href="/store" class="{{ strpos($_SERVER['REQUEST_URI'], 'store') == true ? 'active' : '' }} item">
    Store
  </a>
  <a href="/search" class="{{ strpos($_SERVER['REQUEST_URI'], 'search') == true ? 'active' : '' }} item">
    People
  </a>
  <div class="right menu">
    <a href="/signup" class="{{ strpos($_SERVER['REQUEST_URI'], 'signup') == true ? 'active' : '' }} item">
      Sign Up
    </a>
    <a href="/login" class="{{ strpos($_SERVER['REQUEST_URI'], 'login') == true ? 'active' : '' }} item">
      Login
    </a>
  </div>
</div>
<div class="ui container">
@endif
