<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array (single blog post).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $author = $this->author;
        $category = $this->blog_category;
        $blogType = $this->blog_type;

        return resource_urls_to_paths([
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_body' => $this->short_body,
            'long_body' => $this->long_body,
            'image' => get_image($this->image, asset('front/images/blog.png')),
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'url' => '/api/blog/'.$this->slug,
            'template' => resolve_menu_template(api_path('blog_post', $this->slug)),
            'date' => $this->created_at?->format('M j, Y'),
            'date_attr' => $this->created_at?->format('Y-m-d'),
            'published_at' => $this->published_at?->toIso8601String(),
            'category' => $category ? [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ] : null,
            'blog_type' => $blogType ? [
                'id' => $blogType->id,
                'name' => $blogType->name,
            ] : null,
            'author' => $author ? [
                'id' => $author->id,
                'name' => $author->full_name ?? $author->name ?? 'Author',
                'avatar' => get_image($author->avatar, 'https://ui-avatars.com/api/?name=' . urlencode($author->name ?? 'Author') . '&size=80'),
            ] : null,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ]);
    }
}
