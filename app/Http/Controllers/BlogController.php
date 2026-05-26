<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function categories()
    {
        $categories = BlogCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('blog.categories', compact('categories'));
    }

    public function category(BlogCategory $category)
    {
        abort_unless($category->is_active, 404);

        $posts = BlogPost::query()
            ->where('category_id', $category->id)
            ->published()
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('blog.category', compact('category', 'posts'));
    }

    public function show(BlogPost $post)
    {
        // فقط پست‌های پابلیش
        if (!($post->status === 'published' && $post->published_at && $post->published_at <= now())) {
            abort(404);
        }

        // افزایش ویو (خیلی ساده)
        $post->increment('views');

        return view('blog.show', compact('post'));
    }
}
