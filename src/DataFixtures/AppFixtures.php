<?php

namespace App\DataFixtures;

use App\Entity\Availability;
use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create Vehicles
        $vehicleData = [
            ['brand' => 'Ferrari', 'model' => 'LaFerrari'],
            ['brand' => 'Lamborghini', 'model' => 'Aventador'],
            ['brand' => 'Bugatti', 'model' => 'Chiron'],
            ['brand' => 'Porsche', 'model' => '911 Turbo S'],
            ['brand' => 'McLaren', 'model' => 'P1'],
            ['brand' => 'Aston Martin', 'model' => 'DB11'],
            ['brand' => 'Tesla', 'model' => 'Model 3'],
        ];

        $availabilityData = [
            // Ferrari LaFerrari
            ['start_date' => '2024-05-25', 'end_date' => '2024-06-04', 'price_per_day' => 2500, 'status' => true],
            // Lamborghini Aventador
            ['start_date' => '2024-05-30', 'end_date' => '2024-06-09', 'price_per_day' => 2000, 'status' => true],
            // Bugatti Chiron
            ['start_date' => '2024-06-05', 'end_date' => '2024-06-15', 'price_per_day' => 4000, 'status' => true],
            // Porsche 911 Turbo S
            ['start_date' => '2024-06-10', 'end_date' => '2024-06-20', 'price_per_day' => 1500, 'status' => true],
            // McLaren P1
            ['start_date' => '2024-06-15', 'end_date' => '2024-06-25', 'price_per_day' => 3000, 'status' => true],
            // Aston Martin DB11
            ['start_date' => '2024-06-20', 'end_date' => '2024-06-30', 'price_per_day' => 1800, 'status' => true],
            // Tesla Model 3
            ['start_date' => '2024-05-20', 'end_date' => '2024-05-25', 'price_per_day' => 99.90, 'status' => true],
        ];

        foreach ($vehicleData as $index => $data) {
            $vehicle = new Vehicle();
            $vehicle->setBrand($data['brand']);
            $vehicle->setModel($data['model']);
            $manager->persist($vehicle);

            $availability = new Availability();
            $availability->setVehicle($vehicle);
            $availability->setStartDate(new \DateTime($availabilityData[$index]['start_date']));
            $availability->setEndDate(new \DateTime($availabilityData[$index]['end_date']));
            $availability->setPricePerDay($availabilityData[$index]['price_per_day']);
            $availability->setStatus($availabilityData[$index]['status']);
            $manager->persist($availability);
        }

        $manager->flush();
    }
}
