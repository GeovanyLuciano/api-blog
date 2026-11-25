<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\PostStoreRequest;
use App\Http\Requests\V1\PostUpdateRequest;
use App\Http\Resources\V1\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Post::with(["user", "categoria"])->where("user_id", $request->user()->id)->latest()->paginate(10);
        $this->authorize("viewAny", Post::class);
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {
        $this->authorize("create", Post::class);

        $post = new Post();
        $post->name = $request["name"];
        $post->description = $request["description"];
        $post->image = $request->file("image")->store("uploads", "public");
        $post->categoria_id = $request["categoria_id"];
        $post->user_id = $request->user()->id;
        $post->save();

        return new PostResource($post->load(["user", "categoria"]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $post = Post::with(["user", "categoria"])->where("user_id", "=", $request->user()->id)->findOrFail($id);
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, string $id)
    {
        $post = Post::where("user_id", $request->user()->id)->findOrFail($id);
        $this->authorize("update", $post);
        $validated = $request->validated();
        $oldImage = $post->image;
        $newImage = null;

        if ($request->hasFile("image")) {
            $newImage = $request->file("image")->store("uploads", "public");
            if ($newImage === false) {
                throw new \RuntimeException("The post image could not be stored.");
            }
        }

        foreach (["name", "description", "categoria_id"] as $field) {
            if (array_key_exists($field, $validated)) {
                $post->{$field} = $validated[$field];
            }
        }
        if ($newImage !== null) {
            $post->image = $newImage;
        }

        $post->save();

        if ($newImage !== null) {
            Storage::disk("public")->delete($oldImage);
        }

        return new PostResource($post->load(["user", "categoria"]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $post = Post::findOrFail($id);
        Storage::disk("public")->delete($post->image);
        $post->delete();
        return response()->noContent();
    }
}
