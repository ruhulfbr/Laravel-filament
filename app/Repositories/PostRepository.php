<?php

namespace App\Repositories;

use App\Enums\PostStatus;
use App\Events\PostPublishedEvent;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostRepository implements PostRepositoryInterface
{

    private int $listLimit = 10;
    private string $orderColumn = 'id';
    private string $orderDir = 'desc';

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get all posts with optional filters and pagination.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $query = Post::query();

        if ($request->filled(['start_date', 'end_date'])) {
            $startDate = date('Y-m-d 00:00:00', strtotime($request->input('start_date')));
            $endDate = date('Y-m-d 23:59:59', strtotime($request->input('end_date')));

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('approved_by')) {
            $query->where('approved_by', $request->input('approved_by'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                    ->orWhere('body', 'like', $searchTerm);
            });
        }

        $user = Auth::user();
        if ($user->isAdmin() && $request->filled('with_trashed')) {
            $query->withTrashed();
        } else {
            if ($request->isNotFilled('my_post')) {
                $query->where('status', '!=', PostStatus::DECLINED);
            }
            if ($request->filled('my_post')) {
                $query->where('user_id', $user->id);
            }
        }

        $limit = $request->input('limit', $this->listLimit);
        $orderCol = $request->input('order_col', $this->orderColumn);
        $orderDir = $request->input('order_dir', $this->orderDir);

        return $query->orderBy($orderCol, $orderDir)->paginate($limit);
    }

    /**
     * Get a Post by their ID.
     *
     * @param int|string $postId
     * @return Post
     */
    public function getById(int|string $postId): Post
    {
        return Post::findOrFail($postId)->load(['tags:id,tag']);
    }

    /**
     * Create a new Post.
     *
     * @param array $insertData
     * @return Post
     */
    public function create(array $insertData): Post
    {
        $insertData['slug'] = Str::of($insertData['title']);

        $post = Auth::user()->posts()->save(new Post($insertData));
        $post->tags()->attach($insertData['tags']);

        return $post;
    }

    /**
     * Update a post's information.
     *
     * @param Post $post
     * @param array $updateData
     * @return Post
     */
    public function update(Post $post, array $updateData): Post
    {
        $post->update($updateData);

        if (isset($updateData['tags'])) {
            $post->tags()->sync($updateData['tags']);
        }

        return $post;
    }

    /**
     * Update a post's information.
     *
     * @param Post $post
     * @param PostStatus $status
     * @return Post
     */
    public function updateStatus(Post $post, PostStatus $status): Post
    {
        $updateData = ['status' => $status];

        if ($status === PostStatus::PUBLISHED) {
            $updateData['approved_by'] = Auth::id();
            $updateData['approved_at'] = now();
        }

        $post->update($updateData);

        if ($post->status === PostStatus::PUBLISHED) {
            event(new PostPublishedEvent($post));
        }

        return $post;
    }

    /**
     * Delete a Post.
     *
     * @param Post $post
     * @return bool
     */
    public function delete(Post $post): bool
    {
        return $post->delete();
    }
}
