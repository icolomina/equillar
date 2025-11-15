<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\UserContract\Service;

use App\Application\User\Transformer\UserWalletEntityTransformer;
use App\Application\UserContract\Transformer\UserContractEntityTransformer;
use App\Entity\User;
use App\Persistence\Contract\ContractStorageInterface;
use App\Persistence\PersistorInterface;
use App\Persistence\User\UserWalletStorageInterface;
use App\Presentation\Contract\DTO\Input\CreateUserContractDtoInput;
use App\Presentation\UserContract\DTO\Output\UserContractDtoOutput;
use Soneso\StellarSDK\Crypto\StrKey;
use App\Application\UserContract\Service\ProcessUserContractService;

class CreateUserContractService
{

    public function __construct(
        private readonly ContractStorageInterface $contractStorage,
        private readonly UserContractEntityTransformer $userContractEntityTransformer,
        private readonly UserWalletStorageInterface $userWalletStorage,
        private readonly UserWalletEntityTransformer $userWalletEntityTransformer,
        private readonly ProcessUserContractService $processUserContractService,
        private readonly PersistorInterface $persistor
    ) {}

    public function createUserContract(CreateUserContractDtoInput $createUserContractDtoInput, User $user): UserContractDtoOutput
    {
        $contract     = $this->contractStorage->getContractByAddress(StrKey::decodeContractIdHex($createUserContractDtoInput->contractAddress));
        $userWallet   = $this->userWalletStorage->getWalletByAddress($createUserContractDtoInput->fromAddress);

        if(!$userWallet) {
            $userWallet = $this->userWalletEntityTransformer->fromUserAndAddressToUserWalletEntity($user, $createUserContractDtoInput->fromAddress);
            $this->persistor->persist($userWallet);
        }

        $userContract = $this->userContractEntityTransformer->fromCreateUserContractInvestmentDtoToEntity($createUserContractDtoInput, $contract, $userWallet);
        $this->persistor->persist($userContract);
        $this->persistor->flush();

        $this->processUserContractService->processUserContractTransaction($userContract);
        return $this->userContractEntityTransformer->fromEntityToOutputDto($userContract);
    }
}
