<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'Page') | Social Islands</title>
    <link rel="stylesheet" href="/css/custom.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/imgs/landing/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/imgs/landing/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/imgs/landing/favicon-16x16.png" sizes="16x16">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="/css/semantic/semantic.min.css"/>
    <script src="/css/semantic/semantic.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="application-name" content="Social Islands">
    <meta name="description" content="A 2D social networking avatar site and game creation platform">
    <meta name="keywords" content="Socialislands,sans,social,avatar,site,game,create,roblox,minecraft,clones,roblox clones,multiplayer,islands, social islands">
  </head>
  <body>
    @include('inc.navbar')
    @include('inc.errors')

    @yield('content')


    @include('inc.footer')
  </body>
</html>
