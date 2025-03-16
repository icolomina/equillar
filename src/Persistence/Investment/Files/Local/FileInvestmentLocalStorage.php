<?php

namespace App\Persistence\Investment\Files\Local;

use App\Persistence\Investment\Files\FilesInvestmentStorageInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileInvestmentLocalStorage implements FilesInvestmentStorageInterface
{

    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly string $investmentFilesPath
    ){}

    public function moveProjectFile(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->investmentFilesPath, $newFilename);
        } catch (FileException $e) {
            // Lanzamos error de Validacion
        }

        return $newFilename;
    }
}
