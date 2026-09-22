<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CalculatorRule;
use App\Models\EquivalencyCenter;
use App\Models\Program;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\QueryException;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'students' => 0,
            'applications' => 0,
            'pending' => 0,
            'universities' => 0,
            'programs' => 0,
            'calculator_rules' => 0,
            'equivalency_centers' => 0,
        ];
        $latestApplications = collect();

        try {
            $stats = [
                'students' => User::where('role', 'student')->count(),
                'applications' => Application::count(),
                'pending' => Application::whereIn('status', ['submitted', 'under_review', 'missing_documents'])->count(),
                'universities' => University::count(),
                'programs' => Program::count(),
                'calculator_rules' => CalculatorRule::count(),
                'equivalency_centers' => EquivalencyCenter::count(),
            ];
            $latestApplications = Application::with('student')->latest()->limit(8)->get();
        } catch (QueryException) {
            session()->flash('warning', 'PostgreSQL غير متصل الآن. اللوحة جاهزة، لكن البيانات الحقيقية ستظهر بعد تشغيل قاعدة البيانات وتنفيذ migrations.');
        }

        return view('admin.dashboard', compact('stats', 'latestApplications'));
    }
}
