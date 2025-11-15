<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Persistence\Files\Local;

use App\Persistence\Files\FilesStorageInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileLocalStorage implements FilesStorageInterface
{
    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly string $investmentFilesPath,
        private readonly string $investmentProjectImagesPath
    ) {
    }

    public function moveProjectFile(UploadedFile $file): string
    {
        $newFilename      = $this->generateUniqueFilename($file);

        try {
            $file->move($this->investmentFilesPath, $newFilename);
        } catch (FileException $e) {
            // Lanzamos error de Validacion
        }

        return $newFilename;
    }

    public function moveProjectImage(UploadedFile $file): string
    {
        $newFilename      = $this->generateUniqueFilename($file);

        try {
            $file->move($this->investmentProjectImagesPath, $newFilename);
        } catch (FileException $e) {
            // Lanzamos error de Validacion
        }

        return $newFilename;
    }

    private function generateUniqueFilename(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename     = $this->slugger->slug($originalFilename);
        return $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
    }
}
