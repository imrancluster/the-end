<?php

namespace App\Console\Commands;

use App\Http\Resources\LivingResource;
use App\Living;
use App\Mail\SendMailable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ActiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'active:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email every 15 days to check the member is active.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $allLivings = Living::all();

        $livings = [];

        foreach ($allLivings as $living) {
            $livings[] = [
                'id' => $living->id,
                'last_email_sent' => $living->last_email_sent,
                'send_email_after' => $living->send_email_after,
                'last_email_seen' => $living->last_email_seen,
                'user' => [
                    'id' => $living->user->id,
                    'name' => $living->user->name,
                    'emailc' => $living->user->email,
                ],
            ];
        }

        // Mail::to('imrancluster@test.com')->send(new SendMailable($totalUsers));
    }
}
