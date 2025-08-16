<?php

namespace App\Controller\Api\Stellar;

use App\Blockchain\Stellar\Transaction\GetStellarTransactionDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/blockchain/stellar')]
class StellarController extends AbstractController
{
    #[Route('/get-tx-data', name: 'api_get_transaction_data', methods: ['GET'])]
    public function getTransactionData(#[MapQueryParameter] string $hash, GetStellarTransactionDataService $getStellarTransactionDataService): JsonResponse
    {
        return $this->json($getStellarTransactionDataService->getTransactionData($hash));
    }
}
