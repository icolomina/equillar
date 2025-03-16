<?php

namespace App\Controller\Api\Investment;

use App\Application\Investment\Contract\Service\ApproveContractInvestmentService;
use App\Application\Investment\Contract\Service\CreateContractInvestmentService;
use App\Application\Investment\Contract\Service\CreateContractInvestmentWithdrawalRequestService;
use App\Application\Investment\Contract\Service\EditContractInvestmentService;
use App\Application\Investment\Contract\Service\GetContractsInvestmentService;
use App\Application\Investment\Contract\Service\InitializeContractInvestmentService;
use App\Application\Investment\Contract\Service\StopContractInvestmentsService;
use App\Entity\Investment\ContractInvestment;
use App\Entity\Investment\ContractInvestmentWithdrawalRequest;
use App\Entity\User;
use App\Presentation\Contract\DTO\Input\ContractInvestmentRequestWithdrawalDtoInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use App\Presentation\Contract\DTO\Input\CreateContractDto;
use App\Presentation\Contract\DTO\Input\InitializeContractDtoInput;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Route('/api/v1/contract-investment')]
class ContractInvestmentController extends AbstractController
{
    #[Route('/get-issuer-contracts', name: 'get_issuer_contracts', methods: ['GET'])]
    #[IsGranted('ROLE_COMPANY')]
    public function getIssuerContractsAction(GetContractsInvestmentService $getContractsInvestmentsService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($getContractsInvestmentsService->getContracts($user));
    }

    #[Route('/get-available-contracts', name: 'get_available_contracts', methods: ['GET'])]
    public function getAvailableContractsAction(GetContractsInvestmentService $getContractsInvestmentsService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($getContractsInvestmentsService->getAvailableContracts($user));
    }

    #[Route('/create-contract', name: 'post_create_contract', methods: ['POST'])]
    #[IsGranted('ROLE_COMPANY')]
    public function createContractAction(
        #[MapRequestPayload] CreateContractDto $createContractDto, 
        #[MapUploadedFile([
            new NotBlank(message: 'You must upload a valid Project file'),
            new File(extensions:['pdf'], extensionsMessage: 'Please upload a valid PDF')
        ])] UploadedFile $file, CreateContractInvestmentService $createContractInvestmentService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($createContractInvestmentService->createContract($createContractDto, $file, $user));
    }

    #[Route('/{id}/edit-contract', name: 'get_edit_contract', methods: ['GET'])]
    public function editContractAction(ContractInvestment $contract, EditContractInvestmentService $editContractInvestmentService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user   = $this->getUser();
        return $this->json($editContractInvestmentService->editContract($contract, $user));
    }

    #[Route('/{id}/approve-contract', name: 'patch_approve_contract', methods: ['PATCH'])]
    #[IsGranted('ROLE_COMPANY')]
    public function approveContractAction(ContractInvestment $contract, ApproveContractInvestmentService $approveContractInvestmentService): JsonResponse
    {
        return $this->json($approveContractInvestmentService->approveContractInvestment($contract));
    }

    #[Route('/{id}/initalize-contract', name: 'patch_initialize_contract', methods: ['PATCH'])]
    #[IsGranted('ROLE_COMPANY')]
    public function initializeContract(ContractInvestment $contract, #[MapRequestPayload] InitializeContractDtoInput $initializeContractDtoInput, InitializeContractInvestmentService $initializeContractInvestmentService): JsonResponse
    {
        return $this->json($initializeContractInvestmentService->initializeContract($contract, $initializeContractDtoInput));
    }

    #[Route('/{id}/request-withdrawal', name: 'post_request_withdrawal', methods: ['POST'])]
    #[IsGranted('ROLE_COMPANY')]
    public function requestWithdrawal(ContractInvestment $contract, #[MapRequestPayload] ContractInvestmentRequestWithdrawalDtoInput $contractInvestmentRequestWithdrawalDtoInput,
        CreateContractInvestmentWithdrawalRequestService $createContractInvestmentWithdrawalRequestService): JsonResponse
    {
        return $this->json($createContractInvestmentWithdrawalRequestService->createContractInvestmentWithdrawalRequest($contract, $contractInvestmentRequestWithdrawalDtoInput));
    }

    #[Route('/{id}/approve-withdrawal', name: 'patch_approve_withdrawal', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function approveWithdrawal(ContractInvestmentWithdrawalRequest $contractInvestmentWithdrawalRequest): JsonResponse
    {
        return new JsonResponse(null);
    }

    /*#[Route('/{id}/stop-deposits', name: 'patch_stop_deposits', methods: ['PATCH'])]
    #[IsGranted('ROLE_COMPANY')]
    public function stopDeposits(ContractInvestment $contract, StopContractInvestmentsService $stopContractInvestmentsService): JsonResponse
    {
        return $this->json($stopContractInvestmentsService->stopInvestments($contract));
    }*/
    
}
