<?php

namespace App\Application\UserContract\Service;

use App\Application\User\Transformer\UserWalletEntityTransformer;
use App\Application\UserContract\Transformer\UserContractEntityTransformer;
use App\Entity\User;
use App\Message\CheckUserInvestmentTransactionMessage;
use App\Persistence\Investment\Contract\ContractInvestmentStorageInterface;
use App\Persistence\PersistorInterface;
use App\Persistence\User\UserWalletStorageInterface;
use App\Presentation\Contract\DTO\Input\CreateUserContractDtoInput;
use App\Presentation\UserContract\DTO\Output\UserContractDtoOutput;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateUserContractService
{

    public function __construct(
        private readonly ContractInvestmentStorageInterface $contractInvestmentStorage,
        private readonly UserContractEntityTransformer $userContractInvestmentEntityTransformer,
        private readonly UserWalletStorageInterface $userWalletStorage,
        private readonly UserWalletEntityTransformer $userWalletEntityTransformer,
        private readonly MessageBusInterface $bus,
        private readonly PersistorInterface $persistor
    ) {}

    public function createUserContract(CreateUserContractDtoInput $createUserContractDtoInput, User $user): UserContractDtoOutput
    {
        $contract     = $this->contractInvestmentStorage->getContractByAddress($createUserContractDtoInput->contractAddress);
        $userWallet   = $this->userWalletStorage->getWalletByAddress($createUserContractDtoInput->fromAddress);

        if(!$userWallet) {
            $userWallet = $this->userWalletEntityTransformer->fromUserAndAddressToUserWalletEntity($user, $createUserContractDtoInput->fromAddress);
            $this->persistor->persist($userWallet);
        }

        $userContract = $this->userContractInvestmentEntityTransformer->fromCreateUserContractInvestmentDtoToEntity($createUserContractDtoInput, $contract, $userWallet);
        $this->persistor->persist($userContract);
        $this->persistor->flush();

        $this->bus->dispatch(new CheckUserInvestmentTransactionMessage($userContract->getId()));
        return $this->userContractInvestmentEntityTransformer->fromEntityToOutputDto($userContract);
    }
}
