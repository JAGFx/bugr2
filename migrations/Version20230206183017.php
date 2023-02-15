<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230206183017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE periodic_entry DROP period, DROP type, DROP historic');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE periodic_entry ADD period VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', ADD type VARCHAR(255) NOT NULL, ADD historic LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\'');
    }
}
