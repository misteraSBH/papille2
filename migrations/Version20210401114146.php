<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401114146 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE beverage (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(10, 2) DEFAULT NULL, INDEX IDX_3D8CACBBB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dessert (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(10, 2) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_79291B96B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dish (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, INDEX IDX_957D8CB8B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_restaurant (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_CA38A6EDB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_restaurant_dish (menu_restaurant_id INT NOT NULL, dish_id INT NOT NULL, INDEX IDX_6B7132F8172425F8 (menu_restaurant_id), INDEX IDX_6B7132F8148EB0CB (dish_id), PRIMARY KEY(menu_restaurant_id, dish_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_restaurant_beverage (menu_restaurant_id INT NOT NULL, beverage_id INT NOT NULL, INDEX IDX_9FE8B78B172425F8 (menu_restaurant_id), INDEX IDX_9FE8B78B49F6E812 (beverage_id), PRIMARY KEY(menu_restaurant_id, beverage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_restaurant_dessert (menu_restaurant_id INT NOT NULL, dessert_id INT NOT NULL, INDEX IDX_7A4359B5172425F8 (menu_restaurant_id), INDEX IDX_7A4359B5745B52FD (dessert_id), PRIMARY KEY(menu_restaurant_id, dessert_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, opening INT DEFAULT NULL, INDEX IDX_EB95123FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE beverage ADD CONSTRAINT FK_3D8CACBBB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE dessert ADD CONSTRAINT FK_79291B96B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE dish ADD CONSTRAINT FK_957D8CB8B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE menu_restaurant ADD CONSTRAINT FK_CA38A6EDB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE menu_restaurant_dish ADD CONSTRAINT FK_6B7132F8172425F8 FOREIGN KEY (menu_restaurant_id) REFERENCES menu_restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant_dish ADD CONSTRAINT FK_6B7132F8148EB0CB FOREIGN KEY (dish_id) REFERENCES dish (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant_beverage ADD CONSTRAINT FK_9FE8B78B172425F8 FOREIGN KEY (menu_restaurant_id) REFERENCES menu_restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant_beverage ADD CONSTRAINT FK_9FE8B78B49F6E812 FOREIGN KEY (beverage_id) REFERENCES beverage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant_dessert ADD CONSTRAINT FK_7A4359B5172425F8 FOREIGN KEY (menu_restaurant_id) REFERENCES menu_restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant_dessert ADD CONSTRAINT FK_7A4359B5745B52FD FOREIGN KEY (dessert_id) REFERENCES dessert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_restaurant_beverage DROP FOREIGN KEY FK_9FE8B78B49F6E812');
        $this->addSql('ALTER TABLE menu_restaurant_dessert DROP FOREIGN KEY FK_7A4359B5745B52FD');
        $this->addSql('ALTER TABLE menu_restaurant_dish DROP FOREIGN KEY FK_6B7132F8148EB0CB');
        $this->addSql('ALTER TABLE menu_restaurant_dish DROP FOREIGN KEY FK_6B7132F8172425F8');
        $this->addSql('ALTER TABLE menu_restaurant_beverage DROP FOREIGN KEY FK_9FE8B78B172425F8');
        $this->addSql('ALTER TABLE menu_restaurant_dessert DROP FOREIGN KEY FK_7A4359B5172425F8');
        $this->addSql('ALTER TABLE beverage DROP FOREIGN KEY FK_3D8CACBBB1E7706E');
        $this->addSql('ALTER TABLE dessert DROP FOREIGN KEY FK_79291B96B1E7706E');
        $this->addSql('ALTER TABLE dish DROP FOREIGN KEY FK_957D8CB8B1E7706E');
        $this->addSql('ALTER TABLE menu_restaurant DROP FOREIGN KEY FK_CA38A6EDB1E7706E');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123FA76ED395');
        $this->addSql('DROP TABLE beverage');
        $this->addSql('DROP TABLE dessert');
        $this->addSql('DROP TABLE dish');
        $this->addSql('DROP TABLE menu_restaurant');
        $this->addSql('DROP TABLE menu_restaurant_dish');
        $this->addSql('DROP TABLE menu_restaurant_beverage');
        $this->addSql('DROP TABLE menu_restaurant_dessert');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE user');
    }
}
