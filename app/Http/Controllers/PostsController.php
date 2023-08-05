<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create','store','edit','update','destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // DB::connection()->enableQueryLog();
        // $posts = BlogPost::with('comments')->get();

        // foreach($posts as $post){
        //     foreach($post->comments as $comment){
        //         echo $comment->content;
        //     }
        // }

        // dd(DB::getQueryLog());

        $mostCommented = Cache::tags(['blog-post'])->remember('mostCommented', now()->addSecond(10), function() {
            return BlogPost::MostCommented()->take(5)->get();
        });

        $mostActive = Cache::tags(['blog-post'])->remember('mostActive', now()->addSecond(10), function() {
            return User::withMostBlogPosts()->take(5)->get();
        });
        
        $mostActiveLastMonth = Cache::tags(['blog-post'])->remember('mostActiveLastMonth', now()->addSecond(10), function() {
            return User::withMostBlogPostsLastMonth()->take(5)->get();
        });

        return view('posts.index', [
            'posts' => BlogPost::Latest()->withCount('comments')->with('user')->get(),
            'most_commented' => $mostCommented,
            'mostActive' => $mostActive,
            'mostActiveLastMonth' => $mostActiveLastMonth
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        // $request->validate([
        //     'title' => 'bail|required',
        //     'content' => 'required|min:14'
        // ]);

        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $post = BlogPost::create($validated);
        // $post = new BlogPost();
        // $post->title = $validated['title'];
        // $post->content = $validated['content'];
        // $post->save();

        session()->flash('status', 'Blog post was created!');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // abort_if(!isset(BlogPost::find($id)), 404);

        // return view('posts.show', ['post' => BlogPost::with(['comments' => function($query) {
        //     return $query->latest();
        // }])->findOrFail($id)]);

        $blogpost = Cache::tags(['blog-post'])->remember("blog-post-{$id}", 60, function() use($id) {
            return BlogPost::with('comments')->findOrFail($id);
        });

        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $usersKey = "blog-post-{$id}-users";

        $users = Cache::tags(['blog-post'])->get($usersKey, []);
        $usersUpdate = [];
        $diffrence = 0;
        $now = now();

        foreach ($users as $session => $lastVisit) {
            if($now->diffInMinutes($lastVisit) >= 1) {
                $diffrence--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if (
            !array_key_exists($sessionId, $users) 
            || $now->diffInMinutes($users[$sessionId]) >=1
        ) {
            $diffrence++;
        }

        $usersUpdate[$sessionId] = $now;
        Cache::tags(['blog-post'])->put($usersKey, $usersUpdate);

        if(!Cache::tags(['blog-post'])->has($counterKey)) {
            Cache::tags(['blog-post'])->put($counterKey, 1);
        } else {
            Cache::tags(['blog-post'])->increment($counterKey, $diffrence);
        }

        $counter = Cache::tags(['blog-post'])->get($counterKey);

        return view('posts.show', [
            'post' => $blogpost,
            'counter' => $counter
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        // if(Gate::denies('update-post', $post)) {
        //     abort(403, "You can't edit this blog post!");
        // }
        $this->authorize($post);
        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        // if(Gate::denies('update-post', $post)) {
        //     abort(403, "You can't edit this blog post!");
        // }
        // Gate::forUser($user)->denies('update-post', $post);
        // Gate::forUser($user)->allows('update-post', $post);

        $this->authorize($post);

        $validated = $request->validated();
        $post->fill($validated);
        $post->save();

        session()->flash('status', 'Blog post was updated!');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);

        // if(Gate::denies('update-post', $post)) {
        //     abort(403, "You can't delete this blog post!");
        // }
        $this->authorize($post);

        $post->delete();

        // BlogPost::destroy($id);
        session()->flash('status', 'Blog post was deleted!');

        return redirect()->back();
    }
}
