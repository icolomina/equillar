<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Entity\Contract\Contract;
use App\Entity\User;
use App\Persistence\PersistorInterface;
use App\Persistence\Token\TokenStorageInterface;
use App\Presentation\Contract\DTO\Input\CreateContractDto;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;

class ModifyContractService
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly ContractEntityTransformer $contractEntityTransformer,
        private readonly PersistorInterface $persistor,
    ) {
    }

    public function modifyContract(Contract $contract, CreateContractDto $createContractDto, User $user): ContractDtoOutput
    {
        $token = $this->tokenStorage->getOneByCode($createContractDto->token);

        $this->contractEntityTransformer->updateContractWithNewData($contract, $createContractDto, $user, $token);
        $this->persistor->persistAndFlush($contract);

        return $this->contractEntityTransformer->fromEntityToOutputDto($contract);
    }
}
