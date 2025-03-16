<?php

namespace App\Application\Contract\Transformer;

use App\Domain\Contract\ContractStatus;
use App\Entity\Contract;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;
use App\Domain\DateFormats;
use App\Entity\Investment\ContractInvestment;
use App\Entity\Investment\ContractInvestmentBalance;
use App\Entity\Token;
use App\Entity\User;
use App\Presentation\Contract\DTO\Input\CreateContractDto;
use App\Presentation\Contract\DTO\Output\ContractInvestmentBalanceDtoOutput;
use Soneso\StellarSDK\Crypto\StrKey;

class ContractEntityTransformer
{
    public function fromEntityToOutputDto(ContractInvestment $contract, ?ContractInvestmentBalance $contractBalance = null): ContractDtoOutput
    {
        $contractBalanceDtoOutput = ($contractBalance) 
            ? new ContractInvestmentBalanceDtoOutput($contractBalance->getAvailable(), $contractBalance->getReserveFund(), $contractBalance->getComission())
            : new ContractInvestmentBalanceDtoOutput(0, 0, 0)
        ;

        $contractAddress = $contract->getAddress() ? StrKey::encodeContractIdHex($contract->getAddress()) : null;

        return new ContractDtoOutput(
            $contract->getId(),
            $contractAddress,
            $contract->getToken()->getName(),
            $contract->getToken()->getDecimals(),
            $contract->getToken()->getCode(),
            $contract->getRate(),
            $contract->getCreatedAt()->format(DateFormats::OUTPUT_DATE_FORMAT->value),
            $contract->getInitializedAt()?->format(DateFormats::OUTPUT_DATE_FORMAT->value),
            $contract->isInitialized(),
            $contract->getIssuer()->getName(),
            $contract->getClaimMonths(),
            $contract->getLabel(),
            $contract->isFundsReached(),
            $contract->getDescription(),
            $contract->getShortDescription(),
            $contractBalanceDtoOutput,
            $contract->getStatus(),
            $contract->getGoal()
        );
    }

    public function fromEntitiesToOutputDtos(iterable $elements): iterable
    {
        return array_map(
            fn(Contract $contract) => $this->fromEntityToOutputDto($contract),
            $elements
        );
    }

    public function fromCreateInvestmentContractInputDtoToEntity(CreateContractDto $createContractDto, User $user, Token $token, string $filename): Contract
    {
        $contract = new ContractInvestment();
        $contract->setCreatedAt(new \DateTimeImmutable());
        $contract->setIssuer($user);
        $contract->setRate((float)$createContractDto->rate);
        $contract->setToken($token);
        $contract->setInitialized(false);
        $contract->setClaimMonths((int)$createContractDto->claimMonths);
        $contract->setLabel($createContractDto->label);
        $contract->setDescription($createContractDto->description);
        $contract->setStatus(ContractStatus::REVIEWING->name);
        $contract->setFilename($filename);
        $contract->setFundsReached(false);
        $contract->setGoal((float)$createContractDto->goal);
        $contract->setShortDescription($createContractDto->shortDescription);

        return $contract;
    }
}
