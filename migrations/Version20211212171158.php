<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211212171158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE arme (id INT AUTO_INCREMENT NOT NULL, sigle VARCHAR(10) DEFAULT NULL, designation VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, date_heure_creation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etape (id INT AUTO_INCREMENT NOT NULL, evenement_id INT NOT NULL, arme_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, date_heure_debut DATETIME NOT NULL, date_heure_creation DATETIME NOT NULL, tarif VARCHAR(100) DEFAULT NULL, nb_inscriptions_max INT DEFAULT NULL, INDEX IDX_285F75DDFD02F13 (evenement_id), INDEX IDX_285F75DD21D9C0A (arme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, lieu_destination_id INT NOT NULL, nom VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, date_heure_debut DATETIME NOT NULL, date_heure_limite_inscription DATETIME NOT NULL, nb_inscriptions_max INT DEFAULT NULL, etat VARCHAR(50) NOT NULL, tarif VARCHAR(100) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, date_heure_creation DATETIME NOT NULL, INDEX IDX_B26681EE637792C (lieu_destination_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription_etape (id INT AUTO_INCREMENT NOT NULL, membre_id INT NOT NULL, etape_id INT NOT NULL, validation TINYINT(1) NOT NULL, date_heure_inscription DATETIME NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, INDEX IDX_7A1621906A99F74A (membre_id), INDEX IDX_7A1621904A8CA2AD (etape_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieu (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) DEFAULT NULL, rue VARCHAR(50) NOT NULL, rue2 VARCHAR(50) DEFAULT NULL, code_postal VARCHAR(5) NOT NULL, ville VARCHAR(50) NOT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, club TINYINT(1) NOT NULL, list TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) DEFAULT NULL, num_licence VARCHAR(10) DEFAULT NULL, date_naissance DATE DEFAULT NULL, tel_mobile VARCHAR(15) DEFAULT NULL, lateralite VARCHAR(10) DEFAULT NULL, statut_licence TINYINT(1) DEFAULT NULL, sexe VARCHAR(5) DEFAULT NULL, categorie_age VARCHAR(50) NOT NULL, type_licence VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_F6B4FB29E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_article (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, titre VARCHAR(50) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_37DA19EB7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_trajet (id INT AUTO_INCREMENT NOT NULL, trajet_id INT NOT NULL, membre_id INT NOT NULL, validation TINYINT(1) NOT NULL, date_heure_reservation DATETIME NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, INDEX IDX_63AAE017D12A823 (trajet_id), INDEX IDX_63AAE0176A99F74A (membre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trajet (id INT AUTO_INCREMENT NOT NULL, lieu_depart_id INT NOT NULL, evenement_id INT NOT NULL, organisateur_id INT NOT NULL, titre VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, date_heure_depart DATETIME NOT NULL, nb_places INT NOT NULL, type_voiture VARCHAR(30) DEFAULT NULL, couleur_voiture VARCHAR(30) DEFAULT NULL, date_heure_creation DATETIME NOT NULL, INDEX IDX_2B5BA98CC16565FC (lieu_depart_id), INDEX IDX_2B5BA98CFD02F13 (evenement_id), INDEX IDX_2B5BA98CD936B2FA (organisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DDFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DD21D9C0A FOREIGN KEY (arme_id) REFERENCES arme (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EE637792C FOREIGN KEY (lieu_destination_id) REFERENCES lieu (id)');
        $this->addSql('ALTER TABLE inscription_etape ADD CONSTRAINT FK_7A1621906A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE inscription_etape ADD CONSTRAINT FK_7A1621904A8CA2AD FOREIGN KEY (etape_id) REFERENCES etape (id)');
        $this->addSql('ALTER TABLE photo_article ADD CONSTRAINT FK_37DA19EB7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE reservation_trajet ADD CONSTRAINT FK_63AAE017D12A823 FOREIGN KEY (trajet_id) REFERENCES trajet (id)');
        $this->addSql('ALTER TABLE reservation_trajet ADD CONSTRAINT FK_63AAE0176A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98CC16565FC FOREIGN KEY (lieu_depart_id) REFERENCES lieu (id)');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98CFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98CD936B2FA FOREIGN KEY (organisateur_id) REFERENCES membre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DD21D9C0A');
        $this->addSql('ALTER TABLE photo_article DROP FOREIGN KEY FK_37DA19EB7294869C');
        $this->addSql('ALTER TABLE inscription_etape DROP FOREIGN KEY FK_7A1621904A8CA2AD');
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DDFD02F13');
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98CFD02F13');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EE637792C');
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98CC16565FC');
        $this->addSql('ALTER TABLE inscription_etape DROP FOREIGN KEY FK_7A1621906A99F74A');
        $this->addSql('ALTER TABLE reservation_trajet DROP FOREIGN KEY FK_63AAE0176A99F74A');
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98CD936B2FA');
        $this->addSql('ALTER TABLE reservation_trajet DROP FOREIGN KEY FK_63AAE017D12A823');
        $this->addSql('DROP TABLE arme');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE etape');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE inscription_etape');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE membre');
        $this->addSql('DROP TABLE photo_article');
        $this->addSql('DROP TABLE reservation_trajet');
        $this->addSql('DROP TABLE trajet');
    }
}
