<?php

namespace App\Application\Contract\Transformer;

use App\Domain\Token\Service\TokenNormalizer;
use App\Presentation\Contract\DTO\Input\ContractMoveBalanceToTheReserveInputDto;
use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractBalanceMovement;
use App\Entity\ContractTransaction;
use App\Entity\User;
use App\Presentation\Contract\DTO\Output\ContractBalanceMovementCreatedDtoOutput;
use App\Presentation\Contract\DTO\Output\ContractBalanceMovementDtoOutput;
use App\Presentation\Contract\DTO\Output\ContractMoveBalanceMovementDtoOutput;

class ContractBalanceMovementTransformer
{

    public function fromAvailableToReserveMovementInputDtoToEntity(Contract $contract, User $user, ContractMoveBalanceToTheReserveInputDto $contractMoveBalanceToTheReserveInputDto): ContractBalanceMovement
    {
        $contractBalanceMovement = new ContractBalanceMovement();
        $contractBalanceMovement->setContract($contract);
        $contractBalanceMovement->setSegmentFrom('available');
        $contractBalanceMovement->setSegmentTo('reserve_fund');
        $contractBalanceMovement->setAmount((float)$contractMoveBalanceToTheReserveInputDto->amount);
        $contractBalanceMovement->setCreatedAt(new \DateTimeImmutable());
        $contractBalanceMovement->setRequestedBy($user);
        $contractBalanceMovement->setStatus('CREATED');

        return $contractBalanceMovement;
    }

    public function fromEntityToCreatedMovementOutputDto(ContractBalanceMovement $contractBalanceMovement): ContractBalanceMovementCreatedDtoOutput
    {
        return new ContractBalanceMovementCreatedDtoOutput(
            $contractBalanceMovement->getSegmentFrom(),
            $contractBalanceMovement->getSegmentTo(),
            $contractBalanceMovement->getStatus(),
            $contractBalanceMovement->getAmount()
        );
    }

    public function fromEntityToOutputDto(ContractBalanceMovement $contractBalanceMovement): ContractBalanceMovementDtoOutput
    {
        return new ContractBalanceMovementDtoOutput(
            $contractBalanceMovement->getId(),
            $contractBalanceMovement->getContract()->getLabel(),
            $contractBalanceMovement->getAmount(),
            $contractBalanceMovement->getSegmentFrom(),
            $contractBalanceMovement->getSegmentTo(),
            $contractBalanceMovement->getCreatedAt()->format('Y-m-d H:i'),
            $contractBalanceMovement->getMovedAt()?->format('Y-m-d H:i'),
            $contractBalanceMovement->getStatus()
        );
    }

    /**
     * @param ContractBalanceMovement[] $entities
     * @return ContractBalanceMovementDtoOutput[]
     */
    public function fromEntitiesToOutputDtos(array $entities): array
    {
        return array_map(
            fn(ContractBalanceMovement $contractBalanceMovement) => $this->fromEntityToOutputDto($contractBalanceMovement),
            $entities
        );
    }

    public function fromEntityToMovedToTheReserveFundOutputDto(ContractBalanceMovement $contractBalanceMovement): ContractMoveBalanceMovementDtoOutput
    {
        return new ContractMoveBalanceMovementDtoOutput($contractBalanceMovement->getStatus());
    }

    public function updateContractBalanceMovementAsMoved(ContractBalanceMovement $contractBalanceMovement, ContractTransaction $contractTransaction): void
    {
        $contractBalanceMovement->setStatus('MOVED');
        $contractBalanceMovement->setMovedAt(new \DateTimeImmutable());
        $contractBalanceMovement->setContractTransaction($contractTransaction);
    }

    public function updateContractBalanceMovementAsFailed(ContractBalanceMovement $contractBalanceMovement, ContractTransaction $contractTransaction): void
    {
        $contractBalanceMovement->setStatus('FAILED');
        $contractBalanceMovement->setContractTransaction($contractTransaction);
    }
}
