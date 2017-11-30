<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Participant;
use Doctrine\ORM\EntityRepository;

class ParticipantRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getCouples(): array
    {
        /** @var Participant[] $participants */
        $participants = $this->findAll();

        if (count($participants) === 0) {
            return [];
        }

        shuffle($participants);
        $couples = [];

        foreach ($participants as $key => $sender) {
            $couples[] = [
                'sender' => $sender,
                'recipient' => $participants[$key + 1] ?? $participants[0],
            ];
        }

        return $couples;
    }
}
