<?php

namespace App\Application\Contract\Transformer;

use App\Domain\Contract\ContractReserveFundContributionStatus;
use App\Domain\Contract\Service\ContractReserveFundContributonIdEncoder;
use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractReserveFundContribution;
use App\Entity\ContractTransaction;
use App\Entity\User;
use App\Presentation\Contract\DTO\Output\ContractReserveFundContributionCreatedDtoOutput;
use App\Presentation\Contract\DTO\Output\ContractReserveFundContributionDtoOutput;
use Symfony\Component\Uid\Uuid;

class ContractReserveFundContributionTransformer
{
    public function __construct(
        private readonly ContractReserveFundContributonIdEncoder $contractReserveFundContributonIdEncoder
    ){}

    public function fromAmountAndUserToEntity(User $user, Contract $contract, float $amount): ContractReserveFundContribution
    {
        $contractReserveFundContribution = new ContractReserveFundContribution();
        $contractReserveFundContribution->setStatus(ContractReserveFundContributionStatus::CREATED->name);
        $contractReserveFundContribution->setCreatedAt(new \DateTimeImmutable());
        $contractReserveFundContribution->setAmount($amount);
        $contractReserveFundContribution->setSourceUser($user);
        $contractReserveFundContribution->setUuid(Uuid::v4());
        $contractReserveFundContribution->setContract($contract);

        return $contractReserveFundContribution;
    }

    public function fromEntityToContractReserveFundContributionCreatedDtoOutput(ContractReserveFundContribution $contractReserveFundContribution, string $systemStellarAddress): ContractReserveFundContributionCreatedDtoOutput
    {
        return new ContractReserveFundContributionCreatedDtoOutput(
            $this->contractReserveFundContributonIdEncoder->encodeId($contractReserveFundContribution->getUuid()),
            $systemStellarAddress,
            $contractReserveFundContribution->getAmount()
        );
    }

    public function fromEntityToOutputDto(ContractReserveFundContribution $contractReserveFundContribution): ContractReserveFundContributionDtoOutput
    {
        return new ContractReserveFundContributionDtoOutput(
            $contractReserveFundContribution->getId(),
            $contractReserveFundContribution->getContract()->getLabel(),
            $contractReserveFundContribution->getAmount(),
            $contractReserveFundContribution->getStatus(),
            $contractReserveFundContribution->getCreatedAt()->format('Y-m-d H:i'),
            $contractReserveFundContribution->getReceivedAt()->format('Y-m-d H:i'),
            $contractReserveFundContribution->getTransferredAt()->format('Y-m-d H:i'),
        );
    }

    /**
     * @param ContractReserveFundContribution[] $contractReserveFundContributions
     * @return ContractReserveFundContributionDtoOutput[]
     */
    public function fromEntitiesToOutputDtos(iterable $contractReserveFundContributions): iterable
    {
        return array_map(
            fn(ContractReserveFundContribution $contractReserveFundContribution) => $this->fromEntityToOutputDto($contractReserveFundContribution),
            $contractReserveFundContributions
        );
    }

    public function updateAsReceived(ContractReserveFundContribution $contractReserveFundContribution): void
    {
        $contractReserveFundContribution->setStatus(ContractReserveFundContributionStatus::RECEIVED->name);
        $contractReserveFundContribution->setReceivedat(new \DateTimeImmutable());
    }

    public function updateEntityAsTransferred(ContractTransaction $contractTransaction, ContractReserveFundContribution $contractReserveFundContribution): void
    {
        $contractReserveFundContribution->setContractTrasaction($contractTransaction);
        $contractReserveFundContribution->setTransferredAt(new \DateTimeImmutable());
        $contractReserveFundContribution->setStatus(ContractReserveFundContributionStatus::TRANSFERRED->name);
    }

    public function updateEntityAsFailed(ContractTransaction $contractTransaction, ContractReserveFundContribution $contractReserveFundContribution): void
    {
        $contractReserveFundContribution->setContractTrasaction($contractTransaction);
        $contractReserveFundContribution->setStatus(ContractReserveFundContributionStatus::FAILED->name);
    }
}
