<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts_count = (int)$this->command->ask('How many blog posts wish to create?', 200);
        $users = User::all();

        \App\Models\BlogPost::factory($posts_count)->make()->each(function($post) use($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        }); 
    }
}
