<?php

namespace App\Console\Commands;

use App\Http\Requests\UserCreateRequest;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class UserAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new user to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $validator = Validator::make($this->arguments(), UserCreateRequest::rules());

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field => $error) {
                $this->error($field . ": " . $error[0]);
            }
            return;
        }

        $arguments = $validator->validated();
        $user = User::create([
            'name' => $arguments['name'],
            'email' => $arguments['email'],
            'password' => $arguments['password'],
        ]);
        $this->info("User created, id: $user->id");
    }
}
