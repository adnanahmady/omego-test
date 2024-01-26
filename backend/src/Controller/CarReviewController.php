<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CarReviewController extends AbstractController
{
    public function index(
        Request $request,
        ReviewRepository $repository,
        Car $car
    ): array {
        return $repository->findHighestAndLatest(
            $car,
            $request->get('max-count'),
            $request->get('rate-higher-than'),
        );
    }
}
