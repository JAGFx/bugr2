<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221211103116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE budget DROP anniversary_year_type');
        $this->addSql('ALTER TABLE entry DROP date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE budget ADD anniversary_year_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE entry ADD date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
