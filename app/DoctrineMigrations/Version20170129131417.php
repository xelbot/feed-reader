<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170129131417 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE generators (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE feeds ADD generator_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE feeds DROP generator');
        $this->addSql('ALTER TABLE feeds ADD CONSTRAINT FK_5A29F52FCF158378 FOREIGN KEY (generator_id) REFERENCES generators (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5A29F52FCF158378 ON feeds (generator_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE feeds DROP CONSTRAINT FK_5A29F52FCF158378');
        $this->addSql('DROP TABLE generators');
        $this->addSql('DROP INDEX IDX_5A29F52FCF158378');
        $this->addSql('ALTER TABLE feeds ADD generator VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE feeds DROP generator_id');
    }
}
