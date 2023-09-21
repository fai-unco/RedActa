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
            ],
            [
                'id' => 2,
                'description' => 'Declaración',
            ],
            [
                'id' => 3,
                'description' => 'Disposición',
            ],
            [
                'id' => 4,
                'description' => 'Acta',
            ],
            [
                'id' => 5,
                'description' => 'Memo',
            ],
            [
                'id' => 6,
                'description' => 'Nota',
            ],
        ]);
    }
}
