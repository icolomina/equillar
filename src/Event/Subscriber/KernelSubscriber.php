<?php

namespace App\Event\Subscriber;

use Firebase\JWT\ExpiredException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class KernelSubscriber implements EventSubscriberInterface{

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event): void 
    {
        $exception = $event->getThrowable();
        
        if($exception->getPrevious() instanceof ValidationFailedException) {
            $event->setResponse(new JsonResponse($this->getErrors($exception->getPrevious()), 422));
        }

        if($exception instanceof ValidationFailedException) {
            $event->setResponse(new JsonResponse($this->getErrors($exception), 422));
        }

        if($exception instanceof ExpiredException) {
            $event->setThrowable(new AuthenticationException($exception->getMessage()));
        } 
    }

    private function getErrors(ValidationFailedException $exception): array 
    {
        $errors = [];
        $violations = $exception->getViolations();
        foreach($violations as $violation) {
            $errors[] = ['label' => $violation->getPropertyPath(), 'msg' => $violation->getMessage()];
        }

        return $errors;
    }
}