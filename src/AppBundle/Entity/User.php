<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass = "AppBundle\Repository\UserRepository")
 * @ORM\Table(name = "users")
 */
class User implements UserInterface
{
    use IdentifiedEntityTrait;

    /**
     * @ORM\Column(name = "password", type = "string")
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(name = "salt", type = "string")
     * @var string
     */
    protected $salt;

    /**
     * @ORM\Column(name = "username", type = "string", length = 30, unique = true)
     * @var string
     */
    protected $username;

    /**
     * @inheritdoc
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @inheritdoc
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @inheritdoc
     */
    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }

    /**
     * @inheritdoc
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @inheritdoc
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $salt
     * @return User
     */
    public function setSalt(string $salt): User
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }
}
