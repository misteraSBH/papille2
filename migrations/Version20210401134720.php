<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401134720 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dish_side_dish (dish_id INT NOT NULL, side_dish_id INT NOT NULL, INDEX IDX_1340E9DE148EB0CB (dish_id), INDEX IDX_1340E9DEC884D3E8 (side_dish_id), PRIMARY KEY(dish_id, side_dish_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dish_side_dish ADD CONSTRAINT FK_1340E9DE148EB0CB FOREIGN KEY (dish_id) REFERENCES dish (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dish_side_dish ADD CONSTRAINT FK_1340E9DEC884D3E8 FOREIGN KEY (side_dish_id) REFERENCES side_dish (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE dish_side_dish');
    }
}
