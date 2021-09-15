<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Request;
use App\Users;
use DB;

class SocialOperations
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $site = "https://socialislands.net/banned";
        $chat = "http://epic.test/chat";
        $chat2 = "http://epic.test/chat/send";

        // user is online
        if (Auth::check()) {
          $user = Users::find(Auth::user()->id);
          $user->last_online = time();
          $user->IP = $request->ip();
          $user->save();

          // check for daily cash
          $diff = $user->last_online - $user->daily_cash;
          if($diff > 86400){
            $oldcash = $user->cash;
            $user->daily_cash = time();
            if($user->vip == 1){$newcash = $oldcash + 35;}else{$newcash = $oldcash + 10;}
            $user->cash = $newcash;
            $user->save();
          }

          $url = $request->url();
          // check if we have a chat_online record
          if(!DB::table('users_chats_online')->where('user_id',$user->id)->exists()){
            // create one
            $values = array('user_id' => $user->id,'online' => false);
            DB::table('users_chats_online')->insert($values);
          }

          if (Request::is('chat/*') || Request::is('chat')) {
            DB::table('users_chats_online')->where('user_id',$user->id)->update(['online'=>1]);
          }else{
            DB::table('users_chats_online')->where('user_id',$user->id)->update(['online'=>0]);
          }

          // check if banned
          if (DB::table('ban_logs')->where('user_id', '=', $user->id)->count() > 0) {
            //dd('found ban');
            $bans = DB::table('ban_logs')->select('id','length','reason','start_time')->where('user_id','=',$user->id)->get();
            foreach ($bans as $ban) {
              // see if they have ANY pending ban, we will just show that ban
              $now = time();
              $ban_till = $ban->start_time + $ban->length;
              if($ban_till > $now){
                // update user banned to 1, redirect
                if($user->banned != 1){$user->banned = 1;$user->save();}
                $url = $request->url();
                if($url != $site){
                  return redirect('/banned');
                }
              }else{
                // remove ban
                DB::table('ban_logs')->where('id',$ban->id)->delete();
                $user->banned = 0; $user->save();
              }
            }
          }
        }

        return $next($request);
    }
}
