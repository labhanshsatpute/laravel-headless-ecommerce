<?php

namespace App\Jobs\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPasswordResetMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email, $url;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $url)  
    {
        $this->email = $email;
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
