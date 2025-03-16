<?php

namespace App\Application\UserContract\Transformer;

use App\Domain\UserContract\Service\ClaimableDateCalculator;
use App\Domain\UserContract\Service\InterestsCalculator;
use App\Entity\Investment\ContractInvestment;
use App\Entity\Investment\UserContractInvestment;
use App\Entity\User;
use App\Entity\UserWallet;
use App\Presentation\Contract\DTO\Input\CreateUserContractDtoInput;
use App\Presentation\UserContract\DTO\Output\UserContractDtoOutput;

readonly class UserContractEntityTransformer
{
    public function __construct(
        private ClaimableDateCalculator $claimableDateCalculator,
        private InterestsCalculator $interestsCalculator
    ){}

    public function fromEntityToOutputDto(UserContractInvestment $userContractInvestment): UserContractDtoOutput
    {

        $claimableDate = $this->claimableDateCalculator->getClaimableDate(null, $userContractInvestment->getContract()->getClaimMonths());
        
        return new UserContractDtoOutput(
            $userContractInvestment->getId(),
            $userContractInvestment->getContract()->getIssuer()->getName(),
            $userContractInvestment->getContract()->getLabel(),
            $userContractInvestment->getContract()->getAddress(),
            $userContractInvestment->getContract()->getToken()->getName() . ' - ' . $userContractInvestment->getContract()->getToken()->getCode(),
            $userContractInvestment->getContract()->getRate(),
            $userContractInvestment->getCreatedAt()->format('Y-m-d H:i'),
            $claimableDate,
            $userContractInvestment->getBalance(),
            $userContractInvestment->getInterests(),
            $userContractInvestment->getTotal(),
            $userContractInvestment->getHash()
        );
    }

    /**
     * @return UserContractInvestment[]
     */
    public function fromEntitiesToOutputDtos(array $userContracts): array
    {
        return array_map(
            fn(UserContractInvestment $userContract) => $this->fromEntityToOutputDto($userContract),
            $userContracts
        );
    }

    public function fromCreateUserContractInvestmentDtoToEntity(CreateUserContractDtoInput $createUserContractDtoInput, ContractInvestment $contract, UserWallet $userWallet): UserContractInvestment
    {
        $userContract = new UserContractInvestment();
        $userContract->setUsr($userWallet->getUsr());
        $userContract->setContract($contract);
        $userContract->setBalance($createUserContractDtoInput->deposited);
        $userContract->setHash($createUserContractDtoInput->hash);
        $userContract->setCreatedAt(new \DateTimeImmutable());
        $userContract->setUserWallet($userWallet);

        return $userContract;
    }
    
}
