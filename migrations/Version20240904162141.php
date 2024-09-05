<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240904162141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sale ADD user_id INT NOT NULL, ADD cars_id INT NOT NULL, CHANGE date_sale sale_date DATE NOT NULL');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC0058702F506 FOREIGN KEY (cars_id) REFERENCES car (id)');
        $this->addSql('CREATE INDEX IDX_E54BC005A76ED395 ON sale (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E54BC0058702F506 ON sale (cars_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC005A76ED395');
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC0058702F506');
        $this->addSql('DROP INDEX IDX_E54BC005A76ED395 ON sale');
        $this->addSql('DROP INDEX UNIQ_E54BC0058702F506 ON sale');
        $this->addSql('ALTER TABLE sale DROP user_id, DROP cars_id, CHANGE sale_date date_sale DATE NOT NULL');
    }
}
