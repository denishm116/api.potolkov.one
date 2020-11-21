<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class createUser extends Command
{

    protected $signature = 'user:create {name} {email} {password} {role}';

    protected $description = 'Create user';

    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $role = $this->argument('role');
        $password = $this->argument('password');

        if ($user = User::where('email', $email)->first()) {
            $this->error('User with '. $email . ' is already exists');
            return false;
        }

        if (!array_key_exists($role, User::rolesList())) {
            $this->error('Unacceptable role');
            return false;
        }

        try {
            User::create([
                'name' => $name,
                'email' => $email,
                'role' => $role,
                'password' => Hash::make($password),
            ]);
        } catch (\DomainException $e) {
            $this->error($e->getMessage());
            return false;
        }

        $this->info('User is successfully created');
        return true;
    }
}
