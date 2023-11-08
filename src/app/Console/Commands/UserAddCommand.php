<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Http\Requests\UserCreateRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
     *
     * @throws ValidationException
     */
    public function handle(): void
    {
        $validator = Validator::make($this->arguments(), UserCreateRequest::rules());

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field => $error) {
                $this->error($field . ": " . $error[0]);
            }

            return;
        }

        $arguments = $validator->validated();

        /** @var User $user */
        $user = User::create([
            'name' => $arguments['name'],
            'email' => $arguments['email'],
            'password' => $arguments['password'],
        ]);

        if ($user) {
            $user->settings()->create();

            $this->info("User created, id: $user->id");
        } else {
            $this->error('There was a problem creating the user');
        }
    }
}
