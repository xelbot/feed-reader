<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170126221817 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('COMMENT ON COLUMN users.last_login IS \'(DC2Type:carbondatetime)\'');
        $this->addSql('COMMENT ON COLUMN users.password_requested_at IS \'(DC2Type:carbondatetime)\'');
        $this->addSql('COMMENT ON COLUMN users.created_at IS \'(DC2Type:carbondatetime)\'');
        $this->addSql('CREATE TABLE feeds (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, description TEXT NOT NULL, generator VARCHAR(255) DEFAULT NULL, discr INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE feeds_rss (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE feeds_atom (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE feeds_rss ADD CONSTRAINT FK_ACE75F37BF396750 FOREIGN KEY (id) REFERENCES feeds (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feeds_atom ADD CONSTRAINT FK_F8840391BF396750 FOREIGN KEY (id) REFERENCES feeds (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE feeds_rss DROP CONSTRAINT FK_ACE75F37BF396750');
        $this->addSql('ALTER TABLE feeds_atom DROP CONSTRAINT FK_F8840391BF396750');
        $this->addSql('DROP TABLE feeds_atom');
        $this->addSql('DROP TABLE feeds_rss');
        $this->addSql('DROP TABLE feeds');
    }
}
