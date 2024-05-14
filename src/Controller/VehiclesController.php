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

class VehiclesController extends AbstractController
{
    #[Route('/vehicles', name: 'vehicles.index')]
    public function index(Request $request, VehicleRepository $repository): Response
    {
        $vehicles = $repository->findAll();
        if (!$vehicles) {
            throw $this->createNotFoundException('Vehicles not found');
        }
        return  $this->render('vehicles/index.html.twig', [
            'vehicles' => $vehicles
        ]);
    }

    #[Route('/vehicles/{slug}-{id}', name: 'vehicles.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id, VehicleRepository $repository): Response
    {
        $vehicle = $repository->find($id);
        
        if (!$vehicle) {
            throw $this->createNotFoundException('Vehicle not found');
        }

        $availabilities = $vehicle->getAvailabilities();
        
        return $this->render("vehicles/show.html.twig", [
            'slug' => $slug,
            'id' => $id,
            'vehicle' => $vehicle,
            'availabilities' => $availabilities
        ]);
    }

    #[Route('/vehicles/{id}/edit', name: 'vehicles.edit')]
    public function edit(Vehicle $vehicle, Request $request, EntityManagerInterface $em)
    {
        if (!$vehicle) {
            throw $this->createNotFoundException('Vehicle not found');
        }

        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La modification a bien été prise en compte.');
            return $this->redirectToRoute('vehicles.index');
        }
        return $this->render('vehicles/edit.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView()
        ]);
    }


    #[Route('/vehicles/create', name: 'vehicles.create')]
    public function create(Request $request, EntityManagerInterface $em)
    {

        $vehicle = new Vehicle();
        $availability = new Availability();

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
        return $this->render('vehicles/create.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView()
        ]);
    }

    #[Route('/vehicles/{id}/delete', name: 'vehicles.delete', methods: ['DELETE'])]
    public function remove(Vehicle $vehicle, EntityManagerInterface $em)
    {
        if (!$vehicle) {
            throw $this->createNotFoundException('Vehicle not found');
        }
        $em->remove($vehicle);
        $em->flush();
        $this->addFlash('success', 'Le vehicule a bien été supprimer.');
        return $this->redirectToRoute('vehicles.index');
    }
}
