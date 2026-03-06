<?php

namespace App\Console\Commands;

use App\Mail\TestMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailTestCommand extends Command
{
    protected $signature = 'mail:test {email}';
    protected $description = 'Send a test e-mail to the given address';

    public function handle()
    {
        $email = $this->argument('email');

        try {
            Mail::to($email)->send(new TestMail());
            $this->info("Test e-mail sent to {$email}");
        } catch (\Throwable $e) {
            $this->error('Failed to send mail: '.$e->getMessage());
            return 1;
        }
    }
}
