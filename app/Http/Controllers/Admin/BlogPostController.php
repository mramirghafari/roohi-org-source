<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $BlogPosts = BlogPost::all();
       
        return view('dashboard.blogPostHistory', compact('BlogPosts'));
    }

    public function create()
    {
        $categories = BlogCategory::query()->where('is_active', true)->get();
        return view('dashboard.addBlog', compact('categories'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'category_id'  => 'required|exists:blog_categories,id',
        'title'        => 'required|string|max:255',
        'slug'         => 'nullable|string|max:255|unique:blog_posts,slug',
        'excerpt'      => 'nullable|string',
        'content'      => 'required|string',
        'status'       => 'required|in:draft,published',
        'published_at' => 'nullable|date',
        'cover_image'  => 'nullable|file|mimes:png,jpg,jpeg,webp,svg|max:2048',
    ]);

    // ✅ slug: اگر خالی بود از title بساز
    $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

    // ✅ cover_image: بهینه‌سازی مثل سیگنال‌ها
    if ($request->hasFile('cover_image')) {

        $imageFile = $request->file('cover_image');
        $ext = strtolower($imageFile->getClientOriginalExtension());

        // مسیر ذخیره در public/
        $dir = public_path('blog');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // اگر SVG بود، پردازش نکن (همون رو ذخیره کن)
        if ($ext === 'svg') {

            $fileName = (string) Str::uuid() . '.svg';
            $imageFile->move($dir, $fileName);

            // چون توی public ذخیره کردی، مسیر نسبی می‌ذاریم
            $validated['cover_image'] = 'blog/' . $fileName;

        } else {

            $manager = new ImageManager(new Driver());
            $image = $manager->read($imageFile);

            // تغییر سایز با حفظ نسبت
            $image->scaleDown(width: 1200);

            // خروجی بهینه webp
            $fileName = (string) Str::uuid() . '.webp';

            $image->toWebp(75)->save(
                $dir . '/' . $fileName
            );

            $validated['cover_image'] = 'blog/' . $fileName;
        }
    }

        // اگر published انتخاب شد ولی تاریخ نداد => الان
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // views رو معمولاً از فرم نمی‌گیریم
        $validated['views'] = 0;

        BlogPost::create($validated);

        return redirect()->route('blogAdmin')->with('success', 'Post created');
    }

    public function edit(BlogPost $post)
    {
        $categories = BlogCategory::query()->where('is_active', true)->orderBy('title')->get();
        return view('dashboard.editBlog', compact('post', 'categories'));
    }

    public function update(Request $request, BlogPost $post)
{
    $data = $request->validate([
        'category_id'  => ['required', 'exists:blog_categories,id'],
        'title'        => ['required', 'string', 'max:190'],
        'slug'         => ['nullable', 'string', 'max:190', Rule::unique('blog_posts', 'slug')->ignore($post->id)],
        'excerpt'      => ['nullable', 'string', 'max:500'],
        'content'      => ['required', 'string'],

        // ✅ مثل قبل (SVG هم مجاز)
        'cover_image'  => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg', 'max:4096'],

        'status'       => ['required', Rule::in(['draft', 'published', 'archived'])],
        'published_at' => ['nullable', 'date'],
    ]);

    $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

    // ✅ آپلود کاور مثل سیگنال‌ها + حذف قبلی
    if ($request->hasFile('cover_image')) {

        // حذف فایل قبلی (اگر وجود داشت)
        if (!empty($post->cover_image)) {
            $oldPath = public_path($post->cover_image); // چون ما تو public ذخیره می‌کنیم
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $imageFile = $request->file('cover_image');
        $ext = strtolower($imageFile->getClientOriginalExtension());

        $dir = public_path('blog/covers');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // SVG: بدون پردازش
        if ($ext === 'svg') {
            $fileName = (string) Str::uuid() . '.svg';
            $imageFile->move($dir, $fileName);
            $data['cover_image'] = 'blog/covers/' . $fileName;
        } else {
            $manager = new ImageManager(new Driver());
            $image   = $manager->read($imageFile);

            $image->scaleDown(width: 1200);

            $fileName = (string) Str::uuid() . '.webp';
            $image->toWebp(75)->save($dir . '/' . $fileName);

            $data['cover_image'] = 'blog/covers/' . $fileName;
        }
    }

    if ($data['status'] === 'published' && empty($data['published_at'])) {
        $data['published_at'] = now();
    }

    // ✅ فقط اگر واقعاً چیزی فرق کرد ذخیره کن
    $post->fill($data);

    if ($post->isDirty()) {
        $post->save();
    }

        return redirect()->route('blogPosts.edit', $post->id)->with('success', 'مقاله با موفقیت ویرایش شد.');
    }

    public function destroy(BlogPost $post)
    {
        $post->delete();
        return back()->with('success', 'Post deleted');
    }

    
    public function homeBlog() {
        // مقالاتی که منتشر شده‌اند و تاریخ انتشارشان جلوتر از الان نیست
        $posts = BlogPost::published()
            ->whereHas('category', function ($q) {
                $q->where('access', 'free')->where('is_active', true);
            })
            ->with('category')
            ->orderByDesc('published_at')
            ->paginate(9);

        // دسته‌بندی‌هایی که دسترسی‌شان public است
        $publicCategories = BlogCategory::query()
            ->where('access', 'public')
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        return view('blog', compact('posts', 'publicCategories'));
    }

    public function homeSingleBlog($slug) {
            
            $Blog = BlogPost::where('slug', $slug)->first();
            
            // 3 پست مشابه از همان دسته‌بندی (به جز خود این پست)
            $relatedPosts = BlogPost::published()
                ->where('category_id', $Blog->category_id)
                ->where('id', '!=', $Blog->id)
                ->orderByDesc('published_at')
                ->limit(3)
                ->get();
            
            return view('blogSingle', compact('Blog', 'relatedPosts'));
    }


    public function internalBlog() {
        $user = auth()->user();
        $isVip = $user->has_active_vip;

        if($isVip) {
            $posts = BlogPost::published()
                ->orderByDesc('published_at')
                ->paginate(9);

        
        } else {
            $posts = BlogPost::published()
                ->whereHas('category', function ($q) {
                    $q->whereIn('access', ['free', 'register'])
                        ->where('is_active', true);
                })
                ->with('category')
                ->orderByDesc('published_at')
                ->paginate(9);

            // فقط دسته‌بندی‌های free و register
            /* $categories = BlogCategory::query()
                ->whereIn('access', ['free', 'register'])
                ->where('is_active', true)
                ->orderBy('title')
                ->get(); */
        }

        // همه دسته‌بندی‌ها
        $categories = BlogCategory::query()
                ->where('is_active', true)
                ->orderBy('title')
                ->get();

        return view('dashboard.blog', compact('posts', 'categories'));
    }
    
    public function internalBlogCat($slug) {
        $user = auth()->user();

        $Category = BlogCategory::where('slug', $slug)->firstOrFail();

        $access = $Category->access;
        $canView = true;
        $accessMessage = null;

        if ($access === 'vip') {
            if (auth()->check() && auth()->user()->has_active_vip) {
                $posts = BlogPost::published()
                    ->where('category_id', $Category->id)
                    ->with('category')
                    ->orderByDesc('published_at')
                    ->get();
            } else {
                $posts = collect();
                $canView = false;
                $accessMessage = 'محتوای این دسته بندی آموزشی مربوط به کاربران VIP میباشد. لطفا برای مشاهده اشتراک VIP تهیه کنید.';
            }
        } elseif ($access === 'register') {
            if (auth()->check()) {
                $posts = BlogPost::published()
                    ->where('category_id', $Category->id)
                    ->with('category')
                    ->orderByDesc('published_at')
                    ->get();
            } else {
                $posts = collect();
                $canView = false;
                $accessMessage = 'برای مشاهده این دسته باید وارد حساب کاربری شوید.';
            }
        } else {
            // free or other
            $posts = BlogPost::published()
                ->where('category_id', $Category->id)
                ->with('category')
                ->orderByDesc('published_at')
                ->get();
        }

        // همه دسته‌بندی‌ها
        $categories = BlogCategory::query()
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        return view('dashboard.blogCategory', compact('Category', 'posts', 'categories', 'canView', 'accessMessage'));
    }

    public function internalSingleBlog($slug) {

        $Blog = BlogPost::published()
            ->where('slug', $slug)
            ->with('category')
            ->firstOrFail();

        $categoryAccess = $Blog->category->access ?? 'free';

        if ($categoryAccess === 'vip') {
            if (!(auth()->check() && auth()->user()->has_active_vip)) {
                return redirect()->route('internalBlog')->with('error', 'محتوای این مطلب مربوط به کاربران VIP می‌باشد. برای مشاهده اشتراک VIP تهیه کنید.');
            }
        } elseif ($categoryAccess === 'register') {
            if (!auth()->check()) {
                return redirect()->route('internalBlog')->with('error', 'برای مشاهده این مطلب باید وارد حساب کاربری شوید.');
            }
        }

        // همه دسته‌بندی‌ها
        $categories = BlogCategory::query()
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        $relatedPosts = BlogPost::published()
            ->where('category_id', $Blog->category_id)
            ->where('id', '!=', $Blog->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('dashboard.blogSingle', compact('Blog', 'relatedPosts','categories'));

    }
}
