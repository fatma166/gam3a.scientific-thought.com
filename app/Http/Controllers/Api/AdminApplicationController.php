<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class AdminApplicationController extends Controller
{
    public function index(Request $request)
    {
        return Application::query()
            ->with(['student', 'certificateTrack', 'choices.program.faculty.university', 'documents'])
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->when($request->query('q'), function ($query, $term) {
                $query->where(function ($inner) use ($term) {
                    $inner->where('full_name', 'ilike', '%'.$term.'%')
                        ->orWhere('passport_number', 'ilike', '%'.$term.'%');
                });
            })
            ->latest()
            ->paginate((int) $request->query('per_page', 20));
    }

    public function show(Application $application)
    {
        return $application->load(['student', 'certificateTrack', 'choices.program.faculty.university', 'documents']);
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

        $application->update([
            'status' => $data['status'],
            'meta' => $meta,
            'submitted_at' => $data['status'] === 'submitted' ? now() : $application->submitted_at,
        ]);

        return $application->fresh(['student', 'documents']);
    }

    public function updateDocumentStatus(Request $request, Application $application, int $document)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending_review,approved,rejected,missing'],
            'review_notes' => ['nullable', 'string'],
        ]);

        $documentModel = $application->documents()->findOrFail($document);
        $documentModel->update($data);

        return $documentModel;
    }
}
