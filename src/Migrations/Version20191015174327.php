<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191015174327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE citation_categorie (citation_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_4D3DD5C9500A8AB7 (citation_id), INDEX IDX_4D3DD5C9BCF5E72D (categorie_id), PRIMARY KEY(citation_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE citation_categorie ADD CONSTRAINT FK_4D3DD5C9500A8AB7 FOREIGN KEY (citation_id) REFERENCES citation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE citation_categorie ADD CONSTRAINT FK_4D3DD5C9BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE citation_categorie DROP FOREIGN KEY FK_4D3DD5C9BCF5E72D');
        $this->addSql('DROP TABLE citation_categorie');
        $this->addSql('DROP TABLE categorie');
    }
}
