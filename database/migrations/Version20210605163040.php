<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20210605163040 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE employees (id CHAR(36) NOT NULL COMMENT \'(DC2Type:employee_id)\', pharmacy_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:pharmacy_id)\', birthdate DATETIME NOT NULL, name_first_name VARCHAR(255) NOT NULL, name_middle VARCHAR(255) DEFAULT NULL, name_last_name VARCHAR(255) NOT NULL, gender_gender VARCHAR(255) NOT NULL, INDEX IDX_BA82C3008A94ABE2 (pharmacy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C3008A94ABE2 FOREIGN KEY (pharmacy_id) REFERENCES pharmacies (id)');
        $this->addSql('ALTER TABLE assessments CHANGE analysis_id analysis_id CHAR(36) DEFAULT NULL, CHANGE criteria criteria JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE efficiency_analyses CHANGE id id CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE options CHANGE id id CHAR(36) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE employees');
        $this->addSql('ALTER TABLE assessments CHANGE analysis_id analysis_id CHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE criteria criteria JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE efficiency_analyses CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE options CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
