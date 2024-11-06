<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241106201903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account ADD enable TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE entry ADD account_id INT NOT NULL');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D709B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('CREATE INDEX IDX_2B219D709B6B5FBA ON entry (account_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account DROP enable');
        $this->addSql('ALTER TABLE entry DROP FOREIGN KEY FK_2B219D709B6B5FBA');
        $this->addSql('DROP INDEX IDX_2B219D709B6B5FBA ON entry');
        $this->addSql('ALTER TABLE entry DROP account_id');
    }
}
