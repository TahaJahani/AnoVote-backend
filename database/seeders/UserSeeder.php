<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = fopen("hashed_info.csv", "r");
        while (true) {
            $data = fgetcsv($file);
            if (!$data)
                break;
            $group = UserGroup::where('year', $data[2])->where('grade', $data[3])->first();
            if (!$group) {
                $group = UserGroup::create([
                    "year" => $data[2],
                    "grade" => $data[3]
                ]);
            }
            if ($data[1] == 'b\'$2b$12$h.qf4bmsqWX7aIQ6E7KbmOm3LEdgjHsSz2WtuczcYKQCnmIZ1.AP6\'') {
                User::create([
                    "password" => $data[1],
                    "group_id" => $group->id,
                    "role" => 'admin'
                ]);
            } else {
                User::create([
                    "password" => $data[1],
                    "group_id" => $group->id
                ]);
            }
        }
    }
}
