<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" xmlns:og="https://ogp.me/ns#">
  <head>
    <title>@yield('title', 'Page') | Social Islands</title>
    <link rel="stylesheet" href="/css/custom.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/imgs/landing/apple-touch-icon.png">
    <link rel="icon" href="/imgs/landing/favicon_3.ico" sizes="32x32">
    <link rel="icon" href="/imgs/landing/favicon_2.ico" sizes="16x16">
    <meta name="_token" content="{{csrf_token()}}" />
    <meta charset="UTF-8">
    <meta name="application-name" content="Social Islands">
    <meta name="description" content="A 2D social networking avatar site and game creation platform">
    <meta name="keywords" content="Socialislands,sans,social,avatar,site,game,create,roblox,minecraft,clones,roblox clones,multiplayer,islands, social islands, alternative to roblox, games like roblox,bloxcity,brickplanet">
    <meta property="og:title" content="Social Islands">
    <meta property="og:description" content="A 2D social networking avatar site and game creation platform">
    <meta property="og:image" content="https://socialislands.net/imgs/landing/apple-touch-icon.png">
    <meta property="og:type" content="website">
    @if (Cookie::get('theme') !== null)
      @if (Cookie::get('theme') == "dark")
        <!--<link rel="stylesheet" href="/css/customdark.css">-->
      @endif
    @endif
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!--<script src="https://cdn.jsdelivr.net/npm/bulma-extensions@6.2.7/dist/js/bulma-extensions.min.js"></script>-->
    <link rel="stylesheet" type="text/css" href="/css/semantic/semantic.min.css"/>
    <script src="/css/semantic/semantic.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    @include('inc.navbar')

    @include('inc.errors')

    @yield('content')

    @include('inc.footer')
  </body>
</html>
