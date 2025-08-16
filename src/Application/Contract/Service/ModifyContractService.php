<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Entity\Contract\Contract;
use App\Entity\User;
use App\Persistence\Files\FilesStorageInterface;
use App\Persistence\PersistorInterface;
use App\Persistence\Token\TokenStorageInterface;
use App\Presentation\Contract\DTO\Input\CreateContractDto;
use App\Presentation\Contract\DTO\Output\ContractDtoOutput;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ModifyContractService
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly ContractEntityTransformer $contractEntityTransformer,
        private readonly FilesStorageInterface $filesStorage,
        private readonly PersistorInterface $persistor
    ){}

    public function modifyContract(Contract $contract, CreateContractDto $createContractDto, mixed $file, User $user): ContractDtoOutput
    {
        $token    = $this->tokenStorage->getOneByCode($createContractDto->token);

        $filename = null;
        if($file instanceof UploadedFile){
            $filename = $this->filesStorage->moveProjectFile($file);
        }
        
        
        $this->contractEntityTransformer->updateContractWithNewData($contract, $createContractDto, $user, $token, $filename);
        $this->persistor->persistAndFlush($contract);

        return $this->contractEntityTransformer->fromEntityToOutputDto($contract);
    }
}
