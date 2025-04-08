<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormFieldTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('form_field_types')->insert([
            ['type' => 'Number'],
            ['type' => 'Text'],
            ['type' => 'Date'],
        ]);
    }
}
