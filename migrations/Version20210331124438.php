<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331124438 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_restaurant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_restaurant_dish (menu_restaurant_id INT NOT NULL, dish_id INT NOT NULL, INDEX IDX_6B7132F8172425F8 (menu_restaurant_id), INDEX IDX_6B7132F8148EB0CB (dish_id), PRIMARY KEY(menu_restaurant_id, dish_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_restaurant_dish ADD CONSTRAINT FK_6B7132F8172425F8 FOREIGN KEY (menu_restaurant_id) REFERENCES menu_restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant_dish ADD CONSTRAINT FK_6B7132F8148EB0CB FOREIGN KEY (dish_id) REFERENCES dish (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_restaurant_dish DROP FOREIGN KEY FK_6B7132F8172425F8');
        $this->addSql('DROP TABLE menu_restaurant');
        $this->addSql('DROP TABLE menu_restaurant_dish');
    }
}
