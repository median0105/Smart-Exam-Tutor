<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TryoutAttempt;
use Illuminate\Contracts\View\View;

class StudentPerformanceController extends Controller
{
    public function index(): View
    {
        return view('admin.performances.index', [
            'attempts' => TryoutAttempt::with(['user', 'tryout.subject'])
                ->where('status', 'submitted')
                ->latest('submitted_at')
                ->paginate(12),
        ]);
    }
}
