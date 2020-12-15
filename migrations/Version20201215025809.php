<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201215025809 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gift (id BLOB NOT NULL --(DC2Type:uuid)
        , stock_id INTEGER NOT NULL, code VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, price VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A47C990DDCD6110 ON gift (stock_id)');
        $this->addSql('CREATE TABLE receiver (id BLOB NOT NULL --(DC2Type:uuid)
        , first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, country_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE receiver_gift (receiver_id BLOB NOT NULL --(DC2Type:uuid)
        , gift_id BLOB NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(receiver_id, gift_id))');
        $this->addSql('CREATE INDEX IDX_A135D533CD53EDB6 ON receiver_gift (receiver_id)');
        $this->addSql('CREATE INDEX IDX_A135D53397A95A83 ON receiver_gift (gift_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE gift');
        $this->addSql('DROP TABLE receiver');
        $this->addSql('DROP TABLE receiver_gift');
    }
}
