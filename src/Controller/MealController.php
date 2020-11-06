<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Repository\MealRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/meal", name="meal_")
 */
class MealController extends AbstractController
{
    /**
     * @Route("", name="list")
     */
    public function list(MealRepository $repository): Response
    {
        return $this->render('meal/list.html.twig', [
            'meals' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="single", requirements={"id"="\d+"})
     */
    public function single(Meal $meal): Response
    {
        return $this->render('meal/single.html.twig', [
            'meal' => $meal,
        ]);
    }
}
