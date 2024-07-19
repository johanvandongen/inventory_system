<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715213113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_state ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_state ADD CONSTRAINT FK_2CFA9A74584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_2CFA9A74584665A ON product_state (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_state DROP FOREIGN KEY FK_2CFA9A74584665A');
        $this->addSql('DROP INDEX IDX_2CFA9A74584665A ON product_state');
        $this->addSql('ALTER TABLE product_state DROP product_id');
    }
}
