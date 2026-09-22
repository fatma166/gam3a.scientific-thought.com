<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = Application::query()
            ->with(['student', 'certificateTrack'])
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(20);

        return view('admin.applications.index', compact('applications'));
    }

    public function show(Application $application)
    {
        return view('admin.applications.show', [
            'application' => $application->load(['student', 'certificateTrack', 'choices.program.faculty.university', 'documents']),
        ]);
    }

    public function updateStatus(Request $request, Application $application)
    {
        $data = $request->validate([
            'status' => ['required', 'in:draft,submitted,under_review,missing_documents,approved,rejected'],
            'note' => ['nullable', 'string'],
        ]);

        $meta = $application->meta ?? [];
        $meta['admin_notes'][] = [
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
            'by' => $request->user()->id,
            'at' => now()->toISOString(),
        ];

        $application->update(['status' => $data['status'], 'meta' => $meta]);

        return back()->with('success', 'تم تحديث حالة الطلب.');
    }

    public function download(Application $application, int $document)
    {
        $file = $application->documents()->findOrFail($document);
        return \Illuminate\Support\Facades\Storage::disk($file->disk)->download($file->path, $file->original_name);
    }

    public function reviewDocument(Request $request, Application $application, int $document)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending_review,approved,rejected,missing'],
            'review_notes' => ['nullable', 'string', 'max:4000'],
        ]);
        $application->documents()->findOrFail($document)->update($data);
        return back()->with('success', 'تم حفظ مراجعة المستند.');
    }
}
