<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221115000456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demandes_type_machine (demandes_id INT NOT NULL, type_machine_id INT NOT NULL, INDEX IDX_30696A9DF49DCC2D (demandes_id), INDEX IDX_30696A9DEBB48D03 (type_machine_id), PRIMARY KEY(demandes_id, type_machine_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demandes_type_machine ADD CONSTRAINT FK_30696A9DF49DCC2D FOREIGN KEY (demandes_id) REFERENCES demandes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demandes_type_machine ADD CONSTRAINT FK_30696A9DEBB48D03 FOREIGN KEY (type_machine_id) REFERENCES type_machine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demandes ADD statut_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demandes ADD CONSTRAINT FK_BD940CBBF6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('CREATE INDEX IDX_BD940CBBF6203804 ON demandes (statut_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demandes_type_machine DROP FOREIGN KEY FK_30696A9DF49DCC2D');
        $this->addSql('ALTER TABLE demandes_type_machine DROP FOREIGN KEY FK_30696A9DEBB48D03');
        $this->addSql('DROP TABLE demandes_type_machine');
        $this->addSql('ALTER TABLE demandes DROP FOREIGN KEY FK_BD940CBBF6203804');
        $this->addSql('DROP INDEX IDX_BD940CBBF6203804 ON demandes');
        $this->addSql('ALTER TABLE demandes DROP statut_id');
    }
}
