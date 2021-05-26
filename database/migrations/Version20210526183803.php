<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20210526183803 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE criteria (id CHAR(36) NOT NULL COMMENT \'(DC2Type:criterion_id)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE options (id CHAR(36) NOT NULL, criterion_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:criterion_id)\', name VARCHAR(255) NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_D035FA8797766307 (criterion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE options ADD CONSTRAINT FK_D035FA8797766307 FOREIGN KEY (criterion_id) REFERENCES criteria (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE options DROP FOREIGN KEY FK_D035FA8797766307');
        $this->addSql('DROP TABLE criteria');
        $this->addSql('DROP TABLE options');
    }
}
