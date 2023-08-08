<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = BlogPost::all();
        if($posts->count() === 0) {
            $this->command->info('There is no post yet, so there is no comments added');
            return;
        }
        $comments_count = (int)$this->command->ask('How many comments wish to create?', 200);
        $users = User::all();
        \App\Models\Comment::factory($comments_count)->make()->each(function($comment) use($posts, $users) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->user_id = $users->random()->id;
            $comment->save();
        }); 
    }
}
