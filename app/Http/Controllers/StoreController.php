<?php

namespace App\Http\Controllers;

use App\Store;
use App\Users;
use Illuminate\Http\Request;
use DB;
use Auth;
use Redirect;
use Profanity;
use stdClass;

class StoreController extends Controller
{

    public $forbidden = "You are unauthorised to view this page!";

    public function __construct()
    {
       $this->middleware('auth', ['except' => ['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Store::where('layer','!=','Shirt')->where('layer','!=','Trousers')->where('hidden','!=','1')->orderBy('id','DESC')->paginate(6);
        //$items = DB::table('store')->paginate(15);
        return view('store', compact('items'));
    }

    public function indexShirt(){
      $items = Store::where('layer','Shirt')->orderBy('id','DESC')->paginate(6);
      $userIds = Store::select('creator_id')->where('layer','Shirt')->orderBy('id','DESC')->paginate(6);
      $users = [];
      foreach ($userIds as $user_id) {
        $id = $user_id->creator_id; // just is
        // Bullshit user
        $si = new Users(); $si->username = "Social Staff"; $si->id = -1;
        if($id == -1){array_push($users,$si);}else{array_push($users,Users::find($id));}
      }
      //$items = DB::table('store')->paginate(15);
      return view('store', compact('items'))->withusers($users);
    }

    public function indexPants(){
      $items = Store::where('layer','Trousers')->orderBy('id','DESC')->paginate(6);
      $userIds = Store::select('creator_id')->where('layer','Trousers')->orderBy('id','DESC')->paginate(6);
      $users = [];
      foreach ($userIds as $user_id) {
        $id = $user_id->creator_id; // just is
        // Bullshit user
        $si = new Users(); $si->username = "Social Staff"; $si->id = -1;
        if($id == -1){array_push($users,$si);}else{array_push($users,Users::find($id));}
      }
      //$items = DB::table('store')->paginate(15);
      return view('store', compact('items'))->withusers($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->power == "senior_engineer" || Auth::user()->power == "engineer" || Auth::user()->power == "artist"){
          return view('store_create');
        }else{
          return redirect("/store");
        }

    }

    public function createAsset()
    {
        return view('store_create_asset');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      /*if($request->rare == null){$rare = false;}else{$rare = true;}
      if($request->gold == null){$gold = false;}else{$gold = true;}
      if($request->offsale == null){$offsale = false;}else{$offsale = true;}*/

      // nake sure we aint member
      if(Auth::user()->power == "member" || Auth::user()->power == "artist"){return redirect('/store')->withErrors("You can not access this page. Like really nice try, Kwame was notified");}//peace}

      $request->validate([
        'name' => 'required|max:50',
        'desc' => 'required|max:500',
        'price' => 'required|integer',
        'item_file' => 'required|file|max:10000|mimes:png|dimensions:width=450,height=800',
        'rare' => 'boolean',
        'rare_quantity' => 'integer',
        'offsale' => 'boolean',
        'gold' => 'boolean',
        'layer' => 'required|max:15'
      ]);



      $fileName = $request->name.".".time().".png";
      $fileLocation = "/storage/item_uploads/$fileName";

      $item = new Store;
      $item->item_name = $request->name;
      $item->item_description = $request->desc;
      $item->price = $request->price;
      $item->rare = $request->rare;
      $item->gold_only = $request->gold;
      $item->offsale = $request->offsale;
      $item->img_url = $fileLocation;
      $item->rare_quantity = $request->rare_quantity;
      $item->rare_quantity_original = $request->rare_quantity;
      $item->layer = $request->layer;
      $item->created = now()->timestamp;
      $item->last_updated = now()->timestamp;
      $item->save();

      $request->item_file->storeAs('item_uploads',$fileName);
      return redirect('/store');
    }

    public function upload(Request $request){
      $request->validate([
        'name' => 'required|max:50|regex:/^[\pL\s\-]+$/u',
        'desc' => 'required|max:500',
        'price' => 'required|integer',
        'item_file' => 'required|file|max:10000|mimes:png|dimensions:width=450,height=800',
        'offsale' => 'boolean',
        'gold' => 'boolean',
        'layer' => 'required:max:15'
      ]);

      $fileName = $request->name.".".time().".png";
      $fileLocation = "/storage/custom_assets/$fileName";

      // get upload time
      if(DB::table('store_custom_uploads')->where('user_id',Auth::user()->id)->where('status','pending')->exists()){
        $last_upload = DB::table('store_custom_uploads')->where('user_id',Auth::user()->id)->where('status','pending')->orderBy('created','DESC')->first();
        $past1hour = time() - 10;
        if($last_upload->created > $past1hour){
          return redirect("/store/")->withErrors("You have recently uploaded an asset! Please wait");
        }
      }

      $item = new \stdClass;
      $item->item_name = $request->name;
      $item->item_description = $request->desc;
      $item->price = $request->price;
      $item->gold_only = $request->gold;
      $item->offsale = $request->offsale;
      $item->img_url = $fileLocation;
      $item->layer = ucfirst($request->layer); // Rendering layer case senstivate fix
      $item->created = now()->timestamp;
      $item->last_updated = now()->timestamp;
      $item->user_id = Auth::user()->id;
      $item->status = "pending";
      $item_array = (array) $item;
      DB::table("store_custom_uploads")->insert($item_array);

      $request->item_file->storeAs('custom_assets',$fileName);
      return redirect("/store/assets/pending")->withSuccess("Your asset is now pending approval!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store, $id)
    {
      $item = Store::findOrFail($id);//where('layer','!=','Shirt')->where('layer','!=','Trousers')->findOrFail($id);
      // get creator info for item
      if($item->creator_id != -1){$user = Users::find($item->creator_id);$creator_name = $user->username;}else{$creator_name = null;}
      $created_at = date('jS M Y', $item->created);
      $updated_at = date('jS M Y', $item->last_updated);
      $item_comments = DB::table('store_comments')->where('item_id',$id)->orderBy('time_sent','DESC')->paginate(4);
      $item_com = [];
      foreach ($item_comments as $comment) {
        $new_com = new \stdClass;
        $that_user = Users::find($comment->user_id);
        $new_com->user_id = $comment->user_id;
        $new_com->time_sent = date("g:i A - l jS, F Y",$comment->time_sent);
        $new_com->username = $that_user->username;
        $new_com->avatar_url = $that_user->avatar_url;
        $new_com->comment = Profanity::blocker($comment->comment)->filter();
        $new_com->vip = $that_user->vip;
        $item_com[] = $new_com;
      }
      // check if we can sell (if logged in)
      if(Auth::user()){
        if($item->rare==1){
          if(DB::table('users_inventory')->where('item_id',$item->id)->where('user_id',Auth::user()->id)->exists()){$showSell = true;
          }else{$showSell = false;}
        }else{$showSell = false;}
      }else{
        $showSell = false;
      }


      // check if there are sellers on rare if quantity left is 0
      $sellers = [];
      if($item->rare_quantity == 0){
        // check if anyone selling
        if(DB::table('store_reselling')->where('item_id',$item->id)->exists()){
          // get sellers
          $selling = DB::table('store_reselling')->where('item_id',$item->id)->orderBy('price','ASC')->get();
          foreach ($selling as $seller) {
            $seller_user = Users::find($seller->user_id);
            $newSeller = new \stdClass;
            $newSeller->sale_id = $seller->id;
            $newSeller->price = $seller->price;
            $newSeller->serial = $seller->serial;
            $newSeller->isStaff = ($seller_user->power != "member") ? true : false;
            $newSeller->isVip = ($seller_user->vip != 0) ? true : false;
            $newSeller->user_id = $seller_user->id;
            $newSeller->avatar_url = $seller_user->avatar_url;
            $newSeller->username = $seller_user->username;
            $sellers[] = $newSeller;
          }
        }else{$sellers = false;}
      }else{$sellers = false;}
      return view('/store_item',compact('item_com','sellers'))->withitem($item)->withcreated($created_at)->withupdated($updated_at)->withpagi($item_comments)->withshow($showSell)->withcreator($creator_name);//->withitem_com($item_com);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        //
    }

    public function buy(Store $store, $id){
      // lets see if we have enough
      $item = Store::findOrFail($id);
      // gold only
      if($item->gold_only == 1){
        if(Auth::user()->vip != $item->gold_only){
          return Redirect::back()->withErrors(['You are not a Gold member']);
        }
      }

      // check offsale
      if($item->offsale == 1){
        return Redirect::back()->withErrors(['This item is offsale!']);
      }

      // has enough cash
      if(Auth::user()->cash < $item->price){
        return Redirect::back()->withErrors(['You dont have enough cash']);
      }

      // check if they have it
      $haveIt = DB::table('users_inventory')->where('item_id', $item->id)->where('user_id', Auth::user()->id)->first();
      if($haveIt){
        return Redirect::back()->withErrors(['You already have this item']);
      }
      // if rare or not rare
      if($item->rare == 1){
        // still in stock?
        if($item->rare_quantity > 0){
          // its still in stock we have the money buy it.
          if($item->rare_quantity_original == $item->rare_quantity){
            $serial = 1;
          }else{
            if($item->rare_quantity == 1){
              $serial = $item->rare_quantity_original;
            }else{
              $serial = $item->rare_quantity_original - ($item->rare_quantity - 1);
            }

          }

          $newStock = $item->rare_quantity - 1;

          $newMoney = Auth::user()->cash - $item->price;

          //$newSold = $item->sold + 1;

          DB::table('users')->where('id', Auth::user()->id)->update(['cash'=>$newMoney]);

          DB::table('users_inventory')->insert([
            'user_id'=>Auth::user()->id,
            'item_id'=>$item->id,
            'serial'=>$serial
          ]);

          DB::table('store')->where('id', $item->id)->update(['rare_quantity'=>$newStock,]);
          DB::table('store')->where('id', $item->id)->increment('sold', 1);
        }else{
          // not in stock
          return Redirect::back()->withErrors(['There is no more stock left of this item!']);
        }
      }else{
        // take our money
        //$newSold = $item->sold + 1;
        $newMoney = Auth::user()->cash - $item->price;
        DB::table('users')->where('id', Auth::user()->id)->update(['cash'=>$newMoney]);

        // if its shirt or trousers then give the creator a cut of the money
        if($item->layer == "Shirt" || $item->layer == "Trousers"){
          $profit = $item->price-round((30/100)*$item->price);
          $creator = Users::find($item->creator_id);
          $creator->cash += $profit;
          $creator->save();
        }

        DB::table('users_inventory')->insert([
          'user_id'=>Auth::user()->id,
          'item_id'=>$item->id,
          'serial'=>0
        ]);

        DB::table('store_logs')->insert([
          'user_id'=>Auth::user()->id,
          'seller_id'=>$item->creator_id,
          'item_id'=>$item->id,
          'price'=>$item->price,
          'time'=>time()
        ]);

        DB::table('store')->where('id', $item->id)->increment('sold', 1);
      }

      return redirect()->back()->with('success', 'You have successfully bought this item!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        //
    }

    public function comment(Store $store, $id, Request $request){
      $item = Store::findOrFail($id);
      // check commet logs if they posted commet
      $request->validate([
        'comment' => 'required|max:500'
      ]);

      if(DB::table('store_comments')->where('item_id',$id)->where('user_id',Auth::user()->id)->exists()){
        $last_comment = DB::table('store_comments')->where('item_id',$id)->where('user_id',Auth::user()->id)->orderBy('time_sent','DESC')->first();
        $now = time();
        $past1h = $now - 3600;//3600;
        if($last_comment->time_sent > $past1h){return Redirect::back()->withErrors(['You have already made a comment recently!']);}
        DB::table('store_comments')->insert([
          'user_id'=>Auth::user()->id,
          'item_id'=>$item->id,
          'comment'=>$request->comment,
          'time_sent'=>time()
        ]);
        return redirect()->back()->with('success', 'You have commented on this item!');
      }else{
        // insert
        DB::table('store_comments')->insert([
          'user_id'=>Auth::user()->id,
          'item_id'=>$item->id,
          'comment'=>$request->comment,
          'time_sent'=>time()
        ]);
        return redirect()->back()->with('success', 'You have commented on this item!');
      }
    }

    public function showSell($id){
      $item = Store::findOrFail($id);
      if($item->rare == 0){
        return Redirect::back()->withErrors(['This item is not a rare!']);
      }
      // check if we have item then show the view with item details
      if(DB::table('users_inventory')->where('item_id',$item->id)->where('user_id',Auth::user()->id)->exists()){
        // return view
        // check if we are verified
        if(Auth::user()->verified == 0){return redirect('/verify')->withErrors("You need to verify your account in order to sell");}
        $serials_2 = DB::table('users_inventory')->select('serial')->where('item_id',$item->id)->where('user_id',Auth::user()->id)->get();
        $serials = [];
        foreach ($serials_2 as $s) {
          if(DB::table('store_reselling')->where('item_id',$item->id)->where('user_id',Auth::user()->id)->where('serial',$s->serial)->exists()){
            continue;}else{$serials[] = $s;}}
        return view('/store_sell',compact('item','serials'));
      }else{
        return Redirect::back()->withErrors(['You do not have this item!']);
      }
    }

    public function doSell($id, Request $request){
      $item = Store::findOrFail($id);
      $request->validate([
        'price' => 'required|integer|min:1|max:1000000000',
        'serial' => 'required|integer'
      ]);
      if(DB::table('users_inventory')->where('item_id',$item->id)->where('user_id',Auth::user()->id)->exists()){
        // return view
        // check if we are verified
        if(Auth::user()->verified == 0){return redirect('/verify')->withErrors("You need to verify your account in order to sell");}
        // sell with serial. make sure we have it and validate request
        // are we already selling this item?
        if(DB::table('store_reselling')->where('item_id',$item->id)->where('user_id',Auth::user()->id)->where('serial',$request->serial)->exists()){
          return Redirect::back()->withErrors(['You are already selling this item!']);
        }
        //check if the item is still selling lmao
        if($item->rare_quantity > 0){
          return Redirect::back()->withErrors(['This item is still onsale!']);
        }

        if($item->rare != 1){
          return Redirect::back()->withErrors(['This item is not a rare!']);
        }
        // do we still have the item? (specially serial)
        if(DB::table('users_inventory')->where('item_id',$item->id)->where('user_id',Auth::user()->id)->where('serial',$request->serial)->exists()){
          // sell it then i guess
          DB::table('store_reselling')->insert([
            'user_id'=>Auth::user()->id,
            'item_id'=>$item->id,
            'price'=>$request->price,
            'serial'=>$request->serial
          ]);
          return redirect('/store/'.$item->id."/")->withSuccess("You have listed your ".$item->item_name);
        }else{
          return Redirect::back()->withErrors(['You do not have this item! nice try though']);
        }
      }else{
        return Redirect::back()->withErrors(['You do not have this item!']);
      }
    }

    // avoid redirect::back

    public function buySale($id, $sale_id){
      $item = Store::findOrFail($id);
      // sale exists
      if(DB::table('store_reselling')->where('id',$sale_id)->exists()){
        // check if we have the money
        $sale = DB::table('store_reselling')->where('id',$sale_id)->first();
        if(Auth::user()->cash < $sale->price){return Redirect::back()->withErrors(['You do not have enough money to purchase this item!']);}
        // check if they still have item in inventory
        if(DB::table('users_inventory')->where('item_id',$item->id)->where('user_id',$sale->user_id)->where('serial',$sale->serial)->exists()){
          // check if we buying from ourselves
          if($sale->user_id == Auth::user()->id){return redirect('/store/'.$sale->item_id."/")->withErrors("You can not buy from yourself...");}
          // buy it
          // log purchase
          DB::table('store_logs')->insert([
            'user_id'=>Auth::user()->id,
            'seller_id'=>$sale->user_id,
            'item_id'=>$sale->item_id,
            'price'=>$sale->price,
            'time'=>time()
          ]);
          // update our cash
          $us = Users::find(Auth::user()->id);
          $ourNewCash = $us->cash - $sale->price;
          $us->cash = $ourNewCash;
          // update their cash
          $them = Users::find($sale->user_id);
          $theirNewCash = $them->cash + $sale->price;
          $them->cash = $theirNewCash;
          $us->save();
          $them->save();
          // update from their inventory
          DB::table('users_inventory')->where('item_id',$item->id)->where('user_id',$sale->user_id)->where('serial',$sale->serial)->update(['user_id'=>$us->id]);
          // remove from sales
          DB::table('store_reselling')->delete($sale->id);
          // succ
          return redirect('/store/'.$sale->item_id."/")->withSuccess("You have brought this from ".$them->username);
        }else{
          return Redirect::back()->withErrors(['That user doesn\'t have that item anymore']);}
      }else{return Redirect::back()->withErrors(['The sale could not be found!']);}
    }

    public function removeSale($id, $sale_id){
      $item = Store::findOrFail($id);
      if(DB::table('store_reselling')->where('id',$sale_id)->exists()){
        $sale = DB::table('store_reselling')->where('id',$sale_id)->first();
      }
      // check if ours, is so delete.
      if($sale->user_id == Auth::user()->id){DB::table('store_reselling')->delete($sale->id);}else{return redirect('/store/'.$sale->item_id."/")->withErrors("You can not remove another user's sale!");}
      return redirect('/store/'.$sale->item_id."/")->withSuccess("Sale removed");
    }

    public function showUploads(){
      $uploads = DB::table('store_custom_uploads')->where('user_id',Auth::user()->id)->orderBy('id','DESC')->paginate(12);
      return view('store_assets_status',compact('uploads'));
    }

    public function showUploadsMod(){
      $assets = DB::table('store_custom_uploads')->where('status','pending')->orderBy('id','DESC')->paginate(12);
      return view('store_assets_mod',compact('assets'));
    }

    public function rejectAsset($asset_id){
      if(Auth::user()->power=="member"){return redirect('/dashboard')->withErrors("You can not access this page!");}
      // check if exists
      if(DB::table('store_custom_uploads')->where('id',$asset_id)->exists()){
        $asset = DB::table('store_custom_uploads')->where('id',$asset_id)->first();
        // check if not pending
        if($asset->status != "pending"){return redirect('/store/assets/moderate')->withErrors("This item has already passed moderation stage!");}
        // reject / accept
        DB::table("store_custom_uploads")->where('id',$asset->id)->update(['status'=>'rejected']);
        // mod log it
        DB::table("mod_logs")->insert(['mod_id'=>Auth::user()->id,'user_id'=>$asset->user_id,'action'=>'Rejected Asset ID #'.$asset->id,'time'=>time()]);
        return redirect('/store/assets/moderate')->withSuccess("you have rejected this asset!");
        // inject into store
      }else{
        return redirect('/store/assets/moderate')->withErrors("This asset can not be edited at this current time");
      }
    }

    public function acceptAsset($asset_id){
      if(Auth::user()->power=="member"){return redirect('/dashboard')->withErrors("You can not access this page!");}
      // check if exists
      if(DB::table('store_custom_uploads')->where('id',$asset_id)->exists()){
        $asset = DB::table('store_custom_uploads')->where('id',$asset_id)->first();
        // check if not pending
        if($asset->status != "pending"){return redirect('/store/assets/moderate')->withErrors("This item has already passed moderation stage!");}
        // mod log it
        DB::table("mod_logs")->insert(['mod_id'=>Auth::user()->id,'user_id'=>$asset->user_id,'action'=>'Accepted Asset ID #'.$asset->id,'time'=>time()]);
        // inject into store
        $nasset = new Store;
        $nasset->item_name = $asset->item_name;
        $nasset->item_description = $asset->item_description;
        $nasset->price = $asset->price;
        $nasset->offsale = $asset->offsale;
        $nasset->gold_only = $asset->gold_only;
        $nasset->created = $asset->created;
        $nasset->last_updated = time();
        $nasset->img_url = $asset->img_url;
        $nasset->layer = ucfirst($asset->layer);
        $nasset->creator_id = $asset->user_id;
        $nasset->save();
        // give them a copy
        DB::table("users_inventory")->insert(['item_id'=>$nasset->id,'user_id'=>$nasset->creator_id,'serial'=>'0']);
        // accept
        DB::table("store_custom_uploads")->where('id',$asset->id)->update(['status'=>'accepted']);
        return redirect('/store/'.$nasset->id)->withSuccess("you have accepted this asset!");
      }else{
        return redirect('/store/assets/moderate')->withErrors("This asset can not be edited at this current time");
      }
    }
}
