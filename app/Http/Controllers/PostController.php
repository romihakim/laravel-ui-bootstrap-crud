<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->paginate(5);

        return view('post.index', compact('posts'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'mimes:png,jpg,jpeg'
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());
        }

        $req = $request->all();
        $req['slug'] = Post::unique_slug(Str::of($req['slug'] ?? $req['title'])->slug('-'));
        $req['excerpt'] = $req['excerpt'] ?? '';
        $req['content'] = $req['content'] ?? '';

        if (isset($image)) {
            $req['image'] = $image->hashName();
        }

        if (!isset($req['published'])) {
            $req['published'] = 0;
        }

        Post::create($req);

        return redirect()->route('post.index')->with('success', 'Post created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('post.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'mimes:png,jpg,jpeg'
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());
        }

        $req = $request->all();
        $req['slug'] = Post::unique_slug(Str::of($req['slug'] ?? $req['title'])->slug('-'), $post['id']);
        $req['excerpt'] = $req['excerpt'] ?? '';
        $req['content'] = $req['content'] ?? '';

        if (isset($image)) {
            $req['image'] = $image->hashName();
        }

        if (!isset($req['published'])) {
            $req['published'] = 0;
        }

        $post->update($req);

        return redirect()->route('post.index')->with('success', 'Post updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('post.index')->with('success', 'Post deleted');
    }
}
