<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;


class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $seedDatas = [
                [
                    'name' => '映像',
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime(),
                ],
                [
                    'name' => '3DCG',
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime(),
                ]
            ];
        foreach ($seedDatas as $data) {
            DB::table('tags')->insert($data);
        }
    }
}
