<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route("/", name: "home")]
    function index(Request $request, VehicleRepository $repository): Response
    {

        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);

        $vehicles = [];
        $maxPrice = null;

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $formData = $searchForm->getData();
            $startDate = $formData['startDate'];
            $endDate = $formData['endDate'];
            $maxPrice = $formData['maxPrice'];

            $vehicles = $repository->findAvailableVehicles($startDate, $endDate, $maxPrice);

            //  if (empty($vehicles)) {

            //     $expandedStartDate = clone $startDate;
            //     $expandedEndDate = clone $endDate;
            //     $expandedStartDate->modify('+1 day');
            //     $expandedEndDate->modify('-1 day');
            // replace time() with the time stamp you want to add one day to
            // $startDate = time();
            // date('Y-m-d H:i:s', strtotime('+1 day', $startDate));

            //     $vehicles = $repository->findAvailableVehicles($expandedStartDate, $expandedEndDate, $maxPrice);
            //     dd($expandedStartDate);
            // }

            $availableVehicles = [];
            foreach ($vehicles as $vehicle) {
                $availabilities = $vehicle->getAvailabilities();
                foreach ($availabilities as $availability) {
                    if ($availability->isStatus() == 1) {
                        $availableVehicles[] = $vehicle;
                        break;
                    }
                }
            }

            $vehicles = $availableVehicles;
        } else {

            $vehicles = $repository->findAll();
            $availableVehicles = [];
            foreach ($vehicles as $vehicle) {
                $availabilities = $vehicle->getAvailabilities();
                foreach ($availabilities as $availability) {
                    if ($availability->isStatus() == 1) {
                        $availableVehicles[] = $vehicle;
                        break;
                    }
                }
            }
            $vehicles = $availableVehicles;
        }

        return $this->render('home/index.html.twig', [
            'vehicles' => $vehicles,
            'searchForm' => $searchForm->createView()
        ]);
    }
}
