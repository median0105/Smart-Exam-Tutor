<?php

namespace App\Console\Commands;

use App\Services\QuestionBank\QuestionBankSyncService;
use Illuminate\Console\Command;
use Throwable;

class SyncExternalQuestionsCommand extends Command
{
    protected $signature = 'questions:sync-external {--max-pages=1} {--per-page=50}';

    protected $description = 'Sinkronisasi bank soal dari API eksternal ke database lokal';

    public function handle(QuestionBankSyncService $syncService): int
    {
        try {
            $result = $syncService->sync(
                maxPages: (int) $this->option('max-pages'),
                perPage: (int) $this->option('per-page'),
            );
        } catch (Throwable $e) {
            $this->error('Sinkronisasi gagal: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info("Diproses: {$result['processed']}");
        $this->info("Baru: {$result['created']}");
        $this->info("Diperbarui: {$result['updated']}");
        $this->info("Dilewati: {$result['skipped']}");

        return self::SUCCESS;
    }
}
