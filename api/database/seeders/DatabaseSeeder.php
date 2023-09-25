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
                'actionInOperativeSection' => 'resuelve'
            ],
            [
                'id' => 2,
                'description' => 'Declaración',
                'view' => 'res-dec-disp',
                'actionInOperativeSection' => 'declara'
            ],
            [
                'id' => 3,
                'description' => 'Disposición',
                'view' => 'res-dec-disp',
                'actionInOperativeSection' => 'dispone'
            ],
            [
                'id' => 4,
                'description' => 'Acta',
                'view' => 'acta',
                'actionInOperativeSection' => ''
            ],
            [
                'id' => 5,
                'description' => 'Memo',
                'view' => 'memo',
                'actionInOperativeSection' => ''
            ],
            [
                'id' => 6,
                'description' => 'Nota',
                'view' => 'nota',
                'actionInOperativeSection' => ''
            ],
        ]);
    }
}
