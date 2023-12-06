<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231206000535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (idEvent INT AUTO_INCREMENT NOT NULL, LieuEvent VARCHAR(100) NOT NULL, datedebutevent DATETIME NOT NULL, Duree VARCHAR(255) NOT NULL, nbmaxParticipant VARCHAR(100) NOT NULL, PrixTicket VARCHAR(100) NOT NULL, nomEvent VARCHAR(100) NOT NULL, typeEvent VARCHAR(100) NOT NULL, descriptionEvent VARCHAR(255) NOT NULL, image VARCHAR(100) DEFAULT NULL, datecreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, iduser INT DEFAULT NULL, valid TINYINT(1) DEFAULT 1, current_nb_participants INT DEFAULT 0, PRIMARY KEY(idEvent)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, code_qr VARCHAR(200) DEFAULT NULL, date_participation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, payment_status TINYINT(1) DEFAULT NULL, idEvent INT NOT NULL, idUser INT NOT NULL, INDEX IDX_AB55E24F2C6A49BA (idEvent), INDEX IDX_AB55E24FFE6E88D7 (idUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (iduser INT NOT NULL, event_id INT NOT NULL, INDEX IDX_24CC0DF25E5C27E9 (iduser), INDEX IDX_24CC0DF271F7E88B (event_id), PRIMARY KEY(iduser, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2C6A49BA FOREIGN KEY (idEvent) REFERENCES event (idEvent)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FFE6E88D7 FOREIGN KEY (idUser) REFERENCES user2 (iduser)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF25E5C27E9 FOREIGN KEY (iduser) REFERENCES user2 (iduser)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF271F7E88B FOREIGN KEY (event_id) REFERENCES event (idEvent)');
        $this->addSql('ALTER TABLE orders CHANGE status status VARCHAR(255) DEFAULT \'dispo\' NOT NULL, CHANGE phonenumber phonenumber VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE service CHANGE description description VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE user2 CHANGE mdpuser mdpuser VARCHAR(255) DEFAULT NULL, CHANGE adressuser adressuser VARCHAR(255) DEFAULT NULL, CHANGE reset_token reset_token VARCHAR(180) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F2C6A49BA');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FFE6E88D7');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF25E5C27E9');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF271F7E88B');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE orders CHANGE status status VARCHAR(255) DEFAULT \'\'\'dispo\'\'\' NOT NULL, CHANGE phonenumber phonenumber VARCHAR(255) DEFAULT \'\'\'NULL\'\'\'');
        $this->addSql('ALTER TABLE service CHANGE description description VARCHAR(30) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user2 CHANGE mdpuser mdpuser VARCHAR(255) DEFAULT \'NULL\', CHANGE adressuser adressuser VARCHAR(255) DEFAULT \'NULL\', CHANGE reset_token reset_token VARCHAR(180) NOT NULL');
    }
}
