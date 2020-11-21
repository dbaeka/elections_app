<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Str;

class SetupDevEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets up the development environment';

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
     * @return int
     */
    public function handle()
    {
        $this->info('Setting up development environment');
        $this->MigrateAndSeedDatabase();
        $user = $this->CreateJohnDoeUser();
        $this->CreatePersonalAccessToken($user);
        $this->info('All done. Bye!');
        return 0;
    }

    public function MigrateAndSeedDatabase()
    {
        $this->call('migrate:fresh');
        $this->call('db:seed');
    }

    public function CreateJohnDoeUser()
    {
        $this->info('Creating John Doe user');
        $user = User::create(
            [
                'name' => "John Doe",
                'email' => "johndoe@gmail.com",
                'email_verified_at' => now(),
                'phone' => '0503695535',
                'password' => bcrypt('secret'), // password
                'remember_token' => Str::random(10),
            ]
        );
        $this->info($user->name . ' created');
        $this->warn('Phone: ' . $user->phone);
        $this->warn('Password: ' . $user->password);
        return $user;
    }

    public function CreatePersonalAccessToken($user)
    {
        $token = $user->createToken($user->phone)->plainTextToken;
        $this->info('Personal access token created successfully.');
        $this->warn("Personal access token:");
        $this->line($token);
    }
}
