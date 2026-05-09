<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\QuestionBank\QuestionBankSyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class QuestionSyncController extends Controller
{
    public function __invoke(Request $request, QuestionBankSyncService $syncService): RedirectResponse
    {
        $data = $request->validate([
            'max_pages' => ['nullable', 'integer', 'min:1', 'max:50'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        try {
            $result = $syncService->sync(
                maxPages: (int) ($data['max_pages'] ?? 1),
                perPage: (int) ($data['per_page'] ?? 50),
            );
        } catch (Throwable $e) {
            return back()->with('status', 'Sinkronisasi gagal: '.$e->getMessage());
        }

        return back()->with(
            'status',
            "Sinkronisasi selesai. Diproses {$result['processed']}, baru {$result['created']}, diperbarui {$result['updated']}, dilewati {$result['skipped']}."
        );
    }
}
