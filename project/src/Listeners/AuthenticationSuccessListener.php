<?php

namespace App\Listeners;

use DateInterval;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AuthenticationSuccessListener
{
    private string $jwtTokenTTL;

    private bool $cookieSecure = false;

    public function __construct(string $jwtTokenTTL)
    {
        $this->jwtTokenTTL = $jwtTokenTTL;
    }

    public function onAuthenticationSuccess(
        AuthenticationSuccessEvent $event
    ): JWTAuthenticationSuccessResponse {
        /** @var JWTAuthenticationSuccessResponse $response */
        $response = $event->getResponse();
        $response->setData([
            'success' => 'You login successfully'
        ]);
        $data = $event->getData();

        $event->setData([
            'code' => $event->getResponse()->getStatusCode(),
            'message' => 'You login successfully. Token was written in your cookies.',
            'data' => $data,
        ]);

        $response->headers->setCookie(new Cookie('BEARER', $data['token'], (
        new \DateTime())
            ->add(new DateInterval('PT' . $this->jwtTokenTTL . 'S')),
            '/',
            null,
            $this->cookieSecure)
        );

        return $response;
    }
}