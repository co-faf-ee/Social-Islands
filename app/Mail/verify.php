<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Users;
use DB;

class verify extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Users $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // get hash
        $last_email = DB::table('users_email_verify')->where('user_id',$this->user->id)->first();
        return $this->subject('Verify your Social Islands account')->view('emails.verify')->with('Hash',$last_email->hash)->with('Domain','socialislands.net');
    }
}
