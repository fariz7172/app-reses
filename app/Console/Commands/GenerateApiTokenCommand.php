<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateApiTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:generate-token {user_id} {--name=default-token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Sanctum API token for a specific user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $tokenName = $this->option('name');

        $user = \App\Models\User::find($userId);

        if (!$user) {
            $this->error("User dengan ID {$userId} tidak ditemukan!");
            return 1;
        }

        $token = $user->createToken($tokenName);

        $this->info("Berhasil membuat token untuk user: {$user->name} ({$user->role})");
        $this->info("Token Anda (SIMPAN BAIK-BAIK, HANYA MUNCUL SEKALI):");
        $this->line($token->plainTextToken);

        return 0;
    }
}
