<?php

namespace App\Controller\Api\Contract;

use App\Application\Contract\Service\ApproveContractService;
use App\Application\Contract\Service\Blockchain\ContractActivationService;
use App\Application\Contract\Service\Blockchain\ContractWithdrawalApprovalService;
use App\Application\Contract\Service\EditContractService;
use App\Application\Contract\Service\GetContractsService;
use App\Application\Contract\Service\CreateContractService;
use App\Application\Contract\Service\CreateContractWithdrawalRequestService;
use App\Application\Contract\Service\CreateReserveFundContributionService;
use App\Application\Contract\Service\GetContractDocumentService;
use App\Application\Contract\Service\GetContractReserveFundContributionsService;
use App\Application\Contract\Service\GetContractWithdrawalRequestsService;
use App\Application\Contract\Service\ModifyContractService;
use App\Application\Token\Service\GetTokenBalanceService;
use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Entity\User;
use App\Message\StopContractInvestmentsMessage;
use App\Presentation\Contract\DTO\Input\ContractRequestWithdrawalDtoInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use App\Presentation\Contract\DTO\Input\CreateContractDto;
use App\Presentation\Contract\DTO\Input\CreateContractReserveFundContributionDtoInput;
use App\Presentation\Contract\DTO\Input\StopContractInvestmentsDtoInput;
use App\Presentation\Contract\DTO\Output\GetAddressTokenBalanceOutput;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Route('/api/v1/contract')]
class ContractController extends AbstractController
{
    #[Route('/get-issuer-contracts', name: 'get_issuer_contracts', methods: ['GET'])]
    #[IsGranted('ROLE_COMPANY')]
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
    #[IsGranted('ROLE_COMPANY')]
    public function createContractAction(
        #[MapRequestPayload] CreateContractDto $createContractDto, 
        #[MapUploadedFile([
            new NotBlank(message: 'You must upload a valid Project file'),
            new File(extensions:['pdf'], extensionsMessage: 'Please upload a valid PDF')
        ])] UploadedFile $file, CreateContractService $createContractService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($createContractService->createContract($createContractDto, $file, $user));
    }

    #[Route('/{id}/edit-contract', name: 'get_edit_contract', methods: ['GET'])]
    public function editContractAction(Contract $contract, EditContractService $editContractService): JsonResponse
    {
        return $this->json($editContractService->editContract($contract));
    }

     #[Route('/{id}/modify-contract', name: 'patch_modify_contract', methods: ['POST'])]
    public function modifyContractAction(Contract $contract, #[MapRequestPayload] CreateContractDto $createContractDto, 
        #[MapUploadedFile([
            new File(extensions:['pdf'], extensionsMessage: 'Please upload a valid PDF')
        ])] UploadedFile|array $file, ModifyContractService $modifyContractService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user   = $this->getUser();;
        return $this->json($modifyContractService->modifyContract($contract, $createContractDto, $file, $user));
    }

    #[Route('/{id}/approve-contract', name: 'patch_approve_contract', methods: ['PATCH'])]
    #[IsGranted('ROLE_COMPANY')]
    public function approveContractAction(Contract $contract, ApproveContractService $approveContractService): JsonResponse
    {
        $approveContractService->approveContract($contract);
        return new JsonResponse(null, 204);
    }

    #[Route('/{id}/initalize-contract', name: 'patch_initialize_contract', methods: ['PATCH'])]
    #[IsGranted('ROLE_COMPANY')]
    public function initializeContract(Contract $contract, ContractActivationService $contractActivationService): JsonResponse
    {
        $contractActivationService->activateContract($contract);
        return new JsonResponse(null, 204);
    }

    #[Route('/{id}/get-contract-document', name: 'get_contract_document', methods: ['GET'])]
    public function getContractDocument(Contract $contract, GetContractDocumentService $getContractDocumentService): Response
    {
        return $getContractDocumentService->generateDownloadResponseFromContract($contract);
    }

    #[Route('/get-request-withdrawals', name: 'get_contract_request_withdrawals', methods: ['GET'])]
    #[IsGranted('ROLE_COMPANY')]
    public function getWithdrawalRequests(GetContractWithdrawalRequestsService $getContractWithdrawalRequestsService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return new JsonResponse($getContractWithdrawalRequestsService->getContractRequestWithdrawals($user));
    }

    #[Route('/{id}/request-withdrawal', name: 'post_request_withdrawal', methods: ['POST'])]
    #[IsGranted('ROLE_COMPANY')]
    public function requestWithdrawal(Contract $contract, #[MapRequestPayload] ContractRequestWithdrawalDtoInput $contractRequestWithdrawalDtoInput,
        CreateContractWithdrawalRequestService $createContractWithdrawalRequestService): JsonResponse
    {
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
        $contractWithdrawalApprovalService->processProjectWithdrawal($contractWithdrawalRequest);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/requested-withdrawal/{id}/reject', name: 'patch_reject_requested_withdrawal', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function rejectRequestedWithdrawal(ContractWithdrawalRequest $contractWithdrawalRequest, MessageBusInterface $bus): JsonResponse
    {
        //$bus->dispatch(new ApproveRequestWithdrawalMessage($contractWithdrawalRequest->getId()));
        return new JsonResponse(null, Response::HTTP_ACCEPTED);
    }

    #[Route('/{id}/stop-deposits', name: 'patch_stop_deposits', methods: ['PATCH'])]
    #[IsGranted('ROLE_COMPANY')]
    public function stopDeposits(Contract $contract, #[MapRequestPayload] StopContractInvestmentsDtoInput $stopContractInvestmentsDtoInput, MessageBusInterface $bus): JsonResponse
    {
        $bus->dispatch(new StopContractInvestmentsMessage($contract->getId(), $stopContractInvestmentsDtoInput->reason));
        return new JsonResponse(null, Response::HTTP_ACCEPTED);
    }
    
    #[Route('/{id}/get-contract-token-balance', name: 'api_get_contract_token_balance', methods: ['GET'])]
    public function getTokenBalance(Contract $contract, #[MapQueryParameter] string $address, GetTokenBalanceService $getTokenBalanceService): JsonResponse
    {   
        $output = new GetAddressTokenBalanceOutput((string)$getTokenBalanceService->getContractTokenBalance($contract, $address));
        return $this->json($output);
    }

    #[Route('/get-reserve-fund-contributions', name: 'api_get_reserve_fund_contributions', methods: ['GET'])]
    public function getReserveFundContributions(GetContractReserveFundContributionsService $getContractReserveFundContributionsService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($getContractReserveFundContributionsService->getReserveFundContributions($user));
    }

    #[Route('/{id}/request-reserve-fund-contribution', name: 'api_post_request_reserve_fund_contribution', methods: ['POST'])]
    public function requestContractReserveContribution(Contract $contract, #[MapRequestPayload] CreateContractReserveFundContributionDtoInput $createContractReserveFundContributionDtoInput, CreateReserveFundContributionService $createReserveFundContributionService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($createReserveFundContributionService->createReserveFundContribution($contract, $createContractReserveFundContributionDtoInput, $user));
    }
}
