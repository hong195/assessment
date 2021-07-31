<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20210731184441 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE assessments (id CHAR(36) NOT NULL COMMENT \'(DC2Type:assessment_id)\', analysis_id CHAR(36) DEFAULT NULL, criteria JSON DEFAULT NULL, reviewer_reviewer_id VARCHAR(255) DEFAULT NULL, reviewer_name VARCHAR(255) DEFAULT NULL, check_service_date DATETIME NOT NULL, check_amount INT NOT NULL, check_sale_conversion DOUBLE PRECISION NOT NULL, INDEX IDX_4BFCEC0A7941003F (analysis_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE criteria (id CHAR(36) NOT NULL COMMENT \'(DC2Type:criterion_id)\', name VARCHAR(255) NOT NULL, `order` INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employees (id CHAR(36) NOT NULL COMMENT \'(DC2Type:employee_id)\', pharmacy_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:pharmacy_id)\', birthdate DATETIME NOT NULL, name_first_name VARCHAR(255) NOT NULL, name_middle VARCHAR(255) DEFAULT NULL, name_last_name VARCHAR(255) NOT NULL, gender_gender VARCHAR(255) NOT NULL, INDEX IDX_BA82C3008A94ABE2 (pharmacy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE final_grades (id CHAR(36) NOT NULL, scored DOUBLE PRECISION DEFAULT NULL, total DOUBLE PRECISION DEFAULT NULL, employee_id CHAR(36) NOT NULL COMMENT \'(DC2Type:employee_id)\', month_date DATETIME NOT NULL, status_status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE options (id CHAR(36) NOT NULL, criterion_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:criterion_id)\', name VARCHAR(255) NOT NULL, value DOUBLE PRECISION DEFAULT NULL, INDEX IDX_D035FA8797766307 (criterion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pharmacies (id CHAR(36) NOT NULL COMMENT \'(DC2Type:pharmacy_id)\', email_email VARCHAR(255) NOT NULL, number_number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id CHAR(36) NOT NULL COMMENT \'(DC2Type:user_id)\', password VARCHAR(255) NOT NULL, name_first VARCHAR(255) NOT NULL, name_middle VARCHAR(255) DEFAULT NULL, name_last VARCHAR(255) NOT NULL, role_role VARCHAR(255) NOT NULL, login_login VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assessments ADD CONSTRAINT FK_4BFCEC0A7941003F FOREIGN KEY (analysis_id) REFERENCES final_grades (id)');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C3008A94ABE2 FOREIGN KEY (pharmacy_id) REFERENCES pharmacies (id)');
        $this->addSql('ALTER TABLE options ADD CONSTRAINT FK_D035FA8797766307 FOREIGN KEY (criterion_id) REFERENCES criteria (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE options DROP FOREIGN KEY FK_D035FA8797766307');
        $this->addSql('ALTER TABLE assessments DROP FOREIGN KEY FK_4BFCEC0A7941003F');
        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C3008A94ABE2');
        $this->addSql('DROP TABLE assessments');
        $this->addSql('DROP TABLE criteria');
        $this->addSql('DROP TABLE employees');
        $this->addSql('DROP TABLE final_grades');
        $this->addSql('DROP TABLE options');
        $this->addSql('DROP TABLE pharmacies');
        $this->addSql('DROP TABLE users');
    }
}
