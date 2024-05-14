<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for handling the home page.
 */
class HomeController extends AbstractController
{
    /**
     * Renders the home page with a list of available vehicles.
     * 
     * @param Request $request The HTTP request object.
     * @param VehicleRepository $repository The repository for vehicle entities.
     * 
     * @return Response A Response instance representing the rendered web page.
     * 
     * @throws NotFoundHttpException When an error occurs while processing the request.
     */
    #[Route("/", name: "home")]
    function index(Request $request, VehicleRepository $repository): Response
    {
        try {
            $searchForm = $this->createForm(SearchType::class);
            $searchForm->handleRequest($request);

            $vehicles = [];
            $maxPrice = null;

            if ($searchForm->isSubmitted() && $searchForm->isValid()) {
                // Extract form data
                $formData = $searchForm->getData();
                $startDate = $formData['startDate'];
                $endDate = $formData['endDate'];
                $maxPrice = $formData['maxPrice'];

                // Find available vehicles for the specified dates
                $vehicles = $repository->findAvailableVehicles($startDate, $endDate, $maxPrice);

                // Check if no vehicles are available for the specified dates
                if (empty($vehicles)) {
                    // Clone the start and end dates
                    $expandedStartDate = clone $startDate;
                    $expandedEndDate = clone $endDate;

                    // Modify the cloned dates to expand the search range by +/- 1 day
                    $expandedStartDate->modify('-1 day');
                    $expandedEndDate->modify('+1 day');

                    // Search for available vehicles within the expanded date range
                    $vehicles = $repository->findAvailableVehicles($expandedStartDate, $expandedEndDate, $maxPrice);
                }

                // Filter out vehicles with availability status
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

                // Fetch all vehicles
                $vehicles = $repository->findAll();
                // Filter vehicles based on availability status
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

            // Render the home page
            return $this->render('home/index.html.twig', [
                'vehicles' => $vehicles,
                'searchForm' => $searchForm->createView()
            ]);
        } catch (\Exception $e) {
            // Throw a 404 exception with the error message
            throw new NotFoundHttpException($e->getMessage());
        }
    }
}
