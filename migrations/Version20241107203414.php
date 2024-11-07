<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107203414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE periodic_entry ADD account_id INT NOT NULL');
        $this->addSql('ALTER TABLE periodic_entry ADD CONSTRAINT FK_8FA2A6EB9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('CREATE INDEX IDX_8FA2A6EB9B6B5FBA ON periodic_entry (account_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE periodic_entry DROP FOREIGN KEY FK_8FA2A6EB9B6B5FBA');
        $this->addSql('DROP INDEX IDX_8FA2A6EB9B6B5FBA ON periodic_entry');
        $this->addSql('ALTER TABLE periodic_entry DROP account_id');
    }
}
