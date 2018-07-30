<?php

namespace Knojector\SteamAuthenticationBundle\Security\Authentication\Token;

use Knojector\SteamAuthenticationBundle\User\AbstractSteamUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author Knojector <dev@404-labs.xyz>
 */
class SteamUserToken implements TokenInterface
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * @var bool
     */
    private $authenticated;

    /**
     * @var AbstractSteamUser
     */
    private $user;

    /**
     * @var string
     */
    private $username;

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->user->getSteamId();
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->user->getRoles();
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials()
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthenticated()
    {
       return $this->authenticated;
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthenticated($isAuthenticated)
    {
        $this->authenticated = $isAuthenticated;
    }

    public function eraseCredentials()
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            'attributes' => $this->attributes,
            'authenticated' => $this->authenticated,
            'user' => $this->user,
            'username' => $this->username,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->attributes = $data['attributes'];
        $this->authenticated = $data['authenticated'];
        $this->user = $data['user'];
        $this->username = $data['username'];
    }


}