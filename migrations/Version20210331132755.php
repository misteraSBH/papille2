<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331132755 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beverage ADD menu_restaurant_id INT NOT NULL');
        $this->addSql('ALTER TABLE beverage ADD CONSTRAINT FK_3D8CACBB172425F8 FOREIGN KEY (menu_restaurant_id) REFERENCES menu_restaurant (id)');
        $this->addSql('CREATE INDEX IDX_3D8CACBB172425F8 ON beverage (menu_restaurant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beverage DROP FOREIGN KEY FK_3D8CACBB172425F8');
        $this->addSql('DROP INDEX IDX_3D8CACBB172425F8 ON beverage');
        $this->addSql('ALTER TABLE beverage DROP menu_restaurant_id');
    }
}
