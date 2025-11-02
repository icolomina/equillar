<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
