<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211221060334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etape_arme (etape_id INT NOT NULL, arme_id INT NOT NULL, INDEX IDX_523C2F034A8CA2AD (etape_id), INDEX IDX_523C2F0321D9C0A (arme_id), PRIMARY KEY(etape_id, arme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etape_arme ADD CONSTRAINT FK_523C2F034A8CA2AD FOREIGN KEY (etape_id) REFERENCES etape (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etape_arme ADD CONSTRAINT FK_523C2F0321D9C0A FOREIGN KEY (arme_id) REFERENCES arme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DD21D9C0A');
        $this->addSql('DROP INDEX IDX_285F75DD21D9C0A ON etape');
        $this->addSql('ALTER TABLE etape DROP arme_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE etape_arme');
        $this->addSql('ALTER TABLE etape ADD arme_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DD21D9C0A FOREIGN KEY (arme_id) REFERENCES arme (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_285F75DD21D9C0A ON etape (arme_id)');
    }
}
