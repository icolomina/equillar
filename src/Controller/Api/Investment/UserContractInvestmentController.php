<?php

namespace App\Controller\Api\Investment;

use App\Application\Investment\UserContract\Service\CreateUserContractInvestmentService;
use App\Application\Investment\UserContract\Service\GetUserContractsInvestmentService;
use App\Entity\Investment\UserContractInvestment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;
use App\Presentation\Contract\DTO\Input\CreateUserContractDtoInput;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/api/v1/user-contract-investment')]
class UserContractInvestmentController extends AbstractController
{
    #[Route('', name: 'get_user_contracts', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getUserContracts(GetUserContractsInvestmentService $getUserContractsInvestmentService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($getUserContractsInvestmentService->getUserContracts($user));
    }

    #[Route('/{id}', name: 'get_user_contract', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getUserContract(UserContractInvestment $userContractInvestment, GetUserContractsInvestmentService $getUserContractsInvestmentService): JsonResponse
    {
        return $this->json($getUserContractsInvestmentService->getUserContract($userContractInvestment));
    }

    #[Route('/create-user-investment', name: 'post_create_user_contract_investment', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createUserContract(#[MapRequestPayload] CreateUserContractDtoInput $createUserContractDtoInput, CreateUserContractInvestmentService $createUserContractInvestmentService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($createUserContractInvestmentService->createUserContract($createUserContractDtoInput, $user));
    }
}

