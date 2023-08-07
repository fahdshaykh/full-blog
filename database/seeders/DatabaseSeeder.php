<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if($this->command->confirm('Do you want to refresh the database?', true)) {
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed');
        }

        Cache::tags(['blog-post'])->flush();

        $this->call([
            UsersTableSeeder::class,
            BlogPostsTableSeeder::class,
            CommentsTableSeeder::class,
            TagsTableSeeder::class,
            BlogPostTagTableSeeder::class
        ]);



        
        // $me = \App\Models\User::factory()->callMe()->create();
        // $else = \App\Models\User::factory(20)->create();

        // // me is a class while else is collection
        // // dd(get_class($me), get_class($else));

        // $users = $else->concat([$me]);

        // $posts = \App\Models\BlogPost::factory(50)->make()->each(function($post) use($users) {
        //     $post->user_id = $users->random()->id;
        //     $post->save();
        // }); 

        // $comments = \App\Models\Comment::factory(150)->make()->each(function($comment) use($posts) {
        //     $comment->blog_post_id = $posts->random()->id;
        //     $comment->save();
        // }); 

        // DB::table('users')->insert([
        //     'name' => 'fahd',
        //     'email' => 'fahdshaykh@gmail.com',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        // ]);


    }
}
