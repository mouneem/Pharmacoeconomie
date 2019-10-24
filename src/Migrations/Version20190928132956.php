<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190928132956 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, survey_id INT NOT NULL, patient_id INT DEFAULT NULL, answer LONGTEXT DEFAULT NULL, date VARCHAR(255) DEFAULT NULL, is_active VARCHAR(255) NOT NULL, INDEX IDX_DADD4A25B3FE509D (survey_id), INDEX IDX_DADD4A256B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey (id INT AUTO_INCREMENT NOT NULL, titre LONGTEXT DEFAULT NULL, link LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, numerotel VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, naissance_jour VARCHAR(255) DEFAULT NULL, naissance_mois VARCHAR(255) DEFAULT NULL, naissance_annee VARCHAR(255) DEFAULT NULL, biotherapie_actuelle VARCHAR(255) DEFAULT NULL, num_inclu VARCHAR(255) DEFAULT NULL, sexe VARCHAR(255) DEFAULT NULL, date_inclusion VARCHAR(255) DEFAULT NULL, poids VARCHAR(255) DEFAULT NULL, taille VARCHAR(255) DEFAULT NULL, niv_etude VARCHAR(255) DEFAULT NULL, situation_mat VARCHAR(255) DEFAULT NULL, nb_enf VARCHAR(255) DEFAULT NULL, profession VARCHAR(255) DEFAULT NULL, vilee VARCHAR(255) DEFAULT NULL, rural_urbain VARCHAR(255) DEFAULT NULL, salarie VARCHAR(255) DEFAULT NULL, revenue_des_menages VARCHAR(255) DEFAULT NULL, num_entre VARCHAR(255) DEFAULT NULL, nature_maladie VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A256B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25B3FE509D');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A256B899279');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE survey');
        $this->addSql('DROP TABLE patient');
    }
}
