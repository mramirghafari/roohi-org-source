<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::query()
            ->with('parent')
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->paginate(500);

        return view('dashboard.blogCategoriesAdmin', compact('categories'));
    }

    public function create()
    {
        $parents = BlogCategory::query()->whereNull('parent_id')->orderBy('title')->get();
        return view('admin.blog.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parent_id'   => ['nullable', 'exists:blog_categories,id'],
            'title'       => ['required', 'string', 'max:190'],
            'slug'        => ['nullable', 'string', 'max:190', 'unique:blog_categories,slug'],
            'access'      => ['required', Rule::in(['free', 'vip','register'])],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = (bool)($data['is_active'] ?? true);

        BlogCategory::create($data);

        return redirect()->route('blogCategoriesAdmin.index')->with('success', 'Category created');
    }

    public function edit(BlogCategory $category)
    {
        $categories = BlogCategory::query()
            ->with('parent')
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->paginate(500);

        return view('dashboard.blogCategoryEdit', compact('category', 'categories'));
    }

    public function update(Request $request, BlogCategory $category)
    {
        $data = $request->validate([
            'parent_id'   => ['nullable', 'exists:blog_categories,id'],
            'title'       => ['required', 'string', 'max:190'],
            'slug'        => ['nullable', 'string', 'max:190', Rule::unique('blog_categories', 'slug')->ignore($category->id)],
            'access'      => ['required', Rule::in(['free', 'vip','register'])],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = (bool)($data['is_active'] ?? true);

        // جلوگیری از parent شدن خودش
        if (!empty($data['parent_id']) && (int)$data['parent_id'] === (int)$category->id) {
            $data['parent_id'] = null;
        }

        $category->update($data);

        return redirect()->route('blogCategoriesAdmin.index')->with('success', 'ویرایش دسته بندی با موفقیت انجام شد');
    }

    public function destroy(BlogCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted');
    }
}
