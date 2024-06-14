<?php

namespace App\Interfaces;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Http\Request;

interface PostRepositoryInterface
{
    public function getAll(Request $request);

    public function getById(int|string $postId): Post;

    public function delete(Post $post);

    public function create(array $insertData);

    public function update(Post $post, array $updateData);

    public function updateStatus(Post $post, PostStatus $status);
}
