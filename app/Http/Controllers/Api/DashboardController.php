<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function student(Request $request)
    {
        $applications = $request->user()->applications()->withCount('documents')->latest()->get();

        return [
            'applications' => $applications,
            'active_application' => $applications->first(),
            'progress' => $this->progress($applications->first()),
        ];
    }

    public function admin()
    {
        return [
            'students' => User::where('role', 'student')->count(),
            'applications' => Application::count(),
            'pending' => Application::whereIn('status', ['submitted', 'under_review', 'missing_documents'])->count(),
            'completed' => Application::where('status', 'approved')->count(),
            'missing_documents' => Document::where('status', 'missing')->count(),
            'latest_applications' => Application::with('student')->latest()->limit(10)->get(),
        ];
    }

    private function progress(?Application $application): int
    {
        if (! $application) {
            return 0;
        }

        return match ($application->status) {
            'draft' => 25,
            'submitted' => 50,
            'under_review', 'missing_documents' => 75,
            'approved', 'rejected' => 100,
            default => 10,
        };
    }
}
