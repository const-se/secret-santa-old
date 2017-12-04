<?php

namespace AppBundle\Migration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20171203231624 extends AbstractMigration
{
    /**
     * @inheritdoc
     */
    public function up(Schema $schema)
    {
        $this->addSql('
            ALTER TABLE participants
            ADD dative_firstname VARCHAR(255) DEFAULT NULL,
            ADD dative_lastname VARCHAR(255) DEFAULT NULL,
            ADD received TINYINT(1) NOT NULL DEFAULT \'0\'
        ');
    }

    /**
     * @inheritdoc
     */
    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE participants DROP dative_firstname, DROP dative_lastname, DROP received');
    }
}
