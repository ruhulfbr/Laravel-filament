<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * The default limit for pagination.
     *
     * @var int
     */
    private int $listLimit = 10;

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get all users with optional filters and pagination.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $query = User::query();

        if ($request->filled(['start_date', 'end_date'])) {
            $startDate = date('Y-m-d 00:00:00', strtotime($request->input('start_date')));
            $endDate = date('Y-m-d 23:59:59', strtotime($request->input('end_date')));

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('is_admin')) {
            $query->where('is_admin', $request->input('is_admin'));
        }

        $limit = $request->input('limit', $this->listLimit);

        return $query->orderByDesc('id')->paginate($limit);
    }

    /**
     * Get a user by their ID.
     *
     * @param int|string $userId
     * @return User
     */
    public function getById(int|string $userId): User
    {
        return User::findOrFail($userId);
    }

    /**
     * Create a new user.
     *
     * @param array $insertData
     * @return User
     */
    public function create(array $insertData): User
    {
        return User::create($insertData);
    }

    /**
     * Update a user's information.
     *
     * @param User $user
     * @param array $updateData
     * @return User
     */
    public function update(User $user, array $updateData): User
    {
        $user->update($updateData);

        return $user;
    }

    /**
     * Update a user's password.
     *
     * @param User $user
     * @param string $newPassword
     * @return User
     */
    public function updatePassword(User $user, string $newPassword): User
    {
        $user->update(['password' => Hash::make($newPassword)]);

        return $user;
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
