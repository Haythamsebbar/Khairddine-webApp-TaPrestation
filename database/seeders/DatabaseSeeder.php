<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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

        $this->call(RoleSeeder::class);
        $this->call([
            UsersTableSeeder::class,
            NewCategoriesSeeder::class,
            NewEquipmentCategoriesSeeder::class,
            EquipmentTableSeeder::class,
            EquipmentRentalSeeder::class,
            SkillsTableSeeder::class,
            ServicesTableSeeder::class,
            AdditionalServicesTableSeeder::class,
            MoreServicesTableSeeder::class,

            UrgentSalesTableSeeder::class,
            OffersTableSeeder::class,
            NotificationsTableSeeder::class,
            MessagesTableSeeder::class,
            BookingsTableSeeder::class,
        ]);
    }
}
