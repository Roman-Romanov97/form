<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190331111223 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE usr_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE feedback_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE usr (id INT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(81) NOT NULL, user_ip VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX usr_email_uindex ON usr (email)');
        $this->addSql('CREATE TABLE feedback (id INT NOT NULL, "user" VARCHAR(255) DEFAULT NULL, date_contact TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, text_feedback TEXT NOT NULL, email TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D22944588D93D649 ON feedback ("user")');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944588D93D649 FOREIGN KEY ("user") REFERENCES usr (email) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT FK_D22944588D93D649');
        $this->addSql('DROP SEQUENCE usr_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE feedback_id_seq CASCADE');
        $this->addSql('DROP TABLE usr');
        $this->addSql('DROP TABLE feedback');
    }
}
