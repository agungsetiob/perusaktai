<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AuditLogService;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('Admin/Users/Index', [
            'users' => User::query()
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'role', 'is_active']),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $user = User::create($request->validated());

        // Hapus password agar tidak masuk audit log
        $newValues = collect($user->toArray())->except(['password'])->toArray();

        app(AuditLogService::class)->log(
            module: 'User',
            action: 'Create User',
            subject: $user,
            description: "Membuat user {$user->name}",
            newValues: $newValues,
        );

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $oldValues = collect($user->toArray())->except(['password'])->toArray();

        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        $newValues = collect($user->fresh()->toArray())->except(['password'])->toArray();

        app(AuditLogService::class)->log(
            module: 'User',
            action: 'Update User',
            subject: $user,
            description: "Mengubah user {$user->name}",
            oldValues: $oldValues,
            newValues: $newValues,
        );

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $oldValues = collect($user->toArray())->except(['password'])->toArray();

        $user->update([
            'is_active' => false,
        ]);

        $newValues = collect($user->fresh()->toArray())->except(['password'])->toArray();

        app(AuditLogService::class)->log(
            module: 'User',
            action: 'Deactivate User',
            subject: $user,
            description: "Menonaktifkan user {$user->name}",
            oldValues: $oldValues,
            newValues: $newValues,
        );

        return back()->with('success', 'User berhasil dinonaktifkan.');
    }
}