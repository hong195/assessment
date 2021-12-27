<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20210912110826 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sale_managers_pharmacies (sale_manager_id INT NOT NULL, pharmacy_id CHAR(36) NOT NULL COMMENT \'(DC2Type:pharmacy_id)\', INDEX IDX_BBB83A68856AD027 (sale_manager_id), INDEX IDX_BBB83A688A94ABE2 (pharmacy_id), PRIMARY KEY(sale_manager_id, pharmacy_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sale_managers_pharmacies ADD CONSTRAINT FK_BBB83A68856AD027 FOREIGN KEY (sale_manager_id) REFERENCES sale_managers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sale_managers_pharmacies ADD CONSTRAINT FK_BBB83A688A94ABE2 FOREIGN KEY (pharmacy_id) REFERENCES pharmacies (id) ON DELETE CASCADE');
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

        $this->addSql('DROP TABLE sale_managers_pharmacies');
        $this->addSql('ALTER TABLE assessments CHANGE analysis_id analysis_id CHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE criteria criteria JSON DEFAULT NULL, CHANGE reviewer_reviewer_id reviewer_reviewer_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE reviewer_name reviewer_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE final_grades CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE options CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
