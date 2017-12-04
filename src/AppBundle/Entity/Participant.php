<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass = "AppBundle\Repository\ParticipantRepository")
 * @ORM\Table(name = "participants")
 * @DoctrineAssert\UniqueEntity(
 *     groups = {"registration"},
 *     fields = {"email"},
 *     message = "Такой адрес электронной почты уже зарегистрирован"
 * )
 */
class Participant
{
    use IdentifiedEntityTrait;

    /**
     * @ORM\Column(name = "dative_firstname", type = "string", nullable = true)
     * @var string|null
     */
    protected $dativeFirstname = '';

    /**
     * @ORM\Column(name = "dative_lastname", type = "string", nullable = true)
     * @var string|null
     */
    protected $dativeLastname = '';

    /**
     * @ORM\Column(name = "email", type = "string", length = 100, unique = true)
     * @Assert\NotBlank(groups = {"registration"}, message = "Адрес электронной почты не может быть пустым")
     * @Assert\Email(groups = {"registration"}, message = "Некорректный адрес электронной почты")
     * @Groups({"registration"})
     * @var string
     */
    protected $email = '';

    /**
     * @ORM\ManyToOne(targetEntity = "Participant")
     * @ORM\JoinColumn(name = "id_except_participant", referencedColumnName = "id", nullable = true)
     * @var Participant|null
     */
    protected $exceptParticipant;

    /**
     * @ORM\Column(name = "firstname", type = "string")
     * @Assert\NotBlank(groups = {"registration"}, message = "Имя не может быть пустым")
     * @Assert\Regex(groups = {"registration"}, pattern = "~^[А-Яа-яЁё]{2,50}$~iu", message = "Некорректное имя")
     * @Groups({"registration"})
     * @var string
     */
    protected $firstname = '';

    /**
     * @ORM\Column(name = "lastname", type = "string")
     * @Assert\NotBlank(groups = {"registration"}, message = "Фамилия не может быть пустой")
     * @Assert\Regex(groups = {"registration"}, pattern = "~^[А-Яа-яЁё]{2,50}$~iu", message = "Некорректная фамилия")
     * @Groups({"registration"})
     * @var string
     */
    protected $lastname = '';

    /**
     * @ORM\Column(name = "received", type = "boolean")
     * @var bool
     */
    protected $received;

    /**
     * @ORM\ManyToOne(targetEntity = "Participant")
     * @ORM\JoinColumn(name = "id_recipient", referencedColumnName = "id", nullable = true)
     * @var Participant|null
     */
    protected $recipient;

    public function __construct()
    {
        $this->received = false;
    }

    /**
     * @return string|null
     */
    public function getDativeFirstname()
    {
        return $this->dativeFirstname;
    }

    /**
     * @return string|null
     */
    public function getDativeLastname()
    {
        return $this->dativeLastname;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return Participant|null
     */
    public function getExceptParticipant()
    {
        return $this->exceptParticipant;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return Participant|null
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @return bool
     */
    public function isReceived()
    {
        return $this->received;
    }

    /**
     * @param string|null $dativeFirstname
     * @return $this
     */
    public function setDativeFirstname(string $dativeFirstname = null)
    {
        $this->dativeFirstname = $dativeFirstname;

        return $this;
    }

    /**
     * @param string|null $dativeLastname
     * @return $this
     */
    public function setDativeLastname(string $dativeLastname = null)
    {
        $this->dativeLastname = $dativeLastname;

        return $this;
    }

    /**
     * @param string $email
     * @return Participant
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param Participant|null $exceptParticipant
     * @return Participant
     */
    public function setExceptParticipant(Participant $exceptParticipant = null)
    {
        $this->exceptParticipant = $exceptParticipant;

        return $this;
    }

    /**
     * @param string $firstname
     * @return Participant
     */
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @param string $lastname
     * @return Participant
     */
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @param bool $received
     * @return $this
     */
    public function setReceived(bool $received)
    {
        $this->received = $received;

        return $this;
    }

    /**
     * @param Participant|null $recipient
     * @return Participant|null
     */
    public function setRecipient(Participant $recipient = null)
    {
        $this->recipient = $recipient;

        return $this;
    }
}
