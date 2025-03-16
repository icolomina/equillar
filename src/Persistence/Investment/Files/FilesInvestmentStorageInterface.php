<?php

namespace App\Persistence\Investment\Files;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FilesInvestmentStorageInterface
{
    public function moveProjectFile(UploadedFile $file): string;
}
