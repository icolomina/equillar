<?php

namespace App\Application\Contract\Transformer;

use App\Domain\Contract\ContractWithdrawalStatus;
use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Entity\User;
use App\Presentation\Contract\DTO\Input\ContractRequestWithdrawalDtoInput;
use App\Presentation\Contract\DTO\Output\ContractWithdrawalRequestDtoOutput;
use Symfony\Component\Uid\Uuid;

class ContractWithdrawalRequestEntityTransformer
{
    public function fromRequestWithdrawalDtoToEntity(Contract $contract, User $user, ContractRequestWithdrawalDtoInput $contractInvestmentRequestWithdrawalDtoInput): ContractWithdrawalRequest
    {
        $validUntil = (new \DateTimeImmutable())->add(\DateInterval::createFromDateString('+ 15 minutes'));

        $requestWithdrawal = new ContractWithdrawalRequest();
        $requestWithdrawal->setContract($contract);
        $requestWithdrawal->setRequestedAt(new \DateTimeImmutable());
        $requestWithdrawal->setRequestedAmount($contractInvestmentRequestWithdrawalDtoInput->requestedAmount);
        $requestWithdrawal->setRequestedBy($user);
        $requestWithdrawal->setValidUntil($validUntil);
        $requestWithdrawal->setUuid(Uuid::v4()->toString());
        $requestWithdrawal->setStatus(ContractWithdrawalStatus::REQUESTED->name);

        return $requestWithdrawal;
    }

    public function fromEntityToOutputDto(ContractWithdrawalRequest $contractWithdrawalRequest): ContractWithdrawalRequestDtoOutput
    {
        $numberFormatter = new \NumberFormatter($contractWithdrawalRequest->getContract()->getToken()->getLocale(), \NumberFormatter::CURRENCY);

        $withdrawalApproval = $contractWithdrawalRequest->getWithdrawalApproval();
        $status = $withdrawalApproval?->getStatus() ?? $contractWithdrawalRequest->getStatus();

        return new ContractWithdrawalRequestDtoOutput(
            $contractWithdrawalRequest->getId(),
            $contractWithdrawalRequest->getContract()->getLabel(),
            $contractWithdrawalRequest->getRequestedAt()->format('Y-m-d H:i'),
            $contractWithdrawalRequest->getRequestedBy()->getName(),
            $numberFormatter->formatCurrency(
                $contractWithdrawalRequest->getRequestedAmount(), $contractWithdrawalRequest->getContract()->getToken()->getReferencedCurrency()
            ),
            str_replace('_', ' ', $status),
            $withdrawalApproval?->getApprovedAt()?->format('Y-m-d H:i'),
            $contractWithdrawalRequest->getWithdrawalApproval()?->getContractTransaction()?->getTrxHash()
        );
    }

    /**
     * @param ContractWithdrawalRequest[] $contractWithdrawalRequests
     *
     * @return ContractWithdrawalRequestDtoOutput[]
     */
    public function fromEntitiesToOutputDtos(iterable $contractWithdrawalRequests): array
    {
        return array_map(
            fn (ContractWithdrawalRequest $contractWithdrawalRequest) => $this->fromEntityToOutputDto($contractWithdrawalRequest),
            $contractWithdrawalRequests
        );
    }

    public function updateWithdrawalRequestAsConfirmed(ContractWithdrawalRequest $contractWithdrawalRequest): void
    {
        $contractWithdrawalRequest->setStatus(ContractWithdrawalStatus::CONFIRMED->name);
    }
}
