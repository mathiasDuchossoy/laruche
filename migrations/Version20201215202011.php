<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201215202011 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('DROP INDEX IDX_A47C990DDCD6110');
        $this->addSql('CREATE TEMPORARY TABLE __temp__gift AS SELECT id, stock_id, code, description, price FROM gift');
        $this->addSql('DROP TABLE gift');
        $this->addSql('CREATE TABLE gift (id BLOB NOT NULL --(DC2Type:uuid)
        , stock_id INTEGER NOT NULL, code VARCHAR(255) DEFAULT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, price VARCHAR(255) DEFAULT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_A47C990DDCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO gift (id, stock_id, code, description, price) SELECT id, stock_id, code, description, price FROM __temp__gift');
        $this->addSql('DROP TABLE __temp__gift');
        $this->addSql('CREATE INDEX IDX_A47C990DDCD6110 ON gift (stock_id)');
        $this->addSql('DROP INDEX IDX_A135D53397A95A83');
        $this->addSql('DROP INDEX IDX_A135D533CD53EDB6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__receiver_gift AS SELECT receiver_id, gift_id FROM receiver_gift');
        $this->addSql('DROP TABLE receiver_gift');
        $this->addSql('CREATE TABLE receiver_gift (receiver_id BLOB NOT NULL --(DC2Type:uuid)
        , gift_id BLOB NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(receiver_id, gift_id), CONSTRAINT FK_A135D533CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES receiver (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A135D53397A95A83 FOREIGN KEY (gift_id) REFERENCES gift (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO receiver_gift (receiver_id, gift_id) SELECT receiver_id, gift_id FROM __temp__receiver_gift');
        $this->addSql('DROP TABLE __temp__receiver_gift');
        $this->addSql('CREATE INDEX IDX_A135D53397A95A83 ON receiver_gift (gift_id)');
        $this->addSql('CREATE INDEX IDX_A135D533CD53EDB6 ON receiver_gift (receiver_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_A47C990DDCD6110');
        $this->addSql('CREATE TEMPORARY TABLE __temp__gift AS SELECT id, stock_id, code, description, price FROM gift');
        $this->addSql('DROP TABLE gift');
        $this->addSql('CREATE TABLE gift (id BLOB NOT NULL --(DC2Type:uuid)
        , stock_id INTEGER NOT NULL, code VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, price VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO gift (id, stock_id, code, description, price) SELECT id, stock_id, code, description, price FROM __temp__gift');
        $this->addSql('DROP TABLE __temp__gift');
        $this->addSql('CREATE INDEX IDX_A47C990DDCD6110 ON gift (stock_id)');
        $this->addSql('DROP INDEX IDX_A135D533CD53EDB6');
        $this->addSql('DROP INDEX IDX_A135D53397A95A83');
        $this->addSql('CREATE TEMPORARY TABLE __temp__receiver_gift AS SELECT receiver_id, gift_id FROM receiver_gift');
        $this->addSql('DROP TABLE receiver_gift');
        $this->addSql('CREATE TABLE receiver_gift (receiver_id BLOB NOT NULL --(DC2Type:uuid)
        , gift_id BLOB NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(receiver_id, gift_id))');
        $this->addSql('INSERT INTO receiver_gift (receiver_id, gift_id) SELECT receiver_id, gift_id FROM __temp__receiver_gift');
        $this->addSql('DROP TABLE __temp__receiver_gift');
        $this->addSql('CREATE INDEX IDX_A135D533CD53EDB6 ON receiver_gift (receiver_id)');
        $this->addSql('CREATE INDEX IDX_A135D53397A95A83 ON receiver_gift (gift_id)');
    }
}
