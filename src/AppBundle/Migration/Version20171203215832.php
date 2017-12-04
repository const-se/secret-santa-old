<?php

namespace AppBundle\Migration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20171203215832 extends AbstractMigration
{
    /**
     * @inheritdoc
     */
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE participants ADD id_except_participant INT UNSIGNED DEFAULT NULL');
        $this->addSql('
            ALTER TABLE participants
            ADD CONSTRAINT FK_7169709266492D72 FOREIGN KEY (id_except_participant) REFERENCES participants (id)
        ');
        $this->addSql('CREATE INDEX IDX_7169709266492D72 ON participants (id_except_participant)');
    }

    /**
     * @inheritdoc
     */
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY FK_7169709266492D72');
        $this->addSql('DROP INDEX IDX_7169709266492D72 ON participants');
        $this->addSql('ALTER TABLE participants DROP id_except_participant');
    }
}
