<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409114125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_slip ADD restaurant_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_slip ADD CONSTRAINT FK_B0C879EFB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_B0C879EFB1E7706E ON order_slip (restaurant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_slip DROP FOREIGN KEY FK_B0C879EFB1E7706E');
        $this->addSql('DROP INDEX IDX_B0C879EFB1E7706E ON order_slip');
        $this->addSql('ALTER TABLE order_slip DROP restaurant_id');
    }
}
