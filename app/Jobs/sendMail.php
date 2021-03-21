<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class sendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $path = '';
    protected $emailData;
    protected $email = '';
    protected $subject = '';

    public function __construct($path, $emailData, $email, $subject)
    {
        $this->path = $path;
        $this->emailData = $emailData;
        $this->email = $email;
        $this->subject = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send($this->path, $this->emailData, function ($message) {
            $message->to($this->email, 'ekampusum')->subject($this->subject);
        });
    }
}
