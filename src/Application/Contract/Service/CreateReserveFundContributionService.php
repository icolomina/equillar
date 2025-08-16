<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractReserveFundContributionTransformer;
use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Entity\Contract\Contract;
use App\Entity\User;
use App\Persistence\PersistorInterface;
use App\Presentation\Contract\DTO\Input\CreateContractReserveFundContributionDtoInput;
use App\Presentation\Contract\DTO\Output\ContractReserveFundContributionCreatedDtoOutput;
use Soneso\StellarSDK\Crypto\StrKey;

class CreateReserveFundContributionService
{
    public function __construct(
        private readonly ContractReserveFundContributionTransformer $contractReserveFundContributionTransformer,
        private readonly StellarAccountLoader $stellarAccountLoader,
        private readonly PersistorInterface $persistor
    ){}

    public function createReserveFundContribution(Contract $contract, CreateContractReserveFundContributionDtoInput $createContractReserveFundContributionDtoInput, User $user): ContractReserveFundContributionCreatedDtoOutput
    {
        $contractReserveFundContribution = $this->contractReserveFundContributionTransformer->fromAmountAndUserToEntity($user, $contract, $createContractReserveFundContributionDtoInput->amount);
        $this->persistor->persistAndFlush($contractReserveFundContribution);

        $systemStellarAddress = $this->stellarAccountLoader->getKeyPair()->getAccountId();
        return $this->contractReserveFundContributionTransformer->fromEntityToContractReserveFundContributionCreatedDtoOutput($contractReserveFundContribution, $systemStellarAddress);
    }
}
