<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request, Application $application)
    {
        abort_unless($application->student_id === $request->user()->id, 403);
        abort_if(in_array($application->status, ['approved', 'rejected'], true), 422, 'الطلب مغلق.');
        $settings = \App\Models\SiteContent::where('slug', 'site-settings')->where('is_active', true)->first();
        $service = \App\Models\SiteContent::find($application->meta['service_id'] ?? null);
        $allowedTypes = array_unique(array_merge($settings?->content['required_documents'] ?? [], $service?->content['required_documents'] ?? []));

        $data = $request->validate([
            'type' => ['required', 'string', 'max:80', \Illuminate\Validation\Rule::in($allowedTypes)],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $file = $data['file'];
        $path = $file->store("applications/{$application->id}", config('filesystems.default'));
        abort_unless($path, 503, 'تعذر حفظ المستند.');

        $document = $application->documents()->create([
            'type' => $data['type'],
            'original_name' => $file->getClientOriginalName(),
            'disk' => config('filesystems.default'),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return response()->json([
            'document' => $document,
            'temporary_url' => $document->disk === 's3'
                ? Storage::disk($document->disk)->temporaryUrl($document->path, now()->addMinutes(10))
                : null,
        ], 201);
    }
}
