<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201215012710 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create Receiver and Gift tables';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gift (id BLOB NOT NULL --(DC2Type:uuid)
        , code VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE receiver (id BLOB NOT NULL --(DC2Type:uuid)
        , first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, country_code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE receiver_gift (receiver_id BLOB NOT NULL --(DC2Type:uuid)
        , gift_id BLOB NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(receiver_id, gift_id))');
        $this->addSql('CREATE INDEX IDX_A135D533CD53EDB6 ON receiver_gift (receiver_id)');
        $this->addSql('CREATE INDEX IDX_A135D53397A95A83 ON receiver_gift (gift_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__stock AS SELECT id, file_path FROM stock');
        $this->addSql('DROP TABLE stock');
        $this->addSql('CREATE TABLE stock (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, gift_id BLOB DEFAULT NULL --(DC2Type:uuid)
        , file_path VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_4B36566097A95A83 FOREIGN KEY (gift_id) REFERENCES gift (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO stock (id, file_path) SELECT id, file_path FROM __temp__stock');
        $this->addSql('DROP TABLE __temp__stock');
        $this->addSql('CREATE INDEX IDX_4B36566097A95A83 ON stock (gift_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE gift');
        $this->addSql('DROP TABLE receiver');
        $this->addSql('DROP TABLE receiver_gift');
        $this->addSql('DROP INDEX IDX_4B36566097A95A83');
        $this->addSql('CREATE TEMPORARY TABLE __temp__stock AS SELECT id, file_path FROM stock');
        $this->addSql('DROP TABLE stock');
        $this->addSql('CREATE TABLE stock (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, file_path VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO stock (id, file_path) SELECT id, file_path FROM __temp__stock');
        $this->addSql('DROP TABLE __temp__stock');
    }
}
