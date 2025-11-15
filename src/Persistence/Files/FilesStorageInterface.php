<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Persistence\Files;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FilesStorageInterface
{
    public function moveProjectFile(UploadedFile $file): string;
    public function moveProjectImage(UploadedFile $file): string;
}
