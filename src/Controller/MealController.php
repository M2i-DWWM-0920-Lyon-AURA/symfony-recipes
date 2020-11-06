<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Form\MealType;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/new", name="new_form", methods={"GET"})
     */
    public function newForm(): Response
    {
        $meal = new Meal();

        $form = $this->createForm(MealType::class, $meal);

        return $this->render('meal/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"POST"})
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $meal = new Meal();

        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $meal = $form->getData();
    
            $manager->persist($meal);
            $manager->flush();

            return $this->redirectToRoute('meal_list');
        }

        return $this->render('meal/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/update", name="update_form", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function updateForm(Meal $meal): Response
    {
        $form = $this->createForm(MealType::class, $meal);

        return $this->render('meal/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/update", name="update", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function update(Meal $meal, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $meal = $form->getData();
    
            $manager->persist($meal);
            $manager->flush();

            return $this->redirectToRoute('meal_list');
        }

        return $this->render('meal/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function delete(Meal $meal, EntityManagerInterface $manager)
    {
        $manager->remove($meal);
        $manager->flush();

        return $this->redirectToRoute('meal_list');
    }
}
