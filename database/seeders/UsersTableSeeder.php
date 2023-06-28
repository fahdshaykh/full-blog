<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users_count = max((int)$this->command->ask('How many users wish to create?', 20), 1);
        \App\Models\User::factory()->callMe()->create();
        \App\Models\User::factory($users_count)->create();

        // me is a class while else is collection
        // dd(get_class($me), get_class($else));

        // $users = $else->concat([$me]);
    }
}
