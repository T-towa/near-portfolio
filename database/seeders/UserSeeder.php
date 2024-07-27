<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $seedDatas = [
                [
                    'username' => 'test',
                    'email' => 'test@test',
                    // 'password' => Hash::make('testtest')
                    'password' => 'testtest',
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime(),
                ],
                [
                    'username' => 'test2',
                    'email' => 'test2@test',
                    'password' => 'testtest',
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime(),
                ]
            ];
        foreach ($seedDatas as $data) {
            DB::table('users')->insert($data);
        }
    }
}
