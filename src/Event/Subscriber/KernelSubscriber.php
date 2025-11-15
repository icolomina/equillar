<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Event\Subscriber;

use App\Domain\Contract\Exception\BlockchainNetworkException;
use App\Domain\Contract\Exception\ContractExecutionFailedException;
use Firebase\JWT\ExpiredException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class KernelSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception->getPrevious() instanceof ValidationFailedException) {
            $event->setResponse(new JsonResponse($this->getErrors($exception->getPrevious()), 400));
            return;
        }

        if ($exception instanceof ValidationFailedException) {
            $event->setResponse(new JsonResponse($this->getErrors($exception), 400));
            return;
        }

        if ($exception instanceof ExpiredException) {
            $event->setThrowable(new AuthenticationException($exception->getMessage()));
            return;
        }

        if ($exception instanceof ContractExecutionFailedException) {
            $event->setResponse(new JsonResponse([
                'error' => 'CONTRACT_EXECUTION_FAILED',
                'message' => $exception->getMessage(),
                'contract_id' => $exception->getContractId(),
                'transaction_hash' => $exception->getTransactionHash(),
            ], 422));
            return;
        }
    }

    private function getErrors(ValidationFailedException $exception): array
    {
        $errors = [];
        $violations = $exception->getViolations();
        foreach ($violations as $violation) {
            $errors[] = ['label' => $violation->getPropertyPath(), 'msg' => $violation->getMessage()];
        }

        return $errors;
    }
}
