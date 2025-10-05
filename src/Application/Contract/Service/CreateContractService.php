<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Entity\User;
use App\Persistence\Files\FilesStorageInterface;
use App\Persistence\PersistorInterface;
use App\Persistence\Token\TokenStorageInterface;
use App\Presentation\Contract\DTO\Input\CreateContractDto;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateContractService
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly ContractEntityTransformer $contractEntityTransformer,
        private readonly FilesStorageInterface $filesStorage,
        private readonly PersistorInterface $persistor,
    ) {
    }

    public function createContract(CreateContractDto $createContractDto, UploadedFile $file, UploadedFile $image, User $user): ContractDtoOutput
    {
        $token     = $this->tokenStorage->getOneByCode($createContractDto->token);
        $filename  = $this->filesStorage->moveProjectFile($file);
        $imageName = $this->filesStorage->moveProjectImage($image);
        $contract  = $this->contractEntityTransformer->fromCreateInvestmentContractInputDtoToEntity($createContractDto, $user, $token, $filename, $imageName);

        $this->persistor->persistAndFlush($contract);

        return $this->contractEntityTransformer->fromEntityToOutputDto($contract);
    }
}
