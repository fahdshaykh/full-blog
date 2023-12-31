<?php

namespace App\Http\Controllers;

// use App\Contracts\CounterContract;
use App\Events\BlogPostPosted;
use App\Facades\CounterFacade;
use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\Image;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    // private $counter;
    // conststructor parameter (CounterContract $counter)

    public function __construct()
    {
        $this->middleware('auth')->only(['create','store','edit','update','destroy']);
        // $this->middleware('locale');
        // $this->counter = $counter;    
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

        $posts = BlogPost::latestWithRelations()->get();

        return view('posts.index', [
            'posts' => $posts
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
        $blogPost = BlogPost::create($validated);
        
        if ($request->hasFile('thumbnail')) {
            
            $path = $request->file('thumbnail')->store('thumbnails');
            $blogPost->image()->save(
                Image::make(['path' => $path])
            );

            // dump($file->store('thumbnails'));
            // dump(Storage::disk('public')->putFile('thumbnails', $file));

            // $name1 = $file->storeAs('thumbnails', $blogPost->id .'.'.$file->guessExtension());
            // $name2 = Storage::disk('local')->putFileAs('thumbnails', $file,  $blogPost->id.'.'.$file->guessExtension());

            // dump(Storage::url($name1));
            // dump(Storage::disk('local')->url($name2));
        }

        // $blogPost = new BlogPost();
        // $blogPost->title = $validated['title'];
        // $blogPost->content = $validated['content'];
        // $blogPost->save();

        event(new BlogPostPosted($blogPost));

        session()->flash('status', 'Blog post was created!');

        return redirect()->route('posts.show', ['post' => $blogPost->id]);
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
            return BlogPost::with('comments','tags', 'user', 'comments.user')
            ->findOrFail($id);
        });

        // $counter = resolve(Counter::class);
        
        return view('posts.show', [
            'post' => $blogpost,
            'counter' => CounterFacade::increment("blog-post-{$id}", ['blog-post']),
            // 'counter' => $this->counter->increment("blog-post-{$id}", ['blog-post']),
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

        if ($request->hasFile('thumbnail')) {
            
            $path = $request->file('thumbnail')->store('thumbnails');
            
            if ($post->image) {
                Storage::delete($post->image->path);
                $post->image->path = $path;
                $post->image->save();
            } else {
                $post->image()->save(
                    Image::make(['path' => $path])
                );
            }
        }

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
