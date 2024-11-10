<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241110163340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE history_budget (id INT AUTO_INCREMENT NOT NULL, budget_id INT DEFAULT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', relative_progress DOUBLE PRECISION NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_A07DBB8836ABA6B8 (budget_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE history_budget ADD CONSTRAINT FK_A07DBB8836ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE history_budget DROP FOREIGN KEY FK_A07DBB8836ABA6B8');
        $this->addSql('DROP TABLE history_budget');
    }
}
