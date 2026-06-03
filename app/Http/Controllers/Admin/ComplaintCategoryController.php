<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\ComplaintCategory;
use App\Services\AuditLogService;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class ComplaintCategoryController extends Controller
{
    public function index(): Response
    {
        $this->authorize(
            'viewAny',
            ComplaintCategory::class
        );

        return Inertia::render(
            'Admin/Categories/Index',
            [
                'categories' => ComplaintCategory::query()
                    ->orderBy('name')
                    ->get([
                        'id',
                        'name',
                        'is_active',
                    ]),
            ]
        );
    }

    public function store(
        StoreCategoryRequest $request
    ): RedirectResponse {

        $this->authorize(
            'create',
            ComplaintCategory::class
        );

        $category = ComplaintCategory::create(
            $request->validated()
        );

        app(AuditLogService::class)->log(
            module: 'Category',
            action: 'Create Category',
            subject: $category,
            description:
            "Membuat kategori {$category->name}",
            newValues: $category->toArray(),
        );

        return back()->with(
            'success',
            'Kategori berhasil ditambahkan.'
        );
    }

    public function update(
        UpdateCategoryRequest $request,
        ComplaintCategory $category
    ): RedirectResponse {

        $this->authorize(
            'update',
            $category
        );

        $oldValues = $category->toArray();

        $category->update(
            $request->validated()
        );

        app(AuditLogService::class)->log(
            module: 'Category',
            action: 'Update Category',
            subject: $category,
            description:
            "Mengubah kategori {$category->name}",
            oldValues: $oldValues,
            newValues: $category->fresh()->toArray(),
        );

        return back()->with(
            'success',
            'Kategori berhasil diperbarui.'
        );
    }

    public function destroy(
        ComplaintCategory $category
    ): RedirectResponse {

        $this->authorize(
            'delete',
            $category
        );

        $oldValues = $category->toArray();

        $category->update([
            'is_active' => false,
        ]);

        app(AuditLogService::class)->log(
            module: 'Category',
            action: 'Deactivate Category',
            subject: $category,
            description:
            "Menonaktifkan kategori {$category->name}",
            oldValues: $oldValues,
            newValues: $category->fresh()->toArray(),
        );

        return back()->with(
            'success',
            'Kategori berhasil dinonaktifkan.'
        );
    }
}