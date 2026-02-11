<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bookshelf;
use App\Models\Row;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\Visit;
use App\Models\Report;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Bookshelf::factory(5)->create();
        Row::factory(10)->create();
        Book::factory(20)->create();
        Transaction::factory(20)->create();
        Visit::factory(20)->create();
        Report::factory(20)->create();
    }
}
