@extends('appsite')

@section('title', 'Island')

@section('pagetitle', $island->serverName)

@section('content')
<div class="container is-fluid">
  <div class="notification" style='padding-left:10px;'>
    <center>
      <canvas id="IslandCanvas" width="1000" height="500" style="border:1px solid #000000;background-color:grey;"></canvas>
      <script>
      if(window.location.toString().indexOf("epic.test") != -1){
        //var _0xb833=["\x50\x6C\x65\x61\x73\x65\x20\x45\x6E\x74\x65\x72\x20\x54\x68\x65\x20\x50\x61\x73\x73\x77\x6F\x72\x64","","\x6C\x6F\x72\x65\x6D","\x2F","\x72\x65\x70\x6C\x61\x63\x65"];var p=prompt(_0xb833[0],_0xb833[1]);if(p!= _0xb833[2]){location[_0xb833[4]](_0xb833[3])}
      }else{
        window.location.replace("http://www.dhugues.com/?from=socialislands.net");
      }

      // Engine Init
      let screen = document.getElementById("IslandCanvas"),screen2D = screen.getContext("2d");
      screen2D.imageSmoothingEnabled = true;

      function screenClear(){
        screen2D.clearRect(0,0,screen.width,screen.height);
      }
      // End of Engine Init

      // Global Functions
      /*
      function mouse(e) {
          var pos = getMousePos(screen, e);
          posx = pos.x;
          posy = pos.y;
          return {
            x: posx,
            y: posy
          };
      }
      window.addEventListener('mousemove', mouse);
      function  getMousePos(canvas, evt) {
        var rect = canvas.getBoundingClientRect(), // abs. size of element
            scaleX = canvas.width / rect.width,    // relationship bitmap vs. element for X
            scaleY = canvas.height / rect.height;  // relationship bitmap vs. element for Y

        return {
          x: (evt.clientX - rect.left) * scaleX,   // scale mouse coordinates after they have
          y: (evt.clientY - rect.top) * scaleY     // been adjusted to be relative to element
        }
      }*/
      //var Mouse = mouse();
      function EmptyImage(){return "/imgs/games/islandbuild/empty.png";}

      function CheckCollisionRight(obj1,obj2,speed){
        this.x1 = obj1.x;
        this.y1 = obj1.y;
        this.w1 = obj1.width;
        this.h1 = obj1.height;
        this.collision1 = obj1.collision;

        this.x2 = obj2.x;
        this.y2 = obj2.y;
        this.w2 = obj2.width;
        this.h2 = obj2.height;
        this.collision2 = obj2.collision;
        this.x1 += speed;
        var c = false;
        if((this.x1 + (this.w1 - 18)) >= this.x2 && (this.x1 + this.w1) <= (this.x2 + this.w2) && this.x1 <= this.x2){
          if(this.y1 <= this.y2 && (this.y1 + this.h1) >= (this.y2 + this.h2) || this.y1 <= this.y2 && (this.y1 + this.h1) >= this.y2 && (this.x1 + this.h1) <= this.x2
        || this.y1 >= this.y2 && (this.y1 + this.h1) <= (this.y2 + this.h2) || this.y1 <= this.y2 && (this.y1 + this.h1) > this.y2 ||
      this.y1 + 22 >= this.y2 && this.y1 + 22 <= (this.y2 + this.h2) && (this.y1 + this.h1) >= (this.y2 + this.h2) && (this.y1 + this.h1) >= this.y2){
            if(this.collision2 == true){
              c = true;
            }
          }
        }
        this.x1 -= speed;
        return c;
      }

      function CheckCollisionLeft(obj1,obj2,speed){
        this.x1 = obj1.x;
        this.y1 = obj1.y;
        this.w1 = obj1.width;
        this.h1 = obj1.height;
        this.collision1 = obj1.collision;

        this.x2 = obj2.x;
        this.y2 = obj2.y;
        this.w2 = obj2.width;
        this.h2 = obj2.height;
        this.collision2 = obj2.collision;
        this.x1 -= speed;
        var c = false;
        if((this.x2 + this.w2) >= (this.x1 + 18) && (this.x2 + this.w2) <= (this.x1 + this.w1)){
          if((this.y1 + (this.h1 - 10)) >= this.y2 && this.y1 < this.y2 || this.y1 > this.y2 && (this.y1 + 26) < (this.y2 + this.h2)){
            if(this.collision2 == true){
              c = true;
            }
          }
        }
        this.x1 += speed;
        return c;
      }

      function CheckCollisionUp(obj1,obj2,speed){
        this.x1 = obj1.x;
        this.y1 = obj1.y;
        this.w1 = obj1.width;
        this.h1 = obj1.height;
        this.collision1 = obj1.collision;

        this.x2 = obj2.x;
        this.y2 = obj2.y;
        this.w2 = obj2.width;
        this.h2 = obj2.height;
        this.collision2 = obj2.collision;
        this.y1 -= speed;
        var c = false;
        if((this.y1 + 26) >= this.y2 && (this.y1 + 26) <= (this.y2 + this.h2)){
          if(this.x1 >= this.x2 && this.x1 + (this.width1 - 18) <= (this.x2 + this.w2) || (this.x1 + (this.w1 - 18)) >= this.x2 && (this.x1 + (this.w1 - 18)) <= (this.x2 +
          this.w2)  || this.x1 <= this.x2 && (this.x1 + this.w1) >= (this.x2 + this.w2)){
            if(this.collision2 == true){
              c = true;
            }
          }
        }
        this.y1 += speed;
        return c;
      }

      function CheckCollisionDown(obj1,obj2,speed){
        this.x1 = obj1.x;
        this.y1 = obj1.y;
        this.w1 = obj1.width;
        this.h1 = obj1.height;
        this.collision1 = obj1.collision;

        this.x2 = obj2.x;
        this.y2 = obj2.y;
        this.w2 = obj2.width;
        this.h2 = obj2.height;
        this.collision2 = obj2.collision;
        this.y1 += speed;
        var c = false;
        if((this.y1 + (this.h1 - 10)) >= this.y2 && (this.y1 + (this.h1 - 10)) <= (this.y2 + this.h2)){
          if(this.x1 >= this.x2 && (this.x1 + 18) <= (this.x2 + this.w2) || (this.x1 + (this.w1 - 18)) >= this.x2 && (this.x1 + this.w1) <= (this.x2 + this.w2) || this.x1 <= this.x2 &&
          (this.x1 + this.w1) >= (this.x2 + this.w2)){
            if(this.collision2 == true){
              c = true;
            }
          }
        }
        this.y1 -= speed;
        return c;
      }
      // End of Global Functions

      // Classes
      class Player{
        constructor(username, url) {
          // Game Details
          this.xPosition = 500;
          this.yPosition = 250;
          this.width = 75;
          this.height = 130;
          this.speed = 10;
          //this.x = (this.xPosition - (this.width/2)); Avatars True Position
          //this.y = (this.yPosition - (this.height/2)); Avatars True Position
          this.x = (500 - (this.width/2));
          this.y = (250 - (this.height/2));
          //this.rx = this.x;
          //this.ry = this.y;

          // Player Details (+ Player init)
          this.username = username;
          this.avatar = new Image();
          this.url = url
          this.avatar.src = this.url;
          // Draw the avatar when it loads
          /*this.avatar.onload = function(){
            screen2D.drawImage(this.avatar,this.x,this.y,this.width,this.height);
          };*/
          this.Render = function(){
            //screen2D.drawImage(this.avatar,this.x,this.y,this.width,this.height); // Render Avatars True Position
            screen2D.drawImage(this.avatar,this.x,this.y,this.width,this.height); // Render Avatar center screen
            //console.log("Rendering Avatar");
          }
          //this.UpdateX = function(){this.x = (this.xPosition - (this.width/2));} // Update Avatars True Position
          //this.UpdateY = function(){this.y = (this.yPosition - (this.height/2));}  // Update Avatars True Position
          this.UpdateX = function(speed,direction){
            var collide = false;
            // Change asset positions
            var i;for(i = 0; i < Workspace.Assets.length; i++){
              if(direction=="right"){
                if(CheckCollisionRight(this,Workspace.Assets[i],speed) == true){
                  collide = true;
                }
              }else{
                if(CheckCollisionLeft(this,Workspace.Assets[i],speed) == true){
                  collide = true;
                }
              }
            }

            // Check collision. move accordingly
            if(collide == false){
              // move
              if(direction == "right"){
                var i;for(i = 0; i < Workspace.Assets.length; i++){
                  Workspace.Assets[i].x -= speed;
                }
                this.xPosition += speed;
              }else{
                var i;for(i = 0; i < Workspace.Assets.length; i++){
                  Workspace.Assets[i].x += speed;
                }
                this.xPosition -= speed;
              }
            }
            // Change player positions
          }

          this.UpdateY = function(speed,direction){
            var collide = false;
            // Change asset positions
            var i;for(i = 0; i < Workspace.Assets.length; i++){
              if(direction=="up"){
                if(CheckCollisionUp(this,Workspace.Assets[i],speed) == true){
                  collide = true;
                }
              }else{
                if(CheckCollisionDown(this,Workspace.Assets[i],speed) == true){
                  collide = true;
                }
              }
            }
            // Check collision. move accordingly
            if(collide == false){
              // move
              if(direction == "up"){
                var i;for(i = 0; i < Workspace.Assets.length; i++){
                  Workspace.Assets[i].y += speed;
                }
                this.yPosition -= speed;
              }else{
                var i;for(i = 0; i < Workspace.Assets.length; i++){
                  Workspace.Assets[i].y -= speed;
                }
                this.yPosition += speed;
              }
            }
            // Change player positions
          }

          this.MoveRight = function(speed){this.UpdateX(speed,"right");}
          this.MoveLeft = function(speed){this.UpdateX(speed,"left");}
          this.MoveUp = function(speed){this.UpdateY(speed,"up");}
          this.MoveDown = function(speed){this.UpdateY(speed,"down");}
          Workspace.Players.push(this);
        }
      }
      class Asset{
        constructor(name, url, x, y, width, height, collision = true) {
          // Game Details
          this.xPosition = x;
          this.yPosition = y;
          this.width = width;
          this.height = height;
          this.collision = collision;
          this.x = (this.xPosition - (this.width/2));
          this.y = (this.yPosition - (this.height/2));
          // Player Details (+ Player init)
          this.assetName = name; // remove spaces
          this.asset = new Image();
          this.url = url;
          this.asset.src = this.url;
          this.visible = true;

          this.toggleVisibility = function(){
            if(this.visible){this.visible = false;}else{this.visible = true;}
          }
          /*this.asset.onload = function(){
            lol
          };*/
          this.Render = function(){
            if(this.visible != false){
              screen2D.drawImage(this.asset,this.x,this.y,this.width,this.height);
            }
            //console.log("Rendering " + this.assetName);
          }
          Workspace.Assets.push(this);
        }
      }
      class TextUI{
        constructor(font, x, y, text = "textUI", color = "black") {
          this.font = font
          this.x = x;
          this.y = y;
          this.text = text;
          this.color = color;
          this.visible = true;

          this.toggleVisibility = function(){
            if(this.visible){this.visible = false;}else{this.visible = true;}
          }

          this.Render = function(){
            if(this.visible != false){
              screen2D.font = this.font;
              screen2D.fillStyle = this.color;
              screen2D.fillText(this.text, this.x, this.y);
            }
          }

          Workspace.Gui.push(this);
        }
      }
      class RTTextUI{
        constructor(font, x, y, text = "textUI", color = "black") {
          this.font = font
          this.x = x;
          this.y = y;
          this.text = text;
          this.color = color;
          this.visible = true;

          this.toggleVisibility = function(){
            if(this.visible){this.visible = false;}else{this.visible = true;}
          }

          this.Render = function(){
            if(this.visible != false){
              screen2D.font = this.font;
              screen2D.fillStyle = this.color;
              screen2D.fillText(this.text, this.x, this.y);
            }
          }

          Workspace.RTGui.push(this);
        }
      }
      class RTRectUI{
        constructor(width, height, x, y, color) {
          this.width = width;
          this.height = height;
          this.x = x;
          this.y = y;
          this.color = color;
          this.visible = true;

          this.toggleVisibility = function(){
            if(this.visible){this.visible = false;}else{this.visible = true;}
          }

          this.Render = function(){
            if(this.visible != false){
              screen2D.beginPath();
              screen2D.lineWidth = "2";
              screen2D.strokeStyle = this.color;
              screen2D.rect(this.x, this.y, this.width, this.height);
              screen2D.stroke();
            }
          }

          Workspace.RTGui.push(this);
        }
      }
      class RectUI{
        constructor(width, height, x, y, color) {
          this.width = width;
          this.height = height;
          this.x = x;
          this.y = y;
          this.color = color;
          this.visible = true;

          this.toggleVisibility = function(){
            if(this.visible){this.visible = false;}else{this.visible = true;}
          }

          this.Render = function(){
            if(this.visible != false){
              screen2D.beginPath();
              screen2D.lineWidth = "2";
              screen2D.strokeStyle = this.color;
              screen2D.rect(this.x, this.y, this.width, this.height);
              screen2D.stroke();
            }
          }

          Workspace.Gui.push(this);
        }
      }
      class RectFillUI{
        constructor(width, height, x, y, color = "white", transparency = 1) {
          this.width = width;
          this.height = height;
          this.x = x;
          this.y = y;
          this.color = color;
          this.transparency = transparency;
          this.visible = true;

          this.toggleVisibility = function(){
            if(this.visible){this.visible = false;}else{this.visible = true;}
          }

          this.Render = function(){
            if(this.visible != false){
              screen2D.beginPath();
              screen2D.lineWidth = "2";
              screen2D.globalAlpha = this.transparency;
              screen2D.fillStyle = this.color;
              screen2D.fillRect(this.x, this.y, this.width, this.height);
              screen2D.stroke();
              screen2D.globalAlpha = 1;
            }
          }

          Workspace.Gui.push(this);
        }
      }
      // End of Classes

      // Workspace Init
      var Workspace = {
        Assets: [],
        Scripts: [],
        Players: [],
        Gui: [],
        RTGui: []
      };



      // Social Script (Social Functions,events etc)

      // Prebuild
      var localPlayer = new Player("Player1","{{Auth::user()->avatar_url}}");
      var IslandBackdrop = new Asset("Island", "/imgs/games/islandbuild/islandbackdrop.png", 0, 0, 2500, 2500, false);
      var Palm = new Asset("Palm", "/imgs/games/islandbuild/palm.png", 100, 135, 178, 323, false);
      var PalmLeaves = new Asset("PalmLeaves", "/imgs/games/islandbuild/palmleaves.png", 145, 0, 381, 190, false);
      Workspace.Gui.push(PalmLeaves);
      var Plank = new Asset("Plank", "/imgs/games/islandbuild/plank.png", 900, 400, 626, 177, false);
      // World Building
      // building Collisions
      var plankstop1 = new Asset("plankstop1",EmptyImage(),740,340,50,278);
      //var plankstop1border = new RectUI(plankstop1.width,plankstop1.height,plankstop1.x,plankstop1.y,"Black");
      //Workspace.Assets.push(plankstop1border);
      var plankstop2 = new Asset("plankstop2",EmptyImage(),220,490,1000,50);
      //var plankstop2border = new RectUI(plankstop2.width,plankstop2.height,plankstop2.x,plankstop2.y,"Black");
      //Workspace.Assets.push(plankstop2border);
      var plankstop3 = new Asset("plankstop3",EmptyImage(),-270,400,50,500);
      //var plankstop3border = new RectUI(plankstop3.width,plankstop3.height,plankstop3.x,plankstop3.y,"Black");
      //Workspace.Assets.push(plankstop3border);
      var plankstop4 = new Asset("plankstop4",EmptyImage(),-350,125,250,50);
      //var plankstop4border = new RectUI(plankstop4.width,plankstop4.height,plankstop4.x,plankstop4.y,"Black");
      //Workspace.Assets.push(plankstop4border);
      var plankstop5 = new Asset("plankstop5",EmptyImage(),-460,-260,50,800);
      //var plankstop5border = new RectUI(plankstop5.width,plankstop5.height,plankstop5.x,plankstop5.y,"Black");
      //Workspace.Assets.push(plankstop5border);
      var plankstop6 = new Asset("plankstop6",EmptyImage(),-40,-610,900,50);
      //var plankstop6border = new RectUI(plankstop6.width,plankstop6.height,plankstop6.x,plankstop6.y,"Black");
      //Workspace.Assets.push(plankstop6border);
      var plankstop7 = new Asset("plankstop7",EmptyImage(),435,-235,50,800);
      //var plankstop7border = new RectUI(plankstop7.width,plankstop7.height,plankstop7.x,plankstop7.y,"Black");
      //Workspace.Assets.push(plankstop7border);
      var plankstop8 = new Asset("plankstop8",EmptyImage(),665,190,485,50);
      //var plankstop8border = new RectUI(plankstop8.width,plankstop8.height,plankstop8.x,plankstop8.y,"Black");
      //Workspace.Assets.push(plankstop8border);


      // GUI Variable Gather loop
      // System Builds
      var localPlayerTag = new RectFillUI(75,25,localPlayer.x,localPlayer.y,"#FFFFFF",0.575);
      var localPlayerTagName = new TextUI("12px Arial",localPlayer.x+3,localPlayer.y+16.5,"{{Auth::user()->username}}");
      var localMenuBackdrop = new RectFillUI(1000,500,0,0,"#000000",0.275);localMenuBackdrop.visible = false;
      var localMenuWIP = new TextUI("54px Arial",320,250,"Menu is a WIP","white");localMenuWIP.visible = false;

      // User builds
      var EngineerOnly  = new TextUI("20px Arial",10,35,"Engineer Build 1.1");

      // Main Island Script Loop

      // Allow for Gui instanciations
      var rtguiLoop = setInterval(function() {
        // Allow for Gui instanciations
        var playerpos = new RTTextUI("25px Arial",815,485,"X: " + localPlayer.xPosition + " Y: " + localPlayer.yPosition);
      }, (1000 / 60));

      //var brickborder = new RectUI(Brick.width,Brick.height,Brick.x,Brick.y,"Blue");
      //var brickpos = new TextUI("25px Arial",20,485,"X: " + Brick.x + " Y: " + Brick.y);
      //var mousepos  = new TextUI("25px Arial",450,485,"X: " + Mouse.x + " Y: " + Mouse.y);
      //var playerborder = new RectUI(localPlayer.width,localPlayer.height,localPlayer.x,localPlayer.y,"Yellow");


      // End of Social Script

      // Make Collisions array (for loop)
      /*var c;for(c=0;c<Workspace.Assets.length;c++){
        if(Workspace.Assets[c].collision == true){
          WorkspaceC.push(Workspace.Assets[c]);
        }
      }*/
      // End of Workspace Init

      // Engine Clean Up
      window.addEventListener('keydown',check,false);
      function check(e) {
        var code = e.keyCode;
        if(code == 68){localPlayer.MoveRight(10);}
        if(code == 87){localPlayer.MoveUp(10);}
        if(code == 65){localPlayer.MoveLeft(10);}
        if(code == 83){localPlayer.MoveDown(10);}
        if(code == 32){console.log(Workspace);} // debugging
        if(code == 27){localMenuBackdrop.toggleVisibility();localMenuWIP.toggleVisibility();}
      }

      var refreshRate = setInterval(function() {
        screenClear();
        // Rerender Assets
        var i;for(i=0;i<Workspace.Assets.length;i++){
          Workspace.Assets[i].Render();
        }
        // Rerender the local Player
        Workspace.Players.find(player => player.username === localPlayer.username).Render();
        // Rerender Gui
        var ii;for(ii=0;ii<Workspace.Gui.length;ii++){
          Workspace.Gui[ii].Render();
        }
        //Rerender real time gui
        var iii;for(iii=0;iii<Workspace.RTGui.length;iii++){
          Workspace.RTGui[iii].Render();
        }
        // Cleart Realtime Gui
        Workspace.RTGui = [];
      }, (1000 / 60));
      // End of Engine Clean Up
      </script>
    </center>
    ENBUILD1.1 TEST
  </div>
</div>
@endsection

@section('footer')
