<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190331113011 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT fk_d22944588d93d649');
        $this->addSql('DROP INDEX idx_d22944588d93d649');
        $this->addSql('ALTER TABLE feedback ADD feedback_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE feedback DROP "user"');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944583637F336 FOREIGN KEY (feedback_user_id) REFERENCES usr (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D22944583637F336 ON feedback (feedback_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT FK_D22944583637F336');
        $this->addSql('DROP INDEX IDX_D22944583637F336');
        $this->addSql('ALTER TABLE feedback ADD "user" VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE feedback DROP feedback_user_id');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT fk_d22944588d93d649 FOREIGN KEY ("user") REFERENCES usr (email) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d22944588d93d649 ON feedback ("user")');
    }
}
