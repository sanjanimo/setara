<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveNeedCategoryRequest;
use App\Models\ActivityLog;
use App\Models\NeedCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = NeedCategory::withCount('pantiNeeds')
            ->when($request->filled('search'), fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.admin.categories.create');
    }

    public function store(SaveNeedCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['is_active'] = true;

        $category = NeedCategory::create($data);

        ActivityLog::record('category.created', "Admin menambahkan kategori {$category->name}.", $category);

        return redirect()->action([self::class, 'index'])->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(NeedCategory $category)
    {
        return view('dashboard.admin.categories.edit', compact('category'));
    }

    public function update(NeedCategory $category, SaveNeedCategoryRequest $request)
    {
        $category->update($request->validated());

        ActivityLog::record('category.updated', "Admin memperbarui kategori {$category->name}.", $category);

        return redirect()->action([self::class, 'index'])->with('success', 'Kategori berhasil diperbarui.');
    }

    public function toggle(NeedCategory $category)
    {
        $category->update(['is_active' => ! $category->is_active]);

        ActivityLog::record('category.toggled', 'Admin ' . ($category->is_active ? 'mengaktifkan' : 'menonaktifkan') . " kategori {$category->name}.", $category);

        return back()->with('success', "Status kategori {$category->name} diperbarui.");
    }

    protected function uniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $suffix = 1;

        while (NeedCategory::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }
}
