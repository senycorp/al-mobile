<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        // Run development seeder in local environment
        if (App::getInstance()->environment('local')) {
            $this->call(DevelopmentSeeder::class);
        }
    }
}
