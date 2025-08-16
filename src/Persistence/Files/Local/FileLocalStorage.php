<?php

namespace App\Persistence\Files\Local;

use App\Persistence\Files\FilesStorageInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileLocalStorage implements FilesStorageInterface
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
