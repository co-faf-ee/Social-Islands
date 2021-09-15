<?php

namespace App\Http\Controllers;

use App\Islands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

class IslandsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // take auth into factor

        // get  islands
        $islands = Islands::orderBy('updated','asc')->paginate(14);
        //$items = DB::table('store')->paginate(15);
        $userdetails = [];
        foreach ($islands as $island) {
          $user = Users::find($island->creator_id);
          $userdetails[] = $user->username;
        }

        return view('islands', compact('islands'))->withmakers($userdetails);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('island_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
          'name'=>['min:1','max:50'],
          'max players'=>['integer|min:1|max:50'],
          'description'=>['max:1500'],
          'islandpass'=>['max:255'],
          'locked'=>['boolean'], // passworded
          'active'=>['boolean'],
          'copylocked'=>['boolean'],
          'goldonly'=>['boolean'],
          'item_file' => 'file|max:10000|mimes:png|dimensions:width=230,height=230',
          'item_file2' => 'file|max:10000|mimes:png|dimensions:width=230,height=230',
          'item_file3' => 'file|max:10000|mimes:png|dimensions:width=230,height=230',
          'genre'=>'required',
          'status'=>'required'
        ]);

        // check how much islands they have already
        $islands = Islands::where('creator_id',Auth::user()->id)->where('active',1)->get();
        $islandsCount = $islands->count();
        if(Auth::user()->vip == 1){
          if($islandsCount >= 25){return Redirect::back()->withErrors(['You have reached the maximum ACTIVE island limit of 25']);}
        }else{
          if($islandsCount >= 10){return Redirect::back()->withErrors(['You have reached the maximum ACTIVE island limit of 10']);}
        }


        $island = new Islands;
        $island->serverName = $request->name;
        $island->maxplayers = $request->maxplayers;
        $island->password = $request->islandpass;
        $island->description = $request->desc;
        $island->locked = $request->locked;
        $island->active = $request->active;
        $island->goldonly = $request->goldonly;
        $island->genre = $request->genre;
        $island->status = $request->status;
        $island->creator_id = Auth::user()->id;

        if($request->hasFile('item_file')){
          $fileName = $request->name.".".time()."_1.png";
          $request->item_file->storeAs('game_uploads',$fileName);
          $fileLocation = "/storage/game_uploads/$fileName";
          $island->thumbnail1 = $fileLocation;
        }

        if($request->hasFile('item_file2')){
          $fileName = $request->name.".".time()."_2.png";
          $request->item_file2->storeAs('game_uploads',$fileName);
          $fileLocation = "/storage/game_uploads/$fileName";
          $island->thumbnail2 = $fileLocation;
        }

        if($request->hasFile('item_file3')){
          $fileName = $request->name.".".time()."_3.png";
          $request->item_file3->storeAs('game_uploads',$fileName);
          $fileLocation = "/storage/game_uploads/$fileName";
          $island->thumbnail3 = $fileLocation;
        }

        $island->save();
        return redirect("/islands/".$island->id)->withSuccess("You have successfully created a new island");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Islands  $islands
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $island = Islands::findOrFail($id);
      $creator = Users::find($island->creator_id);
      $created_at = date('jS M Y', $island->created);
      $updated_at = date('jS M Y', $island->updated);
      //$items = DB::table('store')->paginate(15);
      return view('island_show', compact('island'))->withcreatorid($creator->id)->withcreatorimg($creator->avatar_url)->withcreatorname($creator->username)->withcreated($created_at)->withupdated($updated_at);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Islands  $islands
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $island = Islands::findOrFail($id);
        if($island->creator_id != Auth::user()->id){return Redirect::back()->withErrors(['You do not own this island!']);}
        return view('island_edit', compact('island'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Islands  $islands
     * @return \Illuminate\Http\Response
     */
    public function update($id, request $request)
    {
        $island = Islands::findOrFail($id);
        if($island->creator_id != Auth::user()->id){return Redirect::back()->withErrors(['You do not own this island!']);}

        $request->validate([
          'name'=>'min:1|max:50',
          'maxplayers'=>'integer|min:1|max:50',
          'description'=>['max:1500'],
          'password'=>['max:255'],
          'locked'=>['boolean'], // passworded
          'active'=>['boolean'],
          'copylocked'=>['boolean'],
          'goldonly'=>['boolean'],
        ]);

        //dd($request->all());

        $input = $request->all();

        $island->fill($input)->save();
        $island->updated = time();
        $island->save();

        return redirect('/islands/edit/'.$island->id)->withSuccess("You have successfully updated this Island!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Islands  $islands
     * @return \Illuminate\Http\Response
     */
    public function destroy(Islands $islands)
    {
        //
    }

    public function play($id)
    {
      $island = Islands::findOrFail($id);
      if($island->id != 1){
        return Redirect::back()->withErrors(['Islands is not officially out yet for Beta users!']);
      }
      return view('island_play', compact('island'));
    }

    public function playtest($id)
    {
      $island = Islands::findOrFail($id);
      if($island->id != 1){
        return Redirect::back()->withErrors(['Islands is not officially out yet for Beta users!']);
      }
      return view('island_playtest', compact('island'));
    }
}
