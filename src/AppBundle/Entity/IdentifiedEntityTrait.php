<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait IdentifiedEntityTrait
{
    /**
     * @ORM\Column(name = "id", type = "integer", options = {"unsigned": true})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
