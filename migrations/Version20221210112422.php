<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221210112422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE budget (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, shortcut VARCHAR(3) NOT NULL, amount DOUBLE PRECISION NOT NULL, anniversary_year_type VARCHAR(255) DEFAULT NULL, historic LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', enable TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_73F2F77B2EF83F9C (shortcut), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entry (id INT AUTO_INCREMENT NOT NULL, budget_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', amount DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2B219D7036ABA6B8 (budget_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE periodic_entry (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, period VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', type VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, execution_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', historic LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE periodic_entry_budget (periodic_entry_id INT NOT NULL, budget_id INT NOT NULL, INDEX IDX_FBFE7A7D3732355 (periodic_entry_id), INDEX IDX_FBFE7A7D36ABA6B8 (budget_id), PRIMARY KEY(periodic_entry_id, budget_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D7036ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id)');
        $this->addSql('ALTER TABLE periodic_entry_budget ADD CONSTRAINT FK_FBFE7A7D3732355 FOREIGN KEY (periodic_entry_id) REFERENCES periodic_entry (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE periodic_entry_budget ADD CONSTRAINT FK_FBFE7A7D36ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entry DROP FOREIGN KEY FK_2B219D7036ABA6B8');
        $this->addSql('ALTER TABLE periodic_entry_budget DROP FOREIGN KEY FK_FBFE7A7D3732355');
        $this->addSql('ALTER TABLE periodic_entry_budget DROP FOREIGN KEY FK_FBFE7A7D36ABA6B8');
        $this->addSql('DROP TABLE budget');
        $this->addSql('DROP TABLE entry');
        $this->addSql('DROP TABLE periodic_entry');
        $this->addSql('DROP TABLE periodic_entry_budget');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
