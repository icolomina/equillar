<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Controller\Api\Contract;

use App\Presentation\Contract\DTO\Input\ContractMoveBalanceToTheReserveInputDto;
use App\Application\Contract\Service\ApproveContractService;
use App\Application\Contract\Service\Blockchain\ContractActivationService;
use App\Application\Contract\Service\Blockchain\ContractMoveFundsToTheReserveService;
use App\Application\Contract\Service\Blockchain\ContractReserveFundContributionCheckService;
use App\Application\Contract\Service\Blockchain\ContractReserveFundContributionTransferService;
use App\Application\Contract\Service\Blockchain\ContractStopOrRestartInvestmentsService;
use App\Application\Contract\Service\Blockchain\ContractWithdrawalApprovalService;
use App\Application\Contract\Service\Blockchain\CreateContractBalanceMovementService;
use App\Application\Contract\Service\CreateContractService;
use App\Application\Contract\Service\CreateContractWithdrawalRequestService;
use App\Application\Contract\Service\EditContractService;
use App\Application\Contract\Service\GetContractBalanceMovementsService;
use App\Application\Contract\Service\GetContractDocumentService;
use App\Application\Contract\Service\GetContractReserveFundContributionsService;
use App\Application\Contract\Service\GetContractsService;
use App\Application\Contract\Service\GetContractWithdrawalRequestsService;
use App\Application\Contract\Service\ModifyContractService;
use App\Application\Contract\Transformer\ContractBalanceMovementTransformer;
use App\Application\Contract\Transformer\ContractReserveFundContributionTransformer;
use App\Application\Token\Service\GetTokenBalanceService;
use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractBalanceMovement;
use App\Entity\Contract\ContractReserveFundContribution;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Entity\User;
use App\Presentation\Contract\DTO\Input\ContractRequestWithdrawalDtoInput;
use App\Presentation\Contract\DTO\Input\CreateContractDto;
use App\Presentation\Contract\DTO\Input\StopContractInvestmentsDtoInput;
use App\Presentation\Contract\DTO\Output\GetAddressTokenBalanceOutput;
use App\Security\Authorization\Voter\Contract\ContractBalanceMovementVoter;
use App\Security\Authorization\Voter\Contract\ContractReserveFundContributionVoter;
use App\Security\Authorization\Voter\Contract\ContractVoter;
use App\Security\Authorization\Voter\Contract\ContractWithdrawalRequestVoter;
use App\Domain\Contract\ContractPauseOrResumeTypes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Route('/api/v1/contract')]
class ContractController extends AbstractController
{
    #[Route('/get-issuer-contracts', name: 'get_issuer_contracts', methods: ['GET'])]
    public function getIssuerContractsAction(GetContractsService $getContractsService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        return $this->json($getContractsService->getContracts($user));
    }

    #[Route('/get-available-contracts', name: 'get_available_contracts', methods: ['GET'])]
    public function getAvailableContractsAction(GetContractsService $getContractsService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        return $this->json($getContractsService->getAvailableContracts($user));
    }

    #[Route('/create-contract', name: 'post_create_contract', methods: ['POST'])]
    public function createContractAction(
        #[MapRequestPayload] CreateContractDto $createContractDto,
        #[MapUploadedFile([
            new NotBlank(message: 'You must upload a valid Project file'),
            new File(extensions: ['pdf'], extensionsMessage: 'Please upload a valid PDF'),
        ])] UploadedFile $file, 
        #[MapUploadedFile([
            new NotBlank(message: 'You must upload a valid Project image'),
            new File(extensions: ['jpg', 'png'], extensionsMessage: 'Please upload a valid image (jpg, png)'),
        ])] UploadedFile $image, 
        CreateContractService $createContractService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractVoter::CREATE_CONTRACT, null);
        /**
         * @var User $user
         */
        $user = $this->getUser();

        return $this->json($createContractService->createContract($createContractDto, $file, $image, $user));
    }

    #[Route('/{id}/edit-contract', name: 'get_edit_contract', methods: ['GET'])]
    public function editContractAction(Contract $contract, EditContractService $editContractService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractVoter::EDIT_CONTRACT, $contract);
        return $this->json($editContractService->editContract($contract));
    }

    #[Route('/{id}/modify-contract', name: 'patch_modify_contract', methods: ['POST'])]
    public function modifyContractAction(Contract $contract, #[MapRequestPayload] CreateContractDto $createContractDto, ModifyContractService $modifyContractService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractVoter::MODIFY_CONTRACT, $contract);

        /**
         * @var User $user
         */
        $user = $this->getUser();

        return $this->json($modifyContractService->modifyContract($contract, $createContractDto, $user));
    }

    #[Route('/{id}/approve-contract', name: 'patch_approve_contract', methods: ['PATCH'])]
    public function approveContractAction(Contract $contract, ApproveContractService $approveContractService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractVoter::APPROVE_CONTRACT, $contract);

        $approveContractService->approveContract($contract);
        return new JsonResponse(null, 204);
    }

    #[Route('/{id}/initalize-contract', name: 'patch_initialize_contract', methods: ['PATCH'])]
    public function initializeContract(Contract $contract, ContractActivationService $contractActivationService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractVoter::ACTIVATE_CONTRACT, $contract);
        $contractActivationService->activateContract($contract);
        return new JsonResponse(null, 204);
    }

    #[Route('/{id}/get-contract-document', name: 'get_contract_document', methods: ['GET'])]
    public function getContractDocument(Contract $contract, GetContractDocumentService $getContractDocumentService): Response
    {
        $this->denyAccessUnlessGranted(ContractVoter::EDIT_CONTRACT_DOCUMENT, $contract);
        return $getContractDocumentService->generateDownloadResponseFromContract($contract);
    }

    #[Route('/get-request-withdrawals', name: 'get_contract_request_withdrawals', methods: ['GET'])]
    public function getWithdrawalRequests(GetContractWithdrawalRequestsService $getContractWithdrawalRequestsService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractVoter::GET_WITHDRAWAL_REQUESTS, null);

        /**
         * @var User $user
         */
        $user = $this->getUser();

        return new JsonResponse($getContractWithdrawalRequestsService->getContractRequestWithdrawals($user));
    }

    #[Route('/{id}/request-withdrawal', name: 'post_request_withdrawal', methods: ['POST'])]
    public function requestWithdrawal(Contract $contract, #[MapRequestPayload] ContractRequestWithdrawalDtoInput $contractRequestWithdrawalDtoInput,
        CreateContractWithdrawalRequestService $createContractWithdrawalRequestService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractVoter::REQUEST_OPERATION, $contract);

        /**
         * @var User $user
         */
        $user = $this->getUser();

        return $this->json($createContractWithdrawalRequestService->createContractWithdrawalRequest($contract, $user, $contractRequestWithdrawalDtoInput));
    }

    #[Route('/requested-withdrawal/{id}/approve', name: 'patch_approve_requested_withdrawal', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function approveRequestedWithdrawal(ContractWithdrawalRequest $contractWithdrawalRequest, ContractWithdrawalApprovalService $contractWithdrawalApprovalService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractWithdrawalRequestVoter::APPROVE_WITHDRAWAL_REQUEST, $contractWithdrawalRequest);
        $contractWithdrawalApprovalService->processProjectWithdrawal($contractWithdrawalRequest);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/requested-withdrawal/{id}/reject', name: 'patch_reject_requested_withdrawal', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function rejectRequestedWithdrawal(ContractWithdrawalRequest $contractWithdrawalRequest): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractWithdrawalRequestVoter::REJECT_WITHDRAWAL_REQUEST, $contractWithdrawalRequest);
        return new JsonResponse(null, Response::HTTP_ACCEPTED);
    }

    #[Route('/{id}/pause-deposits', name: 'patch_pause_deposits', methods: ['PATCH'])]
    public function stopDeposits(Contract $contract, #[MapRequestPayload] StopContractInvestmentsDtoInput $stopContractInvestmentsDtoInput, 
    ContractStopOrRestartInvestmentsService $contractStopOrRestartInvestmentsService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractVoter::PAUSE_CONTRACT, $contract);
        $contractStopOrRestartInvestmentsService->stopOrRestartInvestments($contract, ContractPauseOrResumeTypes::PAUSE->name, $stopContractInvestmentsDtoInput->reason);

        return new JsonResponse(null, Response::HTTP_ACCEPTED);
    }

    #[Route('/{id}/resume-deposits', name: 'patch_resume_deposits', methods: ['PATCH'])]
    public function restartDeposits(Contract $contract, #[MapRequestPayload] StopContractInvestmentsDtoInput $stopContractInvestmentsDtoInput, 
    ContractStopOrRestartInvestmentsService $contractStopOrRestartInvestmentsService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractVoter::RESUME_CONTRACT, $contract);
        $contractStopOrRestartInvestmentsService->stopOrRestartInvestments($contract, ContractPauseOrResumeTypes::RESUME->name, $stopContractInvestmentsDtoInput->reason);

        return new JsonResponse(null, Response::HTTP_ACCEPTED);
    }

    #[Route('/{id}/get-contract-token-balance', name: 'api_get_contract_token_balance', methods: ['GET'])]
    public function getTokenBalance(Contract $contract, #[MapQueryParameter] string $address, GetTokenBalanceService $getTokenBalanceService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractVoter::REQUEST_OPERATION, $contract);
        $output = new GetAddressTokenBalanceOutput((string) $getTokenBalanceService->getContractTokenBalance($contract, $address));

        return $this->json($output);
    }

    #[Route('/get-reserve-fund-contributions', name: 'api_get_reserve_fund_contributions', methods: ['GET'])]
    public function getReserveFundContributions(GetContractReserveFundContributionsService $getContractReserveFundContributionsService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractVoter::GET_RESERVE_FUNDS_CONTRIBUTIONS, null);
        
        /**
         * @var User $user
         */
        $user = $this->getUser();

        return $this->json($getContractReserveFundContributionsService->getReserveFundContributions($user));
    }

    #[Route('/reserve-fund-contribution/{id}/check', name: 'api_patch_check_reserve_fund_contribution', methods: ['PATCH'])]
    public function checkContractReserveFundContribution(ContractReserveFundContribution $contractReserveFundContribution, ContractReserveFundContributionCheckService $contractReserveFundContributionCheckService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractReserveFundContributionVoter::CHECK_RESERVE_FUND_CONTRIBUTION, $contractReserveFundContribution);
        return $this->json($contractReserveFundContributionCheckService->check($contractReserveFundContribution));
    }

    #[Route('/reserve-fund-contribution/{id}/transfer', name: 'api_patch_transfer_reserve_fund_contribution', methods: ['PATCH'])]
    public function transferContractReserveFundContribution(ContractReserveFundContribution $contractReserveFundContribution, ContractReserveFundContributionTransferService $contractReserveFundContributionTransferService,
        ContractReserveFundContributionTransformer $contractReserveFundContributionTransformer): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractReserveFundContributionVoter::TRANSFER_RECEIVE_FUND_CONTRIBUTION, $contractReserveFundContribution);
        $contractReserveFundContributionTransferService->processReserveFundContribution($contractReserveFundContribution);
        return $this->json($contractReserveFundContributionTransformer->fromEntityToReserveFundContributionTransferOutputDto($contractReserveFundContribution));
    }

    #[Route('/{id}/request-available-to-reserve-fund-movement', name: 'api_post_available_to_reserve_fund_movement', methods: ['POST'])]
    public function requestAvailabeToReserveFundBalanceMovement(Contract $contract, #[MapRequestPayload] ContractMoveBalanceToTheReserveInputDto $contractMoveBalanceToTheReserveInputDto,
        CreateContractBalanceMovementService $createContractBalanceMovementService): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractVoter::REQUEST_OPERATION, $contract);

        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($createContractBalanceMovementService->createBalanceMovementFromAvailableToReserve($contract, $user, $contractMoveBalanceToTheReserveInputDto));
    }

    #[Route('/available-to-reserve-fund-movement/{id}/move', name: 'api_patch_move_available_to_reserve_fund_movement', methods: ['PATCH'])]
    public function moveAvailableToReserveFundMovement(ContractBalanceMovement $contractBalanceMovement, ContractMoveFundsToTheReserveService $contractMoveFundsToTheReserveService,
        ContractBalanceMovementTransformer $contractBalanceMovementTransformer): JsonResponse
    {
        $this->denyAccessUnlessGranted(ContractBalanceMovementVoter::PERFORM_BALANCE_MOVEMENT, $contractBalanceMovement);
        $contractMoveFundsToTheReserveService->moveAvailableFundsToTheReserve($contractBalanceMovement);

        return $this->json($contractBalanceMovementTransformer->fromEntityToMovedToTheReserveFundOutputDto($contractBalanceMovement));
    }

    #[Route('/get-contract-balance-movements', name: 'api_get_contract_balance_movements', methods: ['GET'])]
    public function getContractBalanceMovements(GetContractBalanceMovementsService $getContractBalanceMovementsService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($getContractBalanceMovementsService->getContractBalanceMovements($user));
    }   
}
