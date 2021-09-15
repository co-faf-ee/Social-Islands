<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Users;
use App\Store;
use DB;
use \stdClass;
use Auth;
use Redirect;
use Cookie;
use Mail;
use App\Mail\verify;
use Profanity;

class UsersController extends PagesController
{
    public function showSignUp(){
          return view('/signup');
    }

    public function signUp(Request $request){
      $request->validate([
        'username' => ['required','min:4','max:20','regex:/\\A[a-z\\d]+(?:[.-][a-z\\d]+)*\\z/i','unique:users'],
        //'username.regex' => 'The username can only have alphanumeric characters, . and _',
        'email' => ['required','email','max:255','unique:users'],
        'password' => ['required','confirmed','min:6','max:255'],
        'beta' => ['required']
      ]);

      $password = $request->input('password');
      $key = $request->input('beta');
      $IP = $request->ip();
      $IPcheck = DB::table('users')->where('ip', $IP)->count();
      if($key != "iwantoldsite"){return view('/login')->withErrors(['Wrong beta key, join the discord to find out!']);}
      if($IPcheck >= 3){
        return view('/login')->withErrors(['You have already made enough accounts in your household :(']);
      }else{
        $newpw =  bcrypt($password);
        $account = new Users;
        $account->username = $request->username;
        $account->email = $request->email;
        $account->password = $newpw;
        $account->joined = time();
        $account->IP = $IP;
        $account->save();
        DB::table('users_avatars')->insert(['user_id' => $account->id]);
        app('App\Http\Controllers\AuthUserController')->doLogin($request);// home
        return view('/dashboard');
      }
        // User::create($request['username','password','email'])
    }

    public function showLogin(){
          return view('/login');
    }

    public function showDashboard(){
          return view('/dashboard');
    }

    public function showBanned(){
      $bans = DB::table('ban_logs')->select('id','length','reason','start_time')->where('user_id','=',Auth::user()->id)->get();
      $nbans = [];
      foreach ($bans as $ban) {
        // check if ban expired
        $now = time();
        $ban_till = $ban->start_time + $ban->length;
        if($ban_till > $now){
          $curban = new stdClass;
          $curban->id = $ban->id;
          $curban->reason = $ban->reason;
          $curban->expire = date('l, jS \of F Y (g:iA)',$ban_till);
          $nbans[] = $curban;
        }
      }
      return view('/banned',compact('nbans'));
    }

    public function showCustomiseCategory(request $request, $layer){
      $item_info = DB::table('users_inventory')->where('user_id',Auth::user()->id)->orderBy('id','DESC')->get();
      //dd($item_info);
      $item_ids = [];
      foreach ($item_info as $user_item_record) {
        if(!Store::where('id',$user_item_record->item_id)->exists()){DB::table('users_inventory')->where('item_id',$user_item_record->item_id)->delete();continue;} // delete from inven
        $item_ids[] = $user_item_record->item_id;
      }
      $item_ids = array_reverse($item_ids);
      //dd($item_ids);
      switch (strtolower($layer)) {
          case "gear":
              $items = Store::whereIn('id',$item_ids)->where('layer',"Gear")->orderBy('id','DESC')->paginate(12);
              break;
          case "hats":
              $items = Store::whereIn('id',$item_ids)->where('layer', '=', 'Hat1')->orWhere('layer', '=', 'Hat2')->whereIn('id',$item_ids)->orderBy('id','DESC')->paginate(12);
              break;
          case "hair":
              $items = Store::whereIn('id',$item_ids)->where('layer',"Hair")->orderBy('id','DESC')->paginate(12);
              break;
          case "mask":
              $items = Store::whereIn('id',$item_ids)->where('layer','Mask')->orderBy('id','DESC')->paginate(12);
              break;
          case "eyes":
              //$where = [, ];
              $items = Store::whereIn('id',$item_ids)->where('layer', '=', 'Eyewear')->orWhere('layer','=', 'Eyes')->whereIn('id',$item_ids)->orderBy('id','DESC')->paginate(12);
              break;
          case "mouth":
              $items = Store::whereIn('id',$item_ids)->where('layer',"Mouth")->orderBy('id','DESC')->paginate(12);
              break;
          case "body":
              $items = Store::whereIn('id',$item_ids)->where('layer',"Torso")->orderBy('id','DESC')->paginate(12);
              break;
          case "clothes":
              $items = Store::whereIn('id',$item_ids)->where('layer', '=', 'Shirt')->orWhere('layer','=', 'Trousers')->whereIn('id',$item_ids)->orderBy('id','DESC')->paginate(12);
              break;
          case "backgrounds":
              $items = Store::whereIn('id',$item_ids)->where('layer',"Background")->orderBy('id','DESC')->paginate(12);
              break;
          default:
              redirect('/avatar')->witherrors("Category $layer unfortunately does not exist!");
              break;
      }
      //dd($items);
      // Get Wearing
      $render_row = DB::table('users_avatars')->where('user_id', '=', Auth::user()->id)->first();
      $render_array = ["Gear"=>$render_row->gear_id,"Hat1"=>$render_row->top_hat_id,"Hat2"=>$render_row->bottom_hat_id,"Hair"=>$render_row->hair_id,"Mask"=>$render_row->mask_id,"Eyewear"=>$render_row->eyewear_id,"Eyes"=>$render_row->eyes_id,"Mouth"=>$render_row->mouth_id,"Torso"=>$render_row->torso_id,"Shirt"=>$render_row->shirt_id,"Trousers"=>$render_row->trousers_id,"Skin"=>$render_row->skin_id,"Background"=>$render_row->background_id];

      $wearing = [];
      foreach ($render_array as $layer => $item_id) {
        if($item_id != -1 && $layer != "Skin"){
          // Add to wearing array
          $item = Store::find($item_id);
          array_push($wearing, ["$layer"=>$item]);
        }
      }

      //dd($wearing);
      //$items = Store::whereIn('id', $itemIds)->havingRaw('count(*) > 1')->get();
      //dd($items);
      return view('/avatar', compact('items'))->withpagi($items)->withwearing($wearing);
    }

    public function showCustomise(){

      $item_info = DB::table('users_inventory')->where('user_id',Auth::user()->id)->orderBy('id','DESC')->paginate(12);
      $item_ids = [];
      foreach ($item_info as $user_item_record) {
        if(!Store::where('id',$user_item_record->item_id)->exists()){
          // delete from inven
          DB::table('users_inventory')->where('item_id',$user_item_record->item_id)->delete();
          continue;
        }
        $item_ids[] = $user_item_record->item_id;
      }
      //$item_ids = array_reverse($item_ids);
      $items = Store::whereIn('id',$item_ids)->orderBy('id','DESC')->get();

      // Get Wearing
      $render_row = DB::table('users_avatars')->where('user_id', '=', Auth::user()->id)->first();
      $render_array = ["Gear"=>$render_row->gear_id,"Hat1"=>$render_row->top_hat_id,"Hat2"=>$render_row->bottom_hat_id,"Hair"=>$render_row->hair_id,"Mask"=>$render_row->mask_id,"Eyewear"=>$render_row->eyewear_id,"Eyes"=>$render_row->eyes_id,"Mouth"=>$render_row->mouth_id,"Torso"=>$render_row->torso_id,"Shirt"=>$render_row->shirt_id,"Trousers"=>$render_row->trousers_id,"Skin"=>$render_row->skin_id,"Background"=>$render_row->background_id];

      $wearing = [];
      foreach ($render_array as $layer => $item_id) {
        if($item_id != -1 && $layer != "Skin"){
          // Add to wearing array
          $item = Store::find($item_id);
          array_push($wearing, ["$layer"=>$item]);
        }
      }

      //dd($wearing);
      //$items = Store::whereIn('id', $itemIds)->havingRaw('count(*) > 1')->get();
      //dd($items);
      return view('/avatar', compact('items'))->withpagi($item_info)->withwearing($wearing);
    }

    public function doCustomise($id){
      // check if we have the item
      $item = Store::findOrFail($id);
      if($item->hidden == 1){ return view('/avatar')->witherrors("Due to a consensus of the all beta players, this item is unwearable. Please try again later");sleep(1);exit();}
      if(DB::table('users_inventory')->where([['user_id', '=', Auth::user()->id],['item_id', '=', $id]])->exists()){
        // they got the hat render (they should have a render table from sign up)
        $render_row = DB::table('users_avatars')->where('user_id', '=', Auth::user()->id)->first();
        $item_layer_reserve = $item->layer;
        $items_ = ["Gear"=>$render_row->gear_id,"Hat1"=>$render_row->top_hat_id,"Hat2"=>$render_row->bottom_hat_id,"Hair"=>$render_row->hair_id,"Mask"=>$render_row->mask_id,"Eyewear"=>$render_row->eyewear_id,"Eyes"=>$render_row->eyes_id,"Mouth"=>$render_row->mouth_id,"Torso"=>$render_row->torso_id,"Shirt"=>$render_row->shirt_id,"Trousers"=>$render_row->trousers_id,"Skin"=>$render_row->skin_id,"Background"=>$render_row->background_id];
        $new_items_ = [];
        //dd($items_);
        foreach ($items_ as $layer => $item_id) {
          if($item_layer_reserve == $layer){
            //dd($item_layer_reserve.":".$item->id." ~ ".$layer.":".$item_id);
            if($item_id == $item->id){
              $new_items_[] = -1;
            }else{
              $new_items_[] = $item->id;
            }
          }else{
            $new_items_[] = $item_id;
          }
        }
        //dd($new_items_);
        //dd($dr."/imgs/avatars/avatar-default.png");
        // render all though
        // get skin tone
        if($new_items_[11]==-1){
          $skin_url = "/imgs/avatars/avatar-default.png";
        }else{
          //$skin_tone = abs($new_items_[9] + 1);
          $skin_help = $new_items_[11] + 1;
          $skin_url = "/imgs/avatars/avatar$skin_help.png";
        }
        // get eyes and mouth
        if($new_items_[6]==-1){
          $eyes_url = "/imgs/avatars/avatar-eyes-default.png";
        }

        if($new_items_[7]==-1){
          $mouth_url = "/imgs/avatars/avatar-mouth-default.png";
        }

        if($new_items_[9]==-1){
          $shirt_url = "/imgs/avatars/avatar-shirt-default.png";
        }

        if($new_items_[10]==-1){
          $trousers_url = "/imgs/avatars/avatar-trousers-default.png";
        }

        //dd($new_items_);


        //dd($skin_url);

        // get other layers imgs
        $item_imgs = [];
        $count = -1;
        foreach ($new_items_ as $item_id) {
          $count++;
          if($item_id == -1 && $count != 11 && $count != 6 && $count != 7 && $count != 9 &&  $count != 10){
            $item_imgs[] = "/imgs/avatars/empty.png";
          }else{
              if($item_id < -1){
                $item_imgs[] = $skin_url;
              }elseif($item_id == -1 && $count != 6 && $count != 7 && $count != 9 &&  $count != 10){
                $item_imgs[] = $skin_url;
              }elseif($item_id == -1 && $count == 6 || $item_id == -1 && $count == 7){
                if($count == 6){$item_imgs[] = $eyes_url;}else{$item_imgs[] = $mouth_url;}
              }elseif($item_id == -1 && $count == 9 || $item_id == -1 && $count == 10){
                if($count == 9){$item_imgs[] = $shirt_url;}else{$item_imgs[] = $trousers_url;}
              }else{
                if(!Store::where('id',$item_id)->exists()){
                  $item_imgs[] = "/imgs/avatars/empty.png";
                }else{
                  $item = Store::find($item_id);
                  $item_imgs[] = $item->img_url;
                }
                //dd($item->item_name);
              }
            }
        }

        //dd($item_imgs);
        $dr = $_SERVER['DOCUMENT_ROOT']."/public/";
        //$dr = $_SERVER['DOCUMENT_ROOT'];
        //init all imgs
        $base = imagecreatefrompng($dr.$item_imgs[11]);
        $gear = imagecreatefrompng($dr.$item_imgs[0]);
        $hat1 = imagecreatefrompng($dr.$item_imgs[1]);
        $hat2 = imagecreatefrompng($dr.$item_imgs[2]);
        $hair = imagecreatefrompng($dr.$item_imgs[3]);
        $mask = imagecreatefrompng($dr.$item_imgs[4]);
        $eyewear = imagecreatefrompng($dr.$item_imgs[5]);
        $eyes = imagecreatefrompng($dr.$item_imgs[6]);
        $mouth = imagecreatefrompng($dr.$item_imgs[7]);
        $torso = imagecreatefrompng($dr.$item_imgs[8]);
        $shirt = imagecreatefrompng($dr.$item_imgs[9]);
        $trousers = imagecreatefrompng($dr.$item_imgs[10]);
        $background = imagecreatefrompng($dr.$item_imgs[12]);
        // layer them by order
        imageSaveAlpha($background, true); // make it transparent
        imagecopy($background,$base,0,0,0,0,450,800); // add background
        imagecopy($background,$trousers,0,0,0,0,450,800); // add trousers
        imagecopy($background,$shirt,0,0,0,0,450,800); // add shirt
        imagecopy($background,$torso,0,0,0,0,450,800); // add torso
        imagecopy($background,$mouth,0,0,0,0,450,800); // add mouth
        imagecopy($background,$eyes,0,0,0,0,450,800); // add eyes
        imagecopy($background,$eyewear,0,0,0,0,450,800); // add eyewear
        imagecopy($background,$mask,0,0,0,0,450,800); // add mask
        imagecopy($background,$hair,0,0,0,0,450,800); // add hair
        imagecopy($background,$hat2,0,0,0,0,450,800); // add bottom hat
        imagecopy($background,$hat1,0,0,0,0,450,800); // add top hat
        imagecopy($background,$gear,0,0,0,0,450,800); // add tools and equipment
        $neg = (-$id-1);
        $un = Auth::user()->username;
        imagepng($background,$dr."/imgs/avatars/users/$un.png");
        $avatar_path = "/imgs/avatars/users/$un.png";
        $our_user = Users::find(Auth::user()->id);
        $itemp = Store::findOrFail($id);
        if($itemp->layer == "Hat1" || $itemp->layer == "Hat2"){if($itemp->layer == "Hat1"){$column_name = "top_hat_id";}else{$column_name = "bottom_hat_id";}}else{$column_name = strtolower($itemp->layer)."_id";}
        if(in_array($itemp->id,$new_items_)){$checkid=$itemp->id;}else{$checkid=-1;}
        DB::table('users_avatars')->where('user_id', $our_user->id)->update([$column_name => $checkid]);
        $our_user->avatar_url = $avatar_path;
        $our_user->save();
        return redirect('/avatar');
        // redirect to normal view /avatar
      }else{
        return redirect('/avatar')->witherrors("You do not have this item! Nice try though");
      }

    }

    public function doCustomiseSkin($id){
      // update their skins in table
      if($id > 14){return view('/avatar')->witherrors("Error, The item you requested for is invalid");}
      if($id < 0){return view('/avatar')->witherrors("Error, The item you requested for is invalid");}
      $base = "/imgs/avatars/avatar-$id.png";
      $render_row = DB::table('users_avatars')->where('user_id', '=', Auth::user()->id)->first();
      $items_ = ["Gear"=>$render_row->gear_id,"Hat1"=>$render_row->top_hat_id,"Hat2"=>$render_row->bottom_hat_id,"Hair"=>$render_row->hair_id,"Mask"=>$render_row->mask_id,"Eyewear"=>$render_row->eyewear_id,"Eyes"=>$render_row->eyes_id,"Mouth"=>$render_row->mouth_id,"Torso"=>$render_row->torso_id,"Shirt"=>$render_row->shirt_id,"Trousers"=>$render_row->trousers_id,"Skin"=>$render_row->skin_id,"Background"=>$render_row->background_id];
      $item_imgs = [];
      foreach ($items_ as $layer => $item_id) {
        if($layer == "Skin"){
          $item_imgs[] = $base;
        }else{
          if($item_id == -1){
            if($layer == "Eyes"){
              $item_imgs[] = "/imgs/avatars/avatar-eyes-default.png";
            }elseif($layer == "Mouth"){
              $item_imgs[] = "/imgs/avatars/avatar-mouth-default.png";
            }else{
              $item_imgs[] = "/imgs/avatars/empty.png";
            }
          }else{
            if(!Store::where('id',$item_id)->exists()){
              $item_imgs[] = "/imgs/avatars/empty.png";
            }else{
              $item = Store::find($item_id);
              $item_imgs[] = $item->img_url;
            }
          }
        }
      }
      //dd($item_imgs);
      $dr = $_SERVER['DOCUMENT_ROOT']."/public/";
      //$dr = $_SERVER['DOCUMENT_ROOT'];
      $base = imagecreatefrompng($dr.$item_imgs[11]);
      $gear = imagecreatefrompng($dr.$item_imgs[0]);
      $hat1 = imagecreatefrompng($dr.$item_imgs[1]);
      $hat2 = imagecreatefrompng($dr.$item_imgs[2]);
      $hair = imagecreatefrompng($dr.$item_imgs[3]);
      $mask = imagecreatefrompng($dr.$item_imgs[4]);
      $eyewear = imagecreatefrompng($dr.$item_imgs[5]);
      $eyes = imagecreatefrompng($dr.$item_imgs[6]);
      $mouth = imagecreatefrompng($dr.$item_imgs[7]);
      $torso = imagecreatefrompng($dr.$item_imgs[8]);
      $shirt = imagecreatefrompng($dr.$item_imgs[9]);
      $trousers = imagecreatefrompng($dr.$item_imgs[10]);
      $background = imagecreatefrompng($dr.$item_imgs[12]);
      // layer them by order
      imageSaveAlpha($background, true); // make it transparent
      imagecopy($background,$base,0,0,0,0,450,800); // add background
      imagecopy($background,$trousers,0,0,0,0,450,800); // add trousers
      imagecopy($background,$shirt,0,0,0,0,450,800); // add shirt
      imagecopy($background,$torso,0,0,0,0,450,800); // add torso
      imagecopy($background,$mouth,0,0,0,0,450,800); // add mouth
      imagecopy($background,$eyes,0,0,0,0,450,800); // add eyes
      imagecopy($background,$eyewear,0,0,0,0,450,800); // add eyewear
      imagecopy($background,$mask,0,0,0,0,450,800); // add mask
      imagecopy($background,$hair,0,0,0,0,450,800); // add hair
      imagecopy($background,$hat2,0,0,0,0,450,800); // add bottom hat
      imagecopy($background,$hat1,0,0,0,0,450,800); // add top hat
      imagecopy($background,$gear,0,0,0,0,450,800); // add tools and equipment
      $neg = (-$id-1);
      $un = Auth::user()->username;
      imagepng($background,$dr."/imgs/avatars/users/$un.png");
      $avatar_path = "/imgs/avatars/users/$un.png";
      $our_user = Users::find(Auth::user()->id);
      $our_user->avatar_url = $avatar_path;
      DB::table('users_avatars')->where('user_id', $our_user->id)->update(["skin_id" => $neg]);
      $our_user->save();
      return redirect('/avatar');
    }

    public function resetCustomise(){
      DB::table('users_avatars')->where('user_id', Auth::user()->id)->update([
        'gear_id' => -1,
        'top_hat_id' => -1,
        'bottom_hat_id' => -1,
        'mask_id' => -1,
        'eyes_id' => -1,
        'mouth_id' => -1,
        'torso_id' => -1,
        'shirt_id' => -1,
        'trousers_id' => -1,
        'skin_id' => -1,
        'eyewear_id' => -1,
        'hair_id' => -1,
        'background_id' => -1
      ]);

      $our_user = Users::find(Auth::user()->id);
      $our_user->avatar_url = "/imgs/avatars/avatar-reset.png";
      $our_user->save();
      return redirect('/avatar');
    }

    public function showUser(Users $user, $id){
      $user = Users::findOrFail($id);
      $item_info = DB::table('users_inventory')->where('user_id',$user->id)->orderBy('id','DESC')->paginate(6);
      $island_info = DB::table('islands')->where('creator_id',$user->id)->where('active',1)->orderBy('id','DESC')->paginate(8);
      $items = [];
      foreach ($item_info as $user_item_record) {
        // check if item still exists, if not delete it from the inventory
        if(!Store::where('id',$user_item_record->item_id)->exists()){
          // delete from inven
          DB::table('users_inventory')->where('item_id',$user_item_record->item_id)->delete();
          continue;
        }

        $item_store_info = Store::find($user_item_record->item_id);
        //if($item_store_info->layer == "Shirt"){}
        $item = new stdClass;
        $item->id = $user_item_record->item_id;
        $item->serial = $user_item_record->serial;
        //$item_store_info = Store::where('id', $user_item_record->item_id);

        $item->img_url = $item_store_info->img_url;
        $item->gold_only = $item_store_info->gold_only;
        $item->rare = $item_store_info->rare;
        $item->layer = $item_store_info->layer;
        //echo $item_store_info->id;
        $item->item_name = $item_store_info->item_name;
        $items[] = $item;
      }

      //dd($items);

      //$items = Store::whereIn('id', $itemIds)->havingRaw('count(*) > 1')->get();
      //$items = Store::whereIn('id', function ( $query ) {$query->select('id')->from('store')->havingRaw('count(*) > 1');})->get();
      $joined_at = date('jS \of M Y', $user->joined);
      $now = time();
      $diff = $now - $user->last_online;
      if($diff < 300){
        // online
        $online = true;
      }else{
        $online = false;
      }
      return view('/user', compact('items'))->withuser($user)->withjoined($joined_at)->withonline($online)->withpagi($item_info)->withislands($island_info);//->withitems($items);
    }

    public function showSettings(){
      function obfuscate_email($email)
      {
          $em   = explode("@",$email);
          $name = implode(array_slice($em, 0, count($em)-1), '@');
          $len  = floor(strlen($name)/2);

          return substr($name,0, $len) . str_repeat('•', $len) . "@" . end($em);
      }
      $obe = obfuscate_email(Auth::user()->email);

      if(DB::table('client_keys')->where('user_id', Auth::user()->id)->exists()){
        $key = DB::table('client_keys')->where('user_id', Auth::user()->id)->pluck('key');
        $key = $key[0];
      }else{
        $key = "None";
      }
      return view('/account')->withobe($obe)->withkey($key);
    }

    public function toggleDark(){
      if(isset($_COOKIE['theme'])){
        Cookie::queue(Cookie::forget('theme'));
        return redirect('/dashboard');//->withCookie(Cookie::forget('theme'));
      }else{
        Cookie::queue("theme", "dark", 2628000);
        return redirect('/dashboard');
      }
    }

    public function showBanMod(Users $user, $id){
      $user = Users::findOrFail($id);
      // return view with user info
      return view('/user_ban')->withuser($user);
    }

    public function doBan(Request $request, Users $user, $id){
      if(Auth::user()->power == "member" || Auth::user()->power == "artist"){return redirect('/store')->withErrors("You can not access this page!");}//peace}
      $user = Users::findOrFail($id);
      $request->validate([
        'reason' => ['required','min:4','max:650'],
        'ban_till' => ['required']
      ]);
      if($user->power == "senior_engineer" || $user->power == "engineer" || $user->power == "administrator" || $user->power == "moderator" ){
        return redirect('/dashboard')->withErrors("You do not have a high enough clearance level to ban this user");
      }
      // insert into mod logs and bans
      $mod_log = array('mod_id' => Auth::user()->id, 'user_id' => $user->id, 'action'=>'Banned', 'time'=>time());
      $ban_log = array('mod_id' => Auth::user()->id, 'user_id' => $user->id, 'length'=>$request->ban_till,'reason'=>$request->reason, 'start_time'=>time());
      DB::table('mod_logs')->insert($mod_log);
      DB::table('ban_logs')->insert($ban_log);
      $user->banned = 1;
      $user->save();
      return redirect()->back()->with('success','The user has been banned successfully');
    }

    public function doUnban(Users $user, $id){
      $user = Users::findOrFail($id);
      if($user->banned == 0){
        return redirect('/dashboard')->withErrors("You can not unban a user that is not banned? How have you came here!");
      }else{
        $user->banned = 0;
        DB::table('ban_logs')->where('user_id', $user->id)->delete();
        // delete bans
        $user->save();
        return redirect()->back()->with('success','This user has been unbanned successfully');
      }
    }

    public function showSearch(Request $request){
      // get users or items or etc
      if($request->has('q')){
        $string = $request->get('q');
        $users = Users::where('username','like','%' . $string . '%')->paginate(15);
        $items = Store::where('item_name','like','%' . $string . '%')->orderBy('id','DESC')->paginate(10);
        return view('search',compact('users'))->withitems($items);
      }else{
        if($request->has('online')){
          $now = time();
          $last5 = $now - 300;
          $users = Users::where('last_online','>',$last5)->paginate(50);
        }elseif($request->has('gold')){
          $users = Users::where('vip',1)->paginate(10);
        }elseif($request->has('staff')){
          $users = Users::where('power','!=','member')->paginate(10);
        }else{$users = Users::paginate(10);}

        $items = Store::orderBy('id','DESC')->paginate(10);
        return view('search',compact('users'))->withitems($items);;
      }
    }

    public function updateBio(Request $request){
      $request->validate([
        'bio' => ['required','min:4','max:375']
      ]);
      $user = Users::find(Auth::user()->id);
      $user->bio = $request->bio;
      $user->save();
      return redirect()->back()->with('success','You have updated your biography successfully!');
    }

    public function SendVerify(){
      $user = Users::find(Auth::user()->id);
      $hash = str_random(32);
      $exists = DB::table('users_email_verify')->where('user_id',$user->id)->exists();
      // check if verified
      if($user->verified==1){return redirect('/verify')->withErrors("You are already verified!");}
      if(DB::table('users_email_verify')->where('user_id',$user->id)->exists()){
        // check sent time
        $last_email = DB::table('users_email_verify')->where('user_id',$user->id)->first();
        $now = time();
        $last_5 = $now - 1; //600
        if($last_email->time_sent > $last_5){
          return redirect('/verify')->withErrors("You have already asked for a verification email within the past 10 minutes! Be patient");
        }else{
          // update sent
          DB::table('users_email_verify')->where('user_id',$user->id)->update(['time_sent'=>$now,'hash'=>$hash]);
          Mail::to($user)->send(new verify($user));
          return redirect('/dashboard')->withSuccess("Verification Email Sent!");
        }

      }else{
        // make one and send email
        DB::table('users_email_verify')->insert(array('user_id'=>$user->id,'time_sent'=>time(),'hash'=>$hash));
        Mail::to($user)->send(new verify($user));
        return redirect('/dashboard')->withSuccess("A brand new Verification Email has been sent!");
      }
    }

    public function ShowVerify(){
      return view('/verify');
    }

    public function ConfirmVerify($hash){
      // check if we verified
      $user = Users::find(Auth::user()->id);
      if($user->verified==1){return redirect('/verify')->withErrors("You are already verified!");}
      // check if we have a pending hash
      if(DB::table('users_email_verify')->where('hash',$hash)->exists()){
        $last_email = DB::table('users_email_verify')->where('hash',$hash)->first();
        $now = time();
        $last_24 = $now - 86400; //86400
        // check if their user id matches the verify id
        if($user->id != $last_email->user_id){return redirect('/verify')->withErrors("We can not validate your verification code!");}
        if($last_email->time_sent > $last_24){
          // they can accept it + VERIFY!
          $user->verified = 1;
          $user->save();
          return redirect('/verify')->withSuccess("You are now verified!");
        }else{
          return redirect('/verify')->withErrors("This code has expired, please request another verification code!");
        }
      }else{
        return redirect('/verify')->withErrors("We can not validate your verification code!");
      }

    }

    public function ShowChat(){
      $online = DB::table('users_chats_online')->where('online',1)->get();
      // get users
      $online_users = [];
      foreach ($online as $user_online) {
        $id = $user_online->user_id;
        $user = Users::find($id);
        $blade_user = new stdClass;
        $blade_user->id = $id;
        $blade_user->avatar_url = $user->avatar_url;
        $blade_user->username = $user->username;
        $blade_user->power = $user->power;
        $blade_user->vip = $user->vip;
        $online_users[] = $blade_user;
      }
      return view('/chat', compact('online_users'));
    }

    public function AbortChat(){
      return redirect('/dashboard')->withErrors("You can not access this page!");
    }

    public function SendChat(Request $request){
      //if ($request->isMethod('get')) {redirect('/dashboard')->withErrors("You can not access this page!");}
      if(strlen($request->message) > 500){
        return response()->json(['error'=>'Your message is too large to send!']);
      }else{
        if(strlen($request->message) == 0){
          return response()->json(['error'=>'You need to atleast have 1 character']);
        }
        $chatMessage = new stdClass;
        $chatMessage->message = $request->message;
        $chatMessage->user_id = Auth::user()->id;
        $chatMessage->time_sent = time();
        $chatMessage->sticky = false;
        // check time before we send
        if(DB::table('users_chats')->where('user_id',$chatMessage->user_id)->exists()){
          $time_check = DB::table('users_chats')->where('user_id',$chatMessage->user_id)->orderBy('time_sent','DESC')->first();
          //usleep(660000);
          $past3s = $chatMessage->time_sent - 2;
          if($time_check->time_sent > $past3s){return response()->json(['error'=>'Slow down!']);}
          //if($time_check->time_sent > $past3s){return response()->json(['error'=>$time_check->time_sent.','.$past3s]);}
          if($time_check->message == $chatMessage->message){return response()->json(['error'=>'Please do not send the same message']);}
        }

        usleep(660000);
        if($request->message != "cmd(load)"){
          $values = array('user_id' => $chatMessage->user_id,
                          'message' => $chatMessage->message,
                          'time_sent' => $chatMessage->time_sent,
                          'sticky' => $chatMessage->sticky);
          DB::table('users_chats')->insert($values);
        }
        $latest_messages = DB::table('users_chats')->orderBy('time_sent','DESC')->take(20)->get();
        $json_messages = [];
        foreach ($latest_messages as $message) {
          $json_a = [];
          // get the user
          $sender = Users::findOrFail($message->user_id);
          $json_a[] = $message->user_id;
          $json_a[] = Profanity::blocker($message->message)->filter();
          $json_a[] = $sender->avatar_url;
          $json_a[] = $sender->username;
          $json_a[] = $sender->vip;
          $json_a[] = $sender->power;
          $json_a[] = date("g:i A - l",$message->time_sent);
          $json_a[] = $message->sticky;
          $json_messages[] = $json_a;
        }
        return response()->json(['update'=>$json_messages]);
      }
    }

    public function showUpgrade(){
      return view("upgrade");
    }

    public function showPanel(){
      if(Auth::user()->power=="member"){return redirect('/dashboard')->withErrors("You can not access this page!");}
      return view("panel");
    }

    public function Genkey(){
      // Generate and update the key
      $key = Str::random(50);
      // Check if key is in use. If so regen
      function Updatekey($k){
        // Check if they even have a key
        if(!DB::table('client_keys')->where('user_id', Auth::user()->id)->exists()){
          // create key
          DB::table('client_keys')->insert(['user_id' => Auth::user()->id, 'key' => $k]);
        }
        if(DB::table('client_keys')->where('key', $k)->exists()){
          $k2 = Str::random(50);
          Updatekey($k2);
        }else{
          DB::table('client_keys')->where('user_id', Auth::user()->id)->update(['key' => $k]);
        }
      }

      Updatekey($key);
      return redirect('/account')->withSuccess("You have generated a new key!");
    }
    //public function TestChat($string){dd(Profanity::blocker($string)->filter());}

}
