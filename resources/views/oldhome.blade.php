@extends('app')

@section('title', 'Landing')

@section('content')

<div class="ui grid middle aligned">
    <div class="three wide column">
    </div>
    <div class="ten wide column">
      <div class="ui text container segment" style="height:100%;width:100%;">
        <div class="ui centered large header">
          <div class="item"><div class="ui basic massive blue horizontal label">Social Islands</div><sup>Beta</sup></div>
        </div>
        <h3 class="ui block header">What is Social Islands?</h3>
        <p>Social Islands is a social avatar networking site with an online multiplayer sandbox platform. Players can buy, sell or trade items and
        customize their characters. <br><br> Players can also edit, develop and play Islands with other players this is just a small range of activites amongst others Social Islands supports.</p>
        <h3 class="ui block header">Why is it in Beta?</h3>
        <p>Social Islands is in βeta so that users can constantly report bugs, suggest improvements and get involved with the development of the site whilst being able to
           experiance the platform before the public. <br>In return users get rewarded with items and get exclusive access before the public </p>
        <h3 class="ui block header">How can I join?</h3>
        <p>You can join this wonderful community of pioneers and trailblazers by asking for a beta key in the discord. <br><br>
        <img width="300px" height="300px" src="/imgs/landing/Logo1-02 TRANSPARENT.png"></img>
          <a href="https://discord.gg/v2h2RHq" class="item">Click here to join the discord!</a>
        </p>
      </div>
    </div>
    <div class="three wide column"> </div>
</div>


@endsection

@section('footer')
