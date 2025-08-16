<?php

namespace App\Controller\Api\Contract;

use App\Application\UserContract\Service\GetUserContractPaymentsService;
use App\Presentation\UserContract\DTO\Input\UserContractPaymentsInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;

#[Route('/api/v1/user-contract-payments')]
class UserContractPaymentsController extends AbstractController
{
    #[Route('/get-user-payments', name: 'get_user_contract_payments', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getUserContractPayments(#[MapQueryString] UserContractPaymentsInput $userContractPayments, GetUserContractPaymentsService $getUserContractPaymentsService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($getUserContractPaymentsService->getUserContractPayments($user));
    }
}
