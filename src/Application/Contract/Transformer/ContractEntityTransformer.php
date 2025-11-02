<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace App\Application\Contract\Transformer;

use App\Application\Token\Transformer\TokenEntityTransformer;
use App\Domain\Contract\ContractReturnType;
use App\Domain\Contract\ContractStatus;
use App\Domain\Contract\Service\ContractImageUrlGenerator;
use App\Domain\DateFormats;
use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractBalance;
use App\Entity\ContractTransaction;
use App\Entity\Token;
use App\Entity\User;
use App\Presentation\Contract\DTO\Input\CreateContractDto;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;
use Soneso\StellarSDK\Crypto\StrKey;

class ContractEntityTransformer
{
    public function __construct(
        private readonly ContractBalanceEntityTransformer $contractBalanceEntityTransformer,
        private readonly TokenEntityTransformer $tokenEntityTransformer,
        private readonly ContractImageUrlGenerator $contractImageUrlGenerator
    ) {
    }

    public function fromEntityToOutputDto(Contract $contract, ?ContractBalance $contractBalance = null): ContractDtoOutput
    {
        $returnType = ContractReturnType::tryFrom($contract->getReturnType())?->getReadableName();
        $contractAddress = ($contract->getAddress()) ? StrKey::encodeContractIdHex($contract->getAddress()) : null;

        $contractBalanceDtoOutput = $this->contractBalanceEntityTransformer->fromEntityToOutputDto($contractBalance, $contract, true);
        $tokenContractDtoOutput = $this->tokenEntityTransformer->fromEntityToContractTokenOutputDto($contract->getToken());

        return new ContractDtoOutput(
            (string) $contract->getId(),
            $contractAddress,
            $tokenContractDtoOutput,
            $contract->getRate(),
            $contract->getCreatedAt()->format(DateFormats::OUTPUT_DATE_FORMAT->value),
            $contract->getInitializedAt()?->format(DateFormats::OUTPUT_DATE_FORMAT->value),
            $contract->getApprovedAt()?->format(DateFormats::OUTPUT_DATE_FORMAT->value),
            $contract->getLastPausedAt()?->format(DateFormats::OUTPUT_DATE_FORMAT->value),
            $contract->getLastResumedAt()?->format(DateFormats::OUTPUT_DATE_FORMAT->value),
            $contract->isInitialized(),
            $contract->getIssuer()->getName(),
            $contract->getClaimMonths(),
            $contract->getLabel(),
            $contract->isFundsReached(),
            $contract->getDescription(),
            $contract->getShortDescription(),
            $this->contractImageUrlGenerator->getImageUrl($contract),
            $contractBalanceDtoOutput,
            $contract->getStatus(),
            $contract->getGoal(),
            $contract->getMinPerInvestment(),
            $returnType,
            $contract->getReturnMonths(),
            $contract->getProjectAddress()
        );
    }

    public function fromEntitiesToOutputDtos(iterable $elements): iterable
    {
        return array_map(
            fn (Contract $contract) => $this->fromEntityToOutputDto($contract),
            $elements
        );
    }

    public function fromCreateInvestmentContractInputDtoToEntity(CreateContractDto $createContractDto, User $user, Token $token, string $filename, string $imageName): Contract
    {
        $contract = new Contract();
        $contract->setCreatedAt(new \DateTimeImmutable());
        $contract->setIssuer($user);
        $contract->setRate((float) $createContractDto->rate);
        $contract->setToken($token);
        $contract->setInitialized(false);
        $contract->setClaimMonths((int) $createContractDto->claimMonths);
        $contract->setLabel($createContractDto->label);
        $contract->setDescription($createContractDto->description);
        $contract->setStatus(ContractStatus::REVIEWING->name);
        $contract->setFilename($filename);
        $contract->setImageName($imageName);
        $contract->setFundsReached(false);
        $contract->setGoal((float) $createContractDto->goal);
        $contract->setShortDescription($createContractDto->shortDescription);
        $contract->setMinPerInvestment((float) $createContractDto->minPerInvestment);
        $contract->setReturnMonths((int) $createContractDto->returnMonths);
        $contract->setReturnType((int) $createContractDto->returnType);
        $contract->setProjectAddress($createContractDto->projectAddress);

        return $contract;
    }

    public function updateContractWithNewData(Contract $contract, CreateContractDto $createContractDto, User $user, Token $token): void
    {
        $contract->setIssuer($user);
        $contract->setRate((float) $createContractDto->rate);
        $contract->setToken($token);
        $contract->setClaimMonths((int) $createContractDto->claimMonths);
        $contract->setLabel($createContractDto->label);
        $contract->setDescription($createContractDto->description);
        $contract->setGoal((float) $createContractDto->goal);
        $contract->setShortDescription($createContractDto->shortDescription);
        $contract->setMinPerInvestment((float) $createContractDto->minPerInvestment);
        $contract->setReturnMonths((int) $createContractDto->returnMonths);
        $contract->setReturnType((int) $createContractDto->returnType);
        $contract->setProjectAddress($createContractDto->projectAddress);
    }

    public function updateContractAsBlocked(Contract $contract): void
    {
        $contract->setStatus(ContractStatus::BLOCKED->name);
    }

    public function updateContractAsFundsReached(Contract $contract): void
    {
        $contract->setStatus(ContractStatus::FUNDS_REACHED->name);
    }

    public function updateContractAsActive(Contract $contract, string $address, ContractTransaction $contractTransaction): void
    {
        $contract->setStatus(ContractStatus::ACTIVE->name);
        $contract->setAddress($address);
        $contract->setInitialized(true);
        $contract->setInitializedAt(new \DateTimeImmutable());
        $contract->setContractTransaction($contractTransaction);
    }

    public function updateContractAsDeploymentFailed(Contract $contract, ContractTransaction $contractTransaction): void
    {
        $contract->setStatus(ContractStatus::DEPLOYMENT_FAILED->name);
        $contract->setContractTransaction($contractTransaction);
    }

    public function updateContractAsApproved(Contract $contract): void
    {
        $contract->setStatus(ContractStatus::APPROVED->name);
        $contract->setApprovedAt(new \DateTimeImmutable());
    }

    public function updateContractAsRejected(Contract $contract): void
    {
        $contract->setStatus(ContractStatus::REJECTED->name);
    }
}
