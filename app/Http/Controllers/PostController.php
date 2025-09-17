<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(?Topic $topic = null)
    {
        $posts = Post::with(['user', 'topic'])
            ->when($topic, fn (Builder $query) => $query->whereBelongsTo($topic))
            ->latest('id')
            ->paginate();

        return Inertia::render(
            'Posts/Index',
            [
                'posts' => PostResource::collection($posts),
                'selectedTopic' => fn () => $topic ? $topic : null
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Post::class);;
        return Inertia::render('Posts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'body' => ['required', 'string', 'max:3000'],
        ]);

        $post = Post::create([
            ...$data,
            'user_id' => $request->user()->id
        ]);

        return redirect($post->showRoute());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post, $slug = null)
    {
        // Redirect to correct slug if needed (301 permanent redirect for SEO)
        $correctSlug = Str::slug($post->title);

        if ($slug !== $correctSlug) {
            return redirect($post->showRoute($request->query()), status: 301);
        }

        $post->load('user');

        return inertia('Posts/Show', [
            'post' => fn() => PostResource::make($post),
            'comments' => fn() => CommentResource::collection(
                $post->comments()->with('user')->latest()->latest('id')->paginate(10)
            ),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
