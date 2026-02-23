<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogListResource extends JsonResource
{
    /**
     * Transform the resource into an array (preview list item for blog-posts).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $author = $this->author;

        return resource_urls_to_paths([
            'title' => $this->title,
            'slug' => $this->slug,
            'url' => '/api/blog/'.$this->slug,
            'image' => get_image($this->image, asset('front/images/blog.png')),
            'short_body' => \Illuminate\Support\Str::limit(strip_tags($this->short_body ?? ''), 160),
            'date' => $this->created_at?->format('M j, Y'),
            'date_attr' => $this->created_at?->format('Y-m-d'),
            'category' => $this->blog_category?->name ?? 'Blog',
            'category_slug' => $this->blog_category?->slug ?? null,
            'author_name' => $author ? ($author->full_name ?? $author->name ?? 'Author') : 'Author',
            'author_avatar' => $author ? get_image($author->avatar, 'https://ui-avatars.com/api/?name=' . urlencode($author->name ?? 'Author') . '&size=80') : 'https://ui-avatars.com/api/?name=Author&size=80',
        ]);
    }
}
