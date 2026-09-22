<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WordPressClient
{
    public function posts(array $query = []): array
    {
        $baseUrl = rtrim((string) config('services.wordpress.base_url'), '/');

        if ($baseUrl === '') {
            return [];
        }

        $response = Http::timeout((int) config('services.wordpress.timeout', 8))
            ->get($baseUrl.'/wp-json/wp/v2/posts', array_merge([
                '_embed' => 1,
                'per_page' => 12,
            ], $query));

        return $response->successful() ? $response->json() : [];
    }

    public function postBySlug(string $slug): ?array
    {
        $posts = $this->posts(['slug' => $slug, 'per_page' => 1]);

        return $posts[0] ?? null;
    }
}
