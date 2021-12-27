<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20211219130548 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assessments CHANGE analysis_id analysis_id CHAR(36) DEFAULT NULL, CHANGE criteria criteria JSON DEFAULT NULL, CHANGE reviewer_reviewer_id reviewer_reviewer_id VARCHAR(255) DEFAULT NULL, CHANGE reviewer_name reviewer_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE final_grades CHANGE id id CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE options CHANGE id id CHAR(36) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assessments CHANGE analysis_id analysis_id CHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE criteria criteria JSON DEFAULT NULL, CHANGE reviewer_reviewer_id reviewer_reviewer_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE reviewer_name reviewer_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE final_grades CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE options CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
