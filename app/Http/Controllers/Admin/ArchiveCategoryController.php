<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArchiveCategory;
use App\Models\Arsip;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArchiveCategoryController extends Controller
{
    public function index()
    {
        $categories = ArchiveCategory::withCount('archives')->orderBy('name')->get();

        return view('admin.kategori', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:archive_categories,name'],
            'classification_code' => ['nullable', 'string', 'max:50'],
        ]);

        $category = ArchiveCategory::create($validated);
        ActivityLogger::record('Tambah Kategori Arsip', 'Menambahkan kategori: ' . $category->name, $category);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, ArchiveCategory $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('archive_categories', 'name')->ignore($category->id)],
            'classification_code' => ['nullable', 'string', 'max:50'],
        ]);

        $previousName = $category->name;
        $category->update($validated);
        Arsip::where('kategori', $previousName)->update(['kategori' => $category->name]);
        ActivityLogger::record('Ubah Kategori Arsip', 'Memperbarui kategori: ' . $previousName . ' menjadi ' . $category->name, $category);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function toggle(ArchiveCategory $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();
        ActivityLogger::record($category->is_active ? 'Aktifkan Kategori Arsip' : 'Nonaktifkan Kategori Arsip', 'Memperbarui kategori: ' . $category->name, $category);

        return back()->with('success', 'Status kategori berhasil diperbarui.');
    }
}
