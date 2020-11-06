<?php

namespace App\Controller;

use App\Entity\Instruction;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InstructionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/instruction", name="instruction_")
 */
class InstructionController extends AbstractController
{
    /**
     * @Route("/{id}/delete", name="delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function delete(Instruction $instruction, EntityManagerInterface $manager, InstructionRepository $repository, MealRepository $mealRepository)
    {
        $manager->detach($instruction->getMeal());
        $meal = $mealRepository->find($instruction->getMeal()->getId());
        $instructionsToReindex = $repository->findAllInMealWhereRankAtLeast($meal, $instruction->getInstructionRank() + 1);

        foreach ($instructionsToReindex as $instructionToReindex) {
            $instructionToReindex->setInstructionRank(
                $instructionToReindex->getInstructionRank() - 1
            );
            $manager->persist($instructionToReindex);
        }

        $manager->remove($instruction);
        $manager->flush();

        return $this->redirectToRoute('meal_update_form', [ 'id' => $instruction->getMeal()->getId() ]);
    }
}
