<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass = "AppBundle\Repository\ParticipantRepository")
 * @ORM\Table(name = "participants")
 */
class Participant
{
    use IdentifiedEntityTrait;

    /**
     * @ORM\Column(name = "email", type = "string", length = 100, unique = true)
     * @Assert\NotBlank(groups = {"registration"}, message = "Адрес электронной почты не может быть пустым")
     * @Assert\Email(groups = {"registration"}, message = "Некорректный адрес электронной почты")
     * @var string
     */
    protected $email = '';

    /**
     * @ORM\Column(name = "firstname", type = "string")
     * @Assert\NotBlank(groups = {"registration"}, message = "Имя не может быть пустым")
     * @Assert\Regex(groups = {"registration"}, pattern = "~^[А-Яа-яЁё]{2,50}$~iu", message = "Некорректное имя")
     * @var string
     */
    protected $firstname = '';

    /**
     * @ORM\Column(name = "lastname", type = "string")
     * @Assert\NotBlank(groups = {"registration"}, message = "Фамилия не может быть пустой")
     * @Assert\Regex(groups = {"registration"}, pattern = "~^[А-Яа-яЁё]{2,50}$~iu", message = "Некорректная фамилия")
     * @var string
     */
    protected $lastname = '';

    /**
     * @ORM\ManyToOne(targetEntity = "Participant")
     * @ORM\JoinColumn(name = "id_recipient", referencedColumnName = "id", nullable = true)
     * @var Participant
     */
    protected $recipient;

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
     * @return Participant
     */
    public function getRecipient(): Participant
    {
        return $this->recipient;
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

    /**
     * @param Participant|null $recipient
     * @return Participant|null
     */
    public function setRecipient(?Participant $recipient): ?Participant
    {
        $this->recipient = $recipient;

        return $this;
    }
}
