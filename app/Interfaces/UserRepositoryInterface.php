<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function getAll(Request $request);

    public function getById(int|string $userId);

    public function delete(User $user);

    public function create(array $insertData);

    public function update(User $user, array $updateData);

    public function updatePassword(User $user, string $newPassword);
}
