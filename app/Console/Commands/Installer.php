<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class Installer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:installer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the application with default data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('migrate');

        $this->info('Migrations run successfully');

        User::updateOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'admin',
        ]);

        $this->info('Admin user created successfully');

        $this->call('db:seed');

        $this->info('Database seeded successfully');

        $this->call('storage:link');

        $this->info('Storage linked successfully');

        $this->info('Installation completed successfullyyyy');
    }
}
