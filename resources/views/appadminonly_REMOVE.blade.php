<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'Page') | Social Islands</title>
    <link rel="stylesheet" href="/css/custom.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/imgs/landing/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/imgs/landing/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/imgs/landing/favicon-16x16.png" sizes="16x16">
    @if (Cookie::get('theme') !== null)
      @if (Cookie::get('theme') == "dark")
        <link rel="stylesheet" href="/css/customdark.css">
      @endif
    @endif
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    @include('inc.navbar')

    @include('inc.errors')

    @if(Request::is('/'))
      @include('inc.hero')
    @endif

    @switch(Auth::user()->power)
      @case("member")
        <script>window.location = "/dashboard";</script>
        @break

      @case("artist")
        <script>window.location = "/dashboard";</script>
        @break

      @case("moderator")
        <script>window.location = "/dashboard";</script>
        @break
    @endswitch

    <div class="container is-fluid">
      <section class="hero is-medium">
        <div class="hero-body">
          <div class="container">
            <h1 class="title">
              @yield('pagetitle', 'Page')
            </h1><br>
            @yield('content')
          </div>
        </div>
      </section>

    </div>

    @include('inc.footer')
  </body>
</html>
