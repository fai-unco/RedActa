<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('document_types')->insert([
            [
                'id' => 1,
                'description' => 'Resolución',
                'view' => 'res-dec-disp',
                'action_in_operative_section' => 'resuelve'
            ],
            [
                'id' => 2,
                'description' => 'Declaración',
                'view' => 'res-dec-disp',
                'action_in_operative_section' => 'declara'
            ],
            [
                'id' => 3,
                'description' => 'Disposición',
                'view' => 'res-dec-disp',
                'action_in_operative_section' => 'dispone'
            ],
            [
                'id' => 4,
                'description' => 'Acta',
                'view' => 'acta',
                'action_in_operative_section' => ''
            ],
            [
                'id' => 5,
                'description' => 'Memo',
                'view' => 'memo',
                'action_in_operative_section' => ''
            ],
            [
                'id' => 6,
                'description' => 'Nota',
                'view' => 'nota',
                'action_in_operative_section' => ''
            ],
        ]);
    }
}
