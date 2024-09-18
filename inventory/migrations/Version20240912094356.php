<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240912094356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory_transaction CHANGE total_price total_price NUMERIC(20, 2) NOT NULL, CHANGE total_before_decrease total_before_decrease NUMERIC(20, 2) NOT NULL, CHANGE total_after_decrease total_after_decrease NUMERIC(20, 2) NOT NULL, CHANGE discount discount NUMERIC(20, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory_transaction CHANGE total_price total_price NUMERIC(10, 2) NOT NULL, CHANGE total_before_decrease total_before_decrease NUMERIC(10, 2) NOT NULL, CHANGE total_after_decrease total_after_decrease NUMERIC(10, 2) NOT NULL, CHANGE discount discount NUMERIC(10, 2) NOT NULL');
    }
}
