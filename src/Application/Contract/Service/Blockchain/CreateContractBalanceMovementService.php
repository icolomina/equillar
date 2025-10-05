<?php

namespace App\Application\Contract\Service\Blockchain;

use App\Presentation\Contract\DTO\Input\ContractMoveBalanceToTheReserveInputDto;
use App\Application\Contract\Transformer\ContractBalanceMovementTransformer;
use App\Entity\Contract\Contract;
use App\Entity\User;
use App\Persistence\PersistorInterface;
use App\Presentation\Contract\DTO\Output\ContractBalanceMovementCreatedDtoOutput;

class CreateContractBalanceMovementService
{
    public function __construct(
        private readonly ContractBalanceMovementTransformer $contractBalanceMovementTransformer,
        private readonly PersistorInterface $persistor
    ){}

    public function createBalanceMovementFromAvailableToReserve(Contract $contract, User $user, ContractMoveBalanceToTheReserveInputDto $contractMoveBalanceToTheReserveInputDto): ContractBalanceMovementCreatedDtoOutput
    {
        $contractBalanceMovement = $this->contractBalanceMovementTransformer->fromAvailableToReserveMovementInputDtoToEntity($contract, $user, $contractMoveBalanceToTheReserveInputDto);
        $this->persistor->persistAndFlush($contractBalanceMovement);

        return $this->contractBalanceMovementTransformer->fromEntityToCreatedMovementOutputDto($contractBalanceMovement);
    }
}
