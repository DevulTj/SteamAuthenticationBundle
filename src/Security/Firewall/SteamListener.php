<?php

namespace Knojector\SteamAuthenticationBundle\Security\Firewall;

use Knojector\SteamAuthenticationBundle\Security\Authentication\Token\SteamUserToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * @author Knojector <dev@404-labs.xyz>
 */
class SteamListener implements ListenerInterface
{
    /**
     * @var AuthenticationManagerInterface
     */
    private $authenticationManager;

    /**
     * @var string
     */
    private $loginRedirect;

    /**
     * @var string
     */
    private $loginRoute;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param AuthenticationManagerInterface $authenticationManager
     * @param RouterInterface                $router
     * @param string                         $loginRedirect
     * @param string                         $loginRoute
     * @param TokenStorageInterface          $tokenStorage
     */
    public function __construct(
        AuthenticationManagerInterface $authenticationManager,
        RouterInterface $router,
        string $loginRedirect,
        string $loginRoute,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->authenticationManager = $authenticationManager;
        $this->router = $router;
        $this->loginRedirect = $loginRedirect;
        $this->loginRoute = $loginRoute;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $returnTo = $request->query->get('openid_return_to');

        if ($returnTo !== $this->router->generate($this->loginRoute, [], UrlGeneratorInterface::ABSOLUTE_URL)) {
            return;
        }

        $claimedId = str_replace('https://steamcommunity.com/openid/id/', '', $request->query->get('openid_claimed_id'));

        $token = new SteamUserToken();
        $token->setUsername($claimedId);

        $authToken = $this->authenticationManager->authenticate($token);
        $this->tokenStorage->setToken($authToken);


        $event->setResponse(new RedirectResponse(
            $this->router->generate($this->loginRedirect)
        ));
    }

}
