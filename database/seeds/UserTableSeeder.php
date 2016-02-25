<?php

use Starter\Users\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        factory(\Starter\Users\User::class)->create([
            'first_name' => 'Etienne',
            'last_name' => 'Marais',
            'number' => '27848538354',
            'api_key' => 'apikeyuser',
            'status' => 'enabled',
        ]);
    }
}