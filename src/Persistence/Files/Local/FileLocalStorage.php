<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
