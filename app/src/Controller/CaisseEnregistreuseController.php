<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CaisseEnregistreuseController extends AbstractController
{

    #[Route('/cash-register', name: 'cash_register', methods: ['POST'])]
    public function cashRegister(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $currency = $data['currency'];
        $solutionOptimale = $request->query->get('solutionOptimal', false);

        if ($currency <= 0) {
            return $this->json(['error' => 'Montant invalide'], Response::HTTP_BAD_REQUEST);
        }

        if ($currency % 2 != 0 && $currency < 5) {
            return $this->json(['error' => "Impossible de rendre de la monnaie sur $currency €, nous pouvons rendre uniquement des pièces de 2€ ou des billets de 5€ et 10€"], Response::HTTP_BAD_REQUEST);
        }

        if ($solutionOptimale) {
            $resultats = $this->optimalChange($currency);
            return $this->json(['Monnaie à rendre' => $currency, 'Solution optimale' => $resultats], Response::HTTP_OK);
        } else {
            $resultats = $this->possibleSolutions($currency);
            return $this->json(['Monnaie à rendre' => $currency, 'Solutions possibles' => $resultats], Response::HTTP_OK);
        }
    }

    private function optimalChange(int $currency): string
    {
        $solutions = $this->possibleSolutions($currency);
        usort($solutions, fn ($a, $b) => strlen($a) - strlen($b));
        return $solutions[0];
    }

    private function possibleSolutions(int $currency): array
    {
        $result = [];
        $this->findCombinations($currency, [], $result);
        return array_map(fn ($combination) => implode('+', $combination), $result);
    }

    private function findCombinations(int $currency, array $currentCombination, array &$result)
    {
        $denominations = [10, 5, 2];

        if ($currency == 0) {
            $result[] = $currentCombination;
            return;
        }

        foreach ($denominations as $denomination) {
            if ($currency >= $denomination) {
                $newCombination = $currentCombination;
                $newCombination[] = $denomination;
                $this->findCombinations($currency - $denomination, $newCombination, $result);
            }
        }
    }
}
