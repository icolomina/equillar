<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Controller\Api\Contract;

use App\Application\UserContract\Service\CreateUserContractService;
use App\Application\UserContract\Service\GetUserContractsService;
use App\Application\UserContract\Service\UserContractEditService;
use App\Entity\Contract\UserContract;
use App\Entity\User;
use App\Presentation\Contract\DTO\Input\CreateUserContractDtoInput;
use App\Security\Authorization\Voter\Contract\User\UserContractVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    public function getUserContract(UserContract $userContract, GetUserContractsService $getUserContractsInvestmentService): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserContractVoter::GET_USER_CONTRACT, $userContract);
        return $this->json($getUserContractsInvestmentService->getUserContract($userContract));
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
        $this->denyAccessUnlessGranted(UserContractVoter::GET_USER_CONTRACT, $userContract);
        return $this->json($userContractEditService->editUserContract($userContract));
    }
}
