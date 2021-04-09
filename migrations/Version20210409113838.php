<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409113838 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE beverage (id INT NOT NULL, restaurant_id INT DEFAULT NULL, INDEX IDX_3D8CACBBB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_item (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, cart_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_F0FE25274584665A (product_id), INDEX IDX_F0FE25271AD5CDBF (cart_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, order_number INT DEFAULT NULL, INDEX IDX_64C19C1727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dessert (id INT NOT NULL, restaurant_id INT DEFAULT NULL, INDEX IDX_79291B96B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dish (id INT NOT NULL, restaurant_id INT DEFAULT NULL, category_id INT DEFAULT NULL, INDEX IDX_957D8CB8B1E7706E (restaurant_id), INDEX IDX_957D8CB812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dish_side_dish (dish_id INT NOT NULL, side_dish_id INT NOT NULL, INDEX IDX_1340E9DE148EB0CB (dish_id), INDEX IDX_1340E9DEC884D3E8 (side_dish_id), PRIMARY KEY(dish_id, side_dish_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_restaurant (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, visible TINYINT(1) NOT NULL, INDEX IDX_CA38A6EDB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_restaurant_dish (menu_restaurant_id INT NOT NULL, dish_id INT NOT NULL, INDEX IDX_6B7132F8172425F8 (menu_restaurant_id), INDEX IDX_6B7132F8148EB0CB (dish_id), PRIMARY KEY(menu_restaurant_id, dish_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_restaurant_beverage (menu_restaurant_id INT NOT NULL, beverage_id INT NOT NULL, INDEX IDX_9FE8B78B172425F8 (menu_restaurant_id), INDEX IDX_9FE8B78B49F6E812 (beverage_id), PRIMARY KEY(menu_restaurant_id, beverage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_restaurant_dessert (menu_restaurant_id INT NOT NULL, dessert_id INT NOT NULL, INDEX IDX_7A4359B5172425F8 (menu_restaurant_id), INDEX IDX_7A4359B5745B52FD (dessert_id), PRIMARY KEY(menu_restaurant_id, dessert_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_slip (id INT AUTO_INCREMENT NOT NULL, purchase_id INT NOT NULL, INDEX IDX_B0C879EF558FBEB9 (purchase_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, description VARCHAR(255) DEFAULT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase (id INT AUTO_INCREMENT NOT NULL, id_purchase VARCHAR(255) NOT NULL, amount NUMERIC(10, 2) NOT NULL, payment_method INT DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_item (id INT AUTO_INCREMENT NOT NULL, ref_product_id INT NOT NULL, purchase_id INT NOT NULL, order_slip_id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, quantity INT NOT NULL, INDEX IDX_6FA8ED7D43418944 (ref_product_id), INDEX IDX_6FA8ED7D558FBEB9 (purchase_id), INDEX IDX_6FA8ED7DAFBDDA20 (order_slip_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, opening INT DEFAULT NULL, INDEX IDX_EB95123FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE side_dish (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, INDEX IDX_C939AD8FB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE beverage ADD CONSTRAINT FK_3D8CACBBB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE beverage ADD CONSTRAINT FK_3D8CACBBBF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE dessert ADD CONSTRAINT FK_79291B96B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE dessert ADD CONSTRAINT FK_79291B96BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dish ADD CONSTRAINT FK_957D8CB8B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE dish ADD CONSTRAINT FK_957D8CB812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE dish ADD CONSTRAINT FK_957D8CB8BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dish_side_dish ADD CONSTRAINT FK_1340E9DE148EB0CB FOREIGN KEY (dish_id) REFERENCES dish (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dish_side_dish ADD CONSTRAINT FK_1340E9DEC884D3E8 FOREIGN KEY (side_dish_id) REFERENCES side_dish (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant ADD CONSTRAINT FK_CA38A6EDB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE menu_restaurant_dish ADD CONSTRAINT FK_6B7132F8172425F8 FOREIGN KEY (menu_restaurant_id) REFERENCES menu_restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant_dish ADD CONSTRAINT FK_6B7132F8148EB0CB FOREIGN KEY (dish_id) REFERENCES dish (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant_beverage ADD CONSTRAINT FK_9FE8B78B172425F8 FOREIGN KEY (menu_restaurant_id) REFERENCES menu_restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant_beverage ADD CONSTRAINT FK_9FE8B78B49F6E812 FOREIGN KEY (beverage_id) REFERENCES beverage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant_dessert ADD CONSTRAINT FK_7A4359B5172425F8 FOREIGN KEY (menu_restaurant_id) REFERENCES menu_restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant_dessert ADD CONSTRAINT FK_7A4359B5745B52FD FOREIGN KEY (dessert_id) REFERENCES dessert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_slip ADD CONSTRAINT FK_B0C879EF558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id)');
        $this->addSql('ALTER TABLE purchase_item ADD CONSTRAINT FK_6FA8ED7D43418944 FOREIGN KEY (ref_product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE purchase_item ADD CONSTRAINT FK_6FA8ED7D558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id)');
        $this->addSql('ALTER TABLE purchase_item ADD CONSTRAINT FK_6FA8ED7DAFBDDA20 FOREIGN KEY (order_slip_id) REFERENCES order_slip (id)');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE side_dish ADD CONSTRAINT FK_C939AD8FB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_restaurant_beverage DROP FOREIGN KEY FK_9FE8B78B49F6E812');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD5CDBF');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE dish DROP FOREIGN KEY FK_957D8CB812469DE2');
        $this->addSql('ALTER TABLE menu_restaurant_dessert DROP FOREIGN KEY FK_7A4359B5745B52FD');
        $this->addSql('ALTER TABLE dish_side_dish DROP FOREIGN KEY FK_1340E9DE148EB0CB');
        $this->addSql('ALTER TABLE menu_restaurant_dish DROP FOREIGN KEY FK_6B7132F8148EB0CB');
        $this->addSql('ALTER TABLE menu_restaurant_dish DROP FOREIGN KEY FK_6B7132F8172425F8');
        $this->addSql('ALTER TABLE menu_restaurant_beverage DROP FOREIGN KEY FK_9FE8B78B172425F8');
        $this->addSql('ALTER TABLE menu_restaurant_dessert DROP FOREIGN KEY FK_7A4359B5172425F8');
        $this->addSql('ALTER TABLE purchase_item DROP FOREIGN KEY FK_6FA8ED7DAFBDDA20');
        $this->addSql('ALTER TABLE beverage DROP FOREIGN KEY FK_3D8CACBBBF396750');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25274584665A');
        $this->addSql('ALTER TABLE dessert DROP FOREIGN KEY FK_79291B96BF396750');
        $this->addSql('ALTER TABLE dish DROP FOREIGN KEY FK_957D8CB8BF396750');
        $this->addSql('ALTER TABLE purchase_item DROP FOREIGN KEY FK_6FA8ED7D43418944');
        $this->addSql('ALTER TABLE order_slip DROP FOREIGN KEY FK_B0C879EF558FBEB9');
        $this->addSql('ALTER TABLE purchase_item DROP FOREIGN KEY FK_6FA8ED7D558FBEB9');
        $this->addSql('ALTER TABLE beverage DROP FOREIGN KEY FK_3D8CACBBB1E7706E');
        $this->addSql('ALTER TABLE dessert DROP FOREIGN KEY FK_79291B96B1E7706E');
        $this->addSql('ALTER TABLE dish DROP FOREIGN KEY FK_957D8CB8B1E7706E');
        $this->addSql('ALTER TABLE menu_restaurant DROP FOREIGN KEY FK_CA38A6EDB1E7706E');
        $this->addSql('ALTER TABLE side_dish DROP FOREIGN KEY FK_C939AD8FB1E7706E');
        $this->addSql('ALTER TABLE dish_side_dish DROP FOREIGN KEY FK_1340E9DEC884D3E8');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123FA76ED395');
        $this->addSql('DROP TABLE beverage');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE dessert');
        $this->addSql('DROP TABLE dish');
        $this->addSql('DROP TABLE dish_side_dish');
        $this->addSql('DROP TABLE menu_restaurant');
        $this->addSql('DROP TABLE menu_restaurant_dish');
        $this->addSql('DROP TABLE menu_restaurant_beverage');
        $this->addSql('DROP TABLE menu_restaurant_dessert');
        $this->addSql('DROP TABLE order_slip');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('DROP TABLE purchase_item');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE side_dish');
        $this->addSql('DROP TABLE user');
    }
}
