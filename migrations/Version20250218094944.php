<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218094944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id_id INTEGER NOT NULL, stock_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, price INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , image_link VARCHAR(2048) NOT NULL, CONSTRAINT FK_23A0E6669CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_23A0E66DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_23A0E6669CCBE9A ON article (author_id_id)');
        $this->addSql('CREATE INDEX IDX_23A0E66DCD6110 ON article (stock_id)');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id_id INTEGER NOT NULL, CONSTRAINT FK_BA388B769CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B769CCBE9A ON cart (author_id_id)');
        $this->addSql('CREATE TABLE cart_article (cart_id INTEGER NOT NULL, article_id INTEGER NOT NULL, PRIMARY KEY(cart_id, article_id), CONSTRAINT FK_F9E0C6611AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F9E0C6617294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F9E0C6611AD5CDBF ON cart_article (cart_id)');
        $this->addSql('CREATE INDEX IDX_F9E0C6617294869C ON cart_article (article_id)');
        $this->addSql('CREATE TABLE invoice (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , price INTEGER NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(100) NOT NULL, postal_code VARCHAR(20) NOT NULL, CONSTRAINT FK_9065174469CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_9065174469CCBE9A ON invoice (author_id_id)');
        $this->addSql('CREATE TABLE stock (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, amount INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id_id INTEGER NOT NULL, name VARCHAR(25) NOT NULL, image_link VARCHAR(2048) DEFAULT NULL, CONSTRAINT FK_8CDE57298F3EC46 FOREIGN KEY (article_id_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_8CDE57298F3EC46 ON type (article_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_article');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE type');
    }
}
