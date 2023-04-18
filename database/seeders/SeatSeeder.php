<?php

namespace Database\Seeders;

use App\Models\Column;
use App\Models\Row;
use App\Models\Seat;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = Row::all();
        $columns = Column::all();

        foreach ($rows as $row) {
            foreach ($columns as $col) {
                Seat::create([
                    'name' => $col->name.$row->name,
                    'column_id' => $col->id,
                    'row_id' => $row->id
                ]);
            }
        }
    }
}
