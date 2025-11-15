<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\Contract\Service;

use App\Entity\Contract\Contract;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;

class GetContractDocumentService
{
    public function __construct(
        private readonly KernelInterface $kernel,
    ) {
    }

    public function generateDownloadResponseFromContract(Contract $contract): BinaryFileResponse
    {
        $contractFilename = $contract->getFilename();
        $contractFilePath = $this->kernel->getProjectDir().'/data/investment/projects/'.$contractFilename;

        if (!file_exists($contractFilePath)) {
            throw new NotFoundHttpException('Document not found');
        }

        $response = new BinaryFileResponse($contractFilePath);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $contractFilename);
        $response->headers->set('Content-Length', (string) filesize($contractFilePath));

        return $response;
    }
}
