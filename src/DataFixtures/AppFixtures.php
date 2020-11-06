<?php

namespace App\DataFixtures;

use App\Entity\Meal;
use App\Entity\Ingredient;
use App\Entity\Instruction;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    const MEAL_IDS = [
        52770,
        52771,
        52772,
        52773,
        52774,
        52775,
        52776,
        52777,
        52779,
        52780
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::MEAL_IDS as $id) {
            // Interroge l'API pour récupérer les données d'une recette
            $data = file_get_contents('https://www.themealdb.com/api/json/v1/1/lookup.php?i=' . $id);
            // Convertit le JSON brut en tableaux PHP
            $json_data = json_decode($data, true);
            $mealData = $json_data['meals'][0];

            // Crée une nouvelle recette et lui assigne les valeurs reçues par l'API
            $meal = new Meal();
            $meal
                ->setName($mealData['strMeal'])
                ->setCategory($mealData['strCategory'])
                ->setArea($mealData['strArea'])
                ->setImage($mealData['strMealThumb'])
            ;
            // Marque la recette comme prête à être envoyée en base de données
            $manager->persist($meal);

            // Découpe la description de la recette en fonction des sauts de ligne
            $instructionData = explode("\r\n", $mealData['strInstructions']);
            // Filtre les lignes vides
            $instructionData = array_values( array_filter($instructionData, fn($item) => !empty($item) ) );

            // Pour chaque ligne dans les instructions
            foreach ($instructionData as $key => $description) {
                // Crée une nouvelle instruction associée à la recette
                $instruction = new Instruction();
                $instruction
                    ->setDescription($description)
                    ->setInstructionRank($key + 1)
                    ->setMeal($meal)
                ;
                // Marque l'instruction comme prête à être envoyée en base de données
                $manager->persist($instruction);
            }

            // Pour chaque nombre compris entre 1 et 20
            for ($i = 1; $i <= 20; $i += 1) {
                // Si l'ingrédient au numéro demandé n'existe pas
                if ( is_null($mealData['strIngredient' . $i]) || empty($mealData['strIngredient' . $i] )) {
                    // Interrompt la boucle
                    break;
                }

                // Crée un nouvel ingrédient associé à la recette
                $ingredient = new Ingredient();
                // Associe la nature de l'ingrédient et sa mesure à l'objet
                $ingredient
                    ->setName($mealData['strIngredient' . $i])
                    ->setMeasure($mealData['strMeasure' . $i])
                    ->setMeal($meal)
                ;

                // Marque l'ingrédient' comme prête à être envoyée en base de données
                $manager->persist($ingredient);
            }
        }

        // Envoie toutes les modifications en base de données
        $manager->flush();
    }
}
