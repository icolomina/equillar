<?php

namespace App\Controller\Api\Contract;


use App\Application\UserContract\Service\CreateUserContractService;
use App\Application\UserContract\Service\GetUserContractPaymentsService;
use App\Application\UserContract\Service\GetUserContractsService;
use App\Application\UserContract\Service\UserContractEditService;
use App\Entity\Contract\UserContract;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;
use App\Presentation\Contract\DTO\Input\CreateUserContractDtoInput;
use App\Presentation\UserContract\DTO\Input\UserContractPaymentsInput;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/api/v1/user-contract-investment')]
class UserContractController extends AbstractController
{
    #[Route('/get-user-contracts', name: 'get_user_contracts', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getUserContracts(GetUserContractsService $getUserContractsInvestmentService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($getUserContractsInvestmentService->getUserContracts($user));
    }

    #[Route('/{id}', name: 'get_user_contract', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getUserContract(UserContract $userContractInvestment, GetUserContractsService $getUserContractsInvestmentService): JsonResponse
    {
        return $this->json($getUserContractsInvestmentService->getUserContract($userContractInvestment));
    }

    #[Route('/create-user-investment', name: 'post_create_user_contract_investment', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createUserContract(#[MapRequestPayload] CreateUserContractDtoInput $createUserContractDtoInput, CreateUserContractService $createUserContractService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($createUserContractService->createUserContract($createUserContractDtoInput, $user));
    }

    #[Route('/{id}/edit-user-contract', name: 'get_edit_user_contract', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function editUserContract(UserContract $userContract, UserContractEditService $userContractEditService): JsonResponse
    {
        return $this->json($userContractEditService->editUserContract($userContract));
    }

}

