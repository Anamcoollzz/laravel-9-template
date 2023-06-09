<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Region::truncate();
        // $sql = file_get_contents(base_path('database/seeders/data/regions.sql'));

        // DB::insert($sql);

        // import sql
        $sql = file_get_contents(base_path('database/seeders/data/regions.sql'));
        DB::unprepared($sql);

        // $sql = str_replace("\n", "", $sql);
        // $sql = explode("),", $sql);
        // $data = [];
        // foreach ($sql as  $value) {
        //     $val = str_replace('(', '', $value);
        //     $val = str_replace("'", '', $val);
        //     $val = str_replace(")", '', $val);
        //     $val = explode(",", $val);
        //     $data[] = [
        //         'code' => $val[0],
        //         'name' => $val[1],
        //     ];
        // }

        // // insert per 500 rows
        // $chunks = array_chunk($data, 1000);
        // foreach ($chunks as $chunk) {
        //     DB::table('regions')->insert($chunk);
        // }
    }
}
