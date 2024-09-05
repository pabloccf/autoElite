<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240821181013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car ADD car_model_id INT NOT NULL');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DF64382E3 FOREIGN KEY (car_model_id) REFERENCES car_model (id)');
        $this->addSql('CREATE INDEX IDX_773DE69DF64382E3 ON car (car_model_id)');
        $this->addSql('ALTER TABLE car_model ADD car_brand_id INT NOT NULL');
        $this->addSql('ALTER TABLE car_model ADD CONSTRAINT FK_83EF70ECBC3E50C FOREIGN KEY (car_brand_id) REFERENCES car_brand (id)');
        $this->addSql('CREATE INDEX IDX_83EF70ECBC3E50C ON car_model (car_brand_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DF64382E3');
        $this->addSql('DROP INDEX IDX_773DE69DF64382E3 ON car');
        $this->addSql('ALTER TABLE car DROP car_model_id');
        $this->addSql('ALTER TABLE car_model DROP FOREIGN KEY FK_83EF70ECBC3E50C');
        $this->addSql('DROP INDEX IDX_83EF70ECBC3E50C ON car_model');
        $this->addSql('ALTER TABLE car_model DROP car_brand_id');
    }
}
