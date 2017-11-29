<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass = "AppBundle\Repository\ParticipantRepository")
 * @ORM\Table(name = "participants")
 */
class Participant
{
    use IdentifiedEntityTrait;

    /**
     * @ORM\Column(name = "email", type = "string", length = 100, unique = true)
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(name = "firstname", type = "string")
     * @var string
     */
    protected $firstname;

    /**
     * @ORM\Column(name = "lastname", type = "string")
     * @var string
     */
    protected $lastname;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $email
     * @return Participant
     */
    public function setEmail(string $email): Participant
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $firstname
     * @return Participant
     */
    public function setFirstname(string $firstname): Participant
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @param string $lastname
     * @return Participant
     */
    public function setLastname(string $lastname): Participant
    {
        $this->lastname = $lastname;

        return $this;
    }
}
