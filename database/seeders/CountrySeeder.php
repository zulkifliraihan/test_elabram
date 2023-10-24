<?php

namespace Database\Seeders;

use DB;
use GuzzleHttp\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = new Client();
        $response = $client->get('https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/sql/countries.sql');

        $data = $response->getBody()->getContents();

        DB::unprepared($data);
    }
}
