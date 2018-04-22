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

            // TODO: if member open the email and click on the link e.g: /living/[token]
            // If user the token is valid then update send_email_after = 15, last_email_seen = time()

            // TODO: if send_email_after = 0
            // Final action after 7 days

            $data = [
                'id' => $living->id,
                'last_email_sent' => $living->last_email_sent,
                'send_email_after' => $living->send_email_after,
                'last_email_seen' => $living->last_email_seen,
                'token_url' => asset('living/' . $living->token),
                'user' => [
                    'id' => $living->user->id,
                    'name' => $living->user->name,
                    'email' => $living->user->email,
                ],
            ];

            switch ($living->send_email_after) {
                case 15:
                    $this->checkUserAvailablity($living, $data, 7);
                    break;
                case 7:
                    $this->checkUserAvailablity($living, $data, 3);
                    break;
                case 3:
                    $this->checkUserAvailablity($living, $data, 1);
                    break;
                case 1:
                    $this->checkUserAvailablity($living, $data, 0);
                    break;
                case 0:
                    // TODO: Final action
                    break;
            }
        }
    }

    function checkUserAvailablity($living, $data, $send_email_after)
    {
        $dayDiff = round((time() - $living->last_email_sent)/(60 * 60 * 24));

        if ($dayDiff >= $living->send_email_after) {

            // Send email
            Mail::to($living->user->email)->send(new SendMailable($data));

            // Update column
            $live = Living::findOrFail($living->id);
            $live->send_email_after = $send_email_after;
            $live->last_email_sent = time();
            $live->token = str_random(50);
            $live->save();
        }
    }
}
