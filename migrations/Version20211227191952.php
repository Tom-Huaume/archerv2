<?php
//
//declare(strict_types=1);
//
//namespace DoctrineMigrations;
//
//use Doctrine\DBAL\Schema\Schema;
//use Doctrine\Migrations\AbstractMigration;
//
///**
// * Auto-generated Migration: Please modify to your needs!
// */
//final class Version20211227191952 extends AbstractMigration
//{
//    public function getDescription(): string
//    {
//        return '';
//    }
//
//    public function up(Schema $schema): void
//    {
//        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('DROP INDEX UNIQ_182073798776B952 ON arme');
//        $this->addSql('DROP INDEX UNIQ_182073798947610D ON arme');
//    }
//
//    public function down(Schema $schema): void
//    {
//        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE UNIQUE INDEX UNIQ_182073798776B952 ON arme (sigle)');
//        $this->addSql('CREATE UNIQUE INDEX UNIQ_182073798947610D ON arme (designation)');
//    }
//}
