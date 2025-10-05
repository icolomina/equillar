<?php

namespace App\Persistence\Files;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FilesStorageInterface
{
    public function moveProjectFile(UploadedFile $file): string;
    public function moveProjectImage(UploadedFile $file): string;
}
