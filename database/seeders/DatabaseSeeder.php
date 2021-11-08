<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Aziz Soliyev',
            'email' => 'strelok@mail.com',
            'city' => '1',
            'password' => Hash::make('strelok'),
            'role' => 1
        ]);

        DB::table('shoptype')->insert([
            [
                'name' => 'Fast food',
                'name_ru' => 'Фастфуд',
                'image' => '/cafetypes/fastfood.png',
            ],
            [
                'name' => 'Desert',
                'name_ru' => 'Десерт',
                'image' => '/cafetypes/desert.png',
            ],
            [
                'name' => 'Barbecue',
                'name_ru' => 'Барбекю',
                'image' => '/cafetypes/barbeque.png',
            ],
            [
                'name' => 'Milliy',
                'name_ru' => 'Национальная',
                'image' => '/cafetypes/national.png',
            ],
        ]);
    }
}
