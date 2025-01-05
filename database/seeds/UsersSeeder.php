<?php

use App\Customer;
use App\User;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([[
            "uuid" => Uuid::uuid4()->toString(),
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ], [
            "uuid" => Uuid::uuid4()->toString(),
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ]]);
        Customer::insert([[
            "customer_uuid" => Uuid::uuid4()->toString(),
            "first_name" => 'Team',
            "last_name" => 'Circl',
            "password" => Hash::make('admin@123'),
            "email" => 'admin@circl.co',
            "type" =>'admin',
            "user_id"=>1,
            "is_verified"=>1,
            "is_active"=>1,
            "onboard_count"=>2,
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ], [
            "uuid" => Uuid::uuid4()->toString(),
            "type" => 'guest',
            "first_name" => 'Guest',
            "last_name" => 'Circl',
            "password" => Hash::make('guest@123'),
            "email" => 'guest@circl.com',
            "user_id"=>2,
            "is_verified"=>0,
            "is_active"=>1,
            "onboard_count"=>2,
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ]]);
    }
}
