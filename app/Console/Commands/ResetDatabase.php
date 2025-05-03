<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetDatabase extends Command
{
    protected $signature = 'db:reset';

    protected $description = 'Menjalankan migrate:fresh lalu db:seed';

    public function handle()
    {
        $this->info('Menjalankan migrate:fresh...');
        $this->call('migrate:fresh');

        $this->info('Menjalankan db:seed...');
        $this->call('db:seed');

        $this->info('Database telah di-reset dan diseed ulang!');
        $this->info('');
    }
}
