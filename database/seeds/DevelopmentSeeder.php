<?php

use App\Car;
use App\User;
use Illuminate\Database\Seeder;

/**
 * Class DevelopmentSeeder
 */
class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = new Faker\Generator();

        // Generate admin user
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@almobile.de',
            'password' => Hash::make('admin')
        ]);

        // Generate unselled cars
        $unselledCars = factory(Car::class, 10000)->create([
            'user_id' => $user->id
        ])->each(function (Car $car) use ($user) {
            // Generate invoices for car
            factory(\App\Invoice::class, 10)->create([
                'car_id' => $car->id,
                'user_id' => $user->id
            ]);
        });

        $selledCars = factory(Car::class, 5000)->create([
            'user_id' => $user->id,
        ]);

    }
}
