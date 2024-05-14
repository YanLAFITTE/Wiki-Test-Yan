<?php

namespace App\Controller;

use App\Entity\Availability;
use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for managing vehicle-related operations.
 */
class VehiclesController extends AbstractController
{
    /**
     * Display a list of vehicles.
     */
    #[Route('/vehicles', name: 'vehicles.index')]
    public function index(Request $request, VehicleRepository $repository): Response
    {
        // Retrieve all vehicles from the database
        $vehicles = $repository->findAll();
        // If no vehicles are found, throw a not found exception
        if (!$vehicles) {
            throw $this->createNotFoundException('Vehicles not found');
        }
        // Render the vehicles index template with the retrieved vehicles
        return  $this->render('vehicles/index.html.twig', [
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Display details of a specific vehicle.
     */
    #[Route('/vehicles/{slug}-{id}', name: 'vehicles.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id, VehicleRepository $repository): Response
    {
        // Retrieve the vehicle with the given ID
        $vehicle = $repository->find($id);

        // If no vehicle is found, throw a not found exception
        if (!$vehicle) {
            throw $this->createNotFoundException('Vehicle not found');
        }

        // Retrieve availabilities of the vehicle
        $availabilities = $vehicle->getAvailabilities();

        // Render the vehicle details template with the retrieved data
        return $this->render("vehicles/show.html.twig", [
            'slug' => $slug,
            'id' => $id,
            'vehicle' => $vehicle,
            'availabilities' => $availabilities
        ]);
    }

    /**
     * Edit details of a vehicle.
     */
    #[Route('/vehicles/{id}/edit', name: 'vehicles.edit')]
    public function edit(Vehicle $vehicle, Request $request, EntityManagerInterface $em)
    {
        // If no vehicle is found, throw a not found exception
        if (!$vehicle) {
            throw $this->createNotFoundException('Vehicle not found');
        }

        // Create and handle the form for editing the vehicle
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La modification a bien été prise en compte.');
            return $this->redirectToRoute('vehicles.index');
        }

        // Render the edit vehicle form
        return $this->render('vehicles/edit.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView()
        ]);
    }

    /**
     * Create a new vehicle.
     */
    #[Route('/vehicles/create', name: 'vehicles.create')]
    public function create(Request $request, EntityManagerInterface $em)
    {

        // Create a new vehicle and availability
        $vehicle = new Vehicle();
        $availability = new Availability();


        // Create and handle the form for creating the vehicle
        $vehicle->addAvailability($availability);
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($vehicle->getAvailabilities() as $availability) {
                $em->persist($availability);
            }
            $em->persist($vehicle);
            $em->flush();
            $this->addFlash('success', 'Le vehicule a bien été ajouter');
            return $this->redirectToRoute(('vehicles.index'));
        }

        // Render the create vehicle form
        return $this->render('vehicles/create.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete a vehicle.
     */
    #[Route('/vehicles/{id}/delete', name: 'vehicles.delete', methods: ['DELETE'])]
    public function remove(Vehicle $vehicle, EntityManagerInterface $em)
    {
        // If no vehicle is found, throw a not found exception
        if (!$vehicle) {
            throw $this->createNotFoundException('Vehicle not found');
        }

        // Remove the vehicle from the database
        $em->remove($vehicle);
        $em->flush();

        // Add a flash message and redirect to the index page
        $this->addFlash('success', 'Le vehicule a bien été supprimer.');
        return $this->redirectToRoute('vehicles.index');
    }
}
