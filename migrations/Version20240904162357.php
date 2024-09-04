<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240904162357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC0058702F506');
        $this->addSql('DROP INDEX UNIQ_E54BC0058702F506 ON sale');
        $this->addSql('ALTER TABLE sale CHANGE cars_id car_id INT NOT NULL');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E54BC005C3C6F69F ON sale (car_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC005C3C6F69F');
        $this->addSql('DROP INDEX UNIQ_E54BC005C3C6F69F ON sale');
        $this->addSql('ALTER TABLE sale CHANGE car_id cars_id INT NOT NULL');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC0058702F506 FOREIGN KEY (cars_id) REFERENCES car (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E54BC0058702F506 ON sale (cars_id)');
    }
}
