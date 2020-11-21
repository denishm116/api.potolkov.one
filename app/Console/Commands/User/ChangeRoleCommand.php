<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;

class ChangeRoleCommand extends Command
{

    protected $signature = 'user:role {email} {role}';

    protected $description = 'Change Users role';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        if (!$user = User::where('email', $email)->first()) {
            $this->error('Undefined user with email ' . $email);
            return false;
        }

        try {
            $user->changeRole($role);
        } catch (\DomainException $e) {
            $this->error($e->getMessage());
            return false;
        }

        $this->info('Role is successfully changed');
        return true;

    }
}
