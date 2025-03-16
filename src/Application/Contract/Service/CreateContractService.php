<?php

namespace App\Application\Investment\Contract\Service;

use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Presentation\Contract\DTO\Input\CreateContractDto;
use App\Entity\User;
use App\Persistence\Investment\Contract\ContractInvestmentStorageInterface;
use App\Persistence\Investment\Files\FilesInvestmentStorageInterface;
use App\Persistence\Token\TokenStorageInterface;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateContractService
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly ContractInvestmentStorageInterface $contractInvestmentStorage,
        private readonly ContractEntityTransformer $contractEntityTransformer,
        private readonly FilesInvestmentStorageInterface $filesInvestmentStorage
    ){}

    public function createContract(CreateContractDto $createContractDto, UploadedFile $file, User $user): ContractDtoOutput
    {
        $token = $this->tokenStorage->getOneByCode($createContractDto->token);
        $filename = $this->filesInvestmentStorage->moveProjectFile($file);
        $contract = $this->contractEntityTransformer->fromCreateInvestmentContractInputDtoToEntity($createContractDto, $user, $token, $filename);
        $this->contractInvestmentStorage->saveContract($contract);

        return $this->contractEntityTransformer->fromEntityToOutputDto($contract);
    }
}
