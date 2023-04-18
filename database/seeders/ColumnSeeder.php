<?php

namespace Database\Seeders;

use App\Models\Column;
use Illuminate\Database\Seeder;

class ColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Column::insert([
            ['name' => 'A'],
            ['name' => 'B'],
            ['name' => 'C'],
            ['name' => 'D'],
            ['name' => 'E'],
            ['name' => 'F'],
            ['name' => 'G'],
            ['name' => 'H'],
            ['name' => 'I'],
            ['name' => 'J'],
            ['name' => 'K'],
            ['name' => 'L'],
            ['name' => 'M'],
            ['name' => 'N'],
            ['name' => 'O'],
            ['name' => 'P'],
            ['name' => 'Q'],
            ['name' => 'R'],
            ['name' => 'S'],
            ['name' => 'T']
        ]);
    }
}
