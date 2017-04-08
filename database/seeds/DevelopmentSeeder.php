<?php

use App\Car;
use App\Invoice;
use App\User;
use Illuminate\Database\Seeder;
use Jenssegers\Date\Date;

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
        $faker = Faker\Factory::create();

        // Generate admin user
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@almobile.de',
            'password' => Hash::make('admin')
        ]);

        // Generate unselled cars
        $unselledCars = factory(Car::class, 10)->create([
            'user_id' => $user->id
        ])->each(function (Car $car) use ($user) {
            $car->invoices()->create([
                'title' => 'Einkaufsbeleg',
                'price' => -$car->purchase_price,
                'date' => $car->purchase_date,
                'description' => 'Einkaufsbeleg für ' . $car->title . ' mit FG ' . $car->chassis_number,
                'purchase_invoice' => 1,
                'user_id' => $user->id
            ]);

            // Generate invoices for car
            factory(\App\Invoice::class, 20)->create([
                'car_id' => $car->id,
                'user_id' => $user->id
            ]);
        });

        $selledCars = factory(Car::class, 10)->create([
            'user_id' => $user->id
        ])->each(function (Car $car) use ($user, $faker) {
            $car->fill([
                'sale_date' => (new Date($car->purchase_date->format('Y-m-d')))->addMonth($faker->numberBetween(1, 5)),
                'sale_price' => $car->purchase_price + $faker->numberBetween(1000, 20000)
            ])->save();

            $car->invoices()->create([
                'title' => 'Verkaufsbeleg',
                'price' => -$car->sale_price,
                'date' => $car->sale_date,
                'description' => 'Einkaufsbeleg für ' . $car->title . ' mit FG ' . $car->chassis_number,
                'sale_invoice' => 1,
                'user_id' => $user->id
            ]);

            // Generate invoices for car
            factory(\App\Invoice::class, 20)->create([
                'car_id' => $car->id,
                'user_id' => $user->id
            ]);
        });

        $invoices = factory(\App\Invoice::class, 100)->create([
            'user_id' => $user->id,
            'price' =>  0.0
        ])->each(function (Invoice $invoice) {
            $invoice->fill(['price' => random_int(-1000, 1000)])->save();
        });
    }
}
