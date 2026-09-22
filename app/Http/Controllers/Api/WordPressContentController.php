<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WordPressClient;

class WordPressContentController extends Controller
{
    public function articles(WordPressClient $wordpress)
    {
        return collect($wordpress->posts())->map(fn ($post) => $this->transform($post));
    }

    public function article(string $slug, WordPressClient $wordpress)
    {
        $post = $wordpress->postBySlug($slug);

        abort_if(! $post, 404);

        return $this->transform($post, includeContent: true);
    }

    private function transform(array $post, bool $includeContent = false): array
    {
        $image = data_get($post, '_embedded.wp:featuredmedia.0.source_url');

        return [
            'id' => $post['id'] ?? null,
            'slug' => $post['slug'] ?? null,
            'title' => html_entity_decode(strip_tags(data_get($post, 'title.rendered', ''))),
            'excerpt' => html_entity_decode(strip_tags(data_get($post, 'excerpt.rendered', ''))),
            'content' => $includeContent ? data_get($post, 'content.rendered') : null,
            'date' => $post['date'] ?? null,
            'image' => $image,
        ];
    }
}
