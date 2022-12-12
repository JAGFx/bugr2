<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221211155903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_73F2F77B2EF83F9C ON budget');
        $this->addSql('ALTER TABLE budget DROP shortcut');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE budget ADD shortcut VARCHAR(3) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_73F2F77B2EF83F9C ON budget (shortcut)');
    }
}
