<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218133728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, author_id_id, name, description, price, created_at, image_link FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, price INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , image_link VARCHAR(2048) NOT NULL, CONSTRAINT FK_23A0E6669CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO article (id, author_id_id, name, description, price, created_at, image_link) SELECT id, author_id_id, name, description, price, created_at, image_link FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE INDEX IDX_23A0E6669CCBE9A ON article (author_id_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, author_id_id FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id_id INTEGER NOT NULL, CONSTRAINT FK_BA388B769CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO cart (id, author_id_id) SELECT id, author_id_id FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B769CCBE9A ON cart (author_id_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__type AS SELECT id, name, image_link FROM type');
        $this->addSql('DROP TABLE type');
        $this->addSql('CREATE TABLE type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(20) NOT NULL, image_link VARCHAR(2048) NOT NULL)');
        $this->addSql('INSERT INTO type (id, name, image_link) SELECT id, name, image_link FROM __temp__type');
        $this->addSql('DROP TABLE __temp__type');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, password, email, sold, profile_picture, roles FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, sold INTEGER NOT NULL, profile_picture VARCHAR(2048) DEFAULT NULL, roles CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO user (id, username, password, email, sold, profile_picture, roles) SELECT id, username, password, email, sold, profile_picture, roles FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, author_id_id, name, description, price, created_at, image_link FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, price INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , image_link VARCHAR(2048) NOT NULL, CONSTRAINT FK_23A0E6669CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO article (id, author_id_id, name, description, price, created_at, image_link) SELECT id, author_id_id, name, description, price, created_at, image_link FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE INDEX IDX_23A0E6669CCBE9A ON article (author_id_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, author_id_id FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id_id INTEGER DEFAULT NULL, CONSTRAINT FK_BA388B769CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO cart (id, author_id_id) SELECT id, author_id_id FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B769CCBE9A ON cart (author_id_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__type AS SELECT id, name, image_link FROM type');
        $this->addSql('DROP TABLE type');
        $this->addSql('CREATE TABLE type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(25) NOT NULL, image_link VARCHAR(2048) NOT NULL)');
        $this->addSql('INSERT INTO type (id, name, image_link) SELECT id, name, image_link FROM __temp__type');
        $this->addSql('DROP TABLE __temp__type');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, username, sold, profile_picture FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(320) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, sold INTEGER DEFAULT 0 NOT NULL, profile_picture VARCHAR(2048) DEFAULT NULL, uuid VARCHAR(180) NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password, username, sold, profile_picture) SELECT id, email, roles, password, username, sold, profile_picture FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_UUID ON user (uuid)');
    }
}
