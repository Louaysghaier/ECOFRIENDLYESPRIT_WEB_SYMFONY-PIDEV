<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207030554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE codepromo (idcodepromo INT AUTO_INCREMENT NOT NULL, pourcentage DOUBLE PRECISION NOT NULL, idevent INT DEFAULT NULL, idtransport INT DEFAULT NULL, idservice INT DEFAULT NULL, PRIMARY KEY(idcodepromo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_comment INT DEFAULT NULL, id_post INT NOT NULL, description_comment VARCHAR(800) NOT NULL, date_creation_comment DATE NOT NULL, post_id INT DEFAULT NULL, INDEX comment_ibfk_2 (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documents (id INT AUTO_INCREMENT NOT NULL, iduser INT DEFAULT NULL, idtopic INT DEFAULT NULL, document_name VARCHAR(255) NOT NULL, document_type VARCHAR(255) NOT NULL, document VARCHAR(255) NOT NULL, niveau VARCHAR(255) NOT NULL, semestre VARCHAR(255) NOT NULL, brochureFilename VARCHAR(255) NOT NULL, INDEX idtopic (idtopic), INDEX iduser (iduser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (idEvent INT AUTO_INCREMENT NOT NULL, LieuEvent VARCHAR(100) NOT NULL, datedebutevent DATETIME NOT NULL, Duree VARCHAR(255) NOT NULL, nbmaxParticipant VARCHAR(100) NOT NULL, PrixTicket VARCHAR(100) NOT NULL, nomEvent VARCHAR(100) NOT NULL, typeEvent VARCHAR(100) NOT NULL, descriptionEvent VARCHAR(255) NOT NULL, image VARCHAR(100) DEFAULT NULL, datecreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, iduser INT DEFAULT NULL, valid TINYINT(1) DEFAULT 1, current_nb_participants INT DEFAULT 0, PRIMARY KEY(idEvent)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (feeds INT AUTO_INCREMENT NOT NULL, oreder_id VARCHAR(255) NOT NULL, service_id VARCHAR(255) NOT NULL, comments VARCHAR(255) NOT NULL, PRIMARY KEY(feeds)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (services INT DEFAULT NULL, orderId INT AUTO_INCREMENT NOT NULL, num_order VARCHAR(255) NOT NULL, pickupDateTime DATETIME NOT NULL, status VARCHAR(255) DEFAULT \'dispo\' NOT NULL, phonenumber VARCHAR(255) DEFAULT \'NULL\', priceOrder INT DEFAULT NULL, userId INT DEFAULT NULL, INDEX fk_idserv (services), INDEX fk_iduser (userId), PRIMARY KEY(orderId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orderservices (orderId INT NOT NULL, serviceId INT NOT NULL, INDEX IDX_F100A95FFA237437 (orderId), INDEX IDX_F100A95F89697FA8 (serviceId), PRIMARY KEY(orderId, serviceId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, code_qr VARCHAR(200) DEFAULT NULL, date_participation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, payment_status TINYINT(1) DEFAULT NULL, idEvent INT NOT NULL, idUser INT NOT NULL, INDEX IDX_AB55E24F2C6A49BA (idEvent), INDEX IDX_AB55E24FFE6E88D7 (idUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_post INT DEFAULT NULL, subject VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description_post VARCHAR(800) NOT NULL, image_post VARCHAR(255) DEFAULT \'NULL\', date_creation_post DATE NOT NULL, nbres_comments INT NOT NULL, INDEX id_user (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE raiting (idraiting INT AUTO_INCREMENT NOT NULL, id INT DEFAULT NULL, iduser INT DEFAULT NULL, value INT NOT NULL, INDEX iduser (iduser), INDEX id (id), PRIMARY KEY(idraiting)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (rate_id INT AUTO_INCREMENT NOT NULL, service VARCHAR(255) NOT NULL, stars INT NOT NULL, PRIMARY KEY(rate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (serviceId INT AUTO_INCREMENT NOT NULL, serviceName VARCHAR(100) NOT NULL, price DOUBLE PRECISION NOT NULL, img VARCHAR(200) NOT NULL, description VARCHAR(30) DEFAULT NULL, PRIMARY KEY(serviceId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, id_transport_id INT DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, lieu_depart VARCHAR(255) NOT NULL, lieu_arrive VARCHAR(255) NOT NULL, date_ticket DATETIME NOT NULL, statut_ticket VARCHAR(255) NOT NULL, rating INT DEFAULT NULL, user_rating INT DEFAULT NULL, idUser INT NOT NULL, INDEX IDX_97A0ADA3BAC8B44D (id_transport_id), INDEX IDX_97A0ADA3FE6E88D7 (idUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic (idtopic INT AUTO_INCREMENT NOT NULL, topic_name VARCHAR(255) NOT NULL, PRIMARY KEY(idtopic)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transport (id INT AUTO_INCREMENT NOT NULL, nbre_places INT NOT NULL, type_transport VARCHAR(255) NOT NULL, disponibilite VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transport_user (transport_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_504381CC9909C13F (transport_id), INDEX IDX_504381CCA76ED395 (user_id), PRIMARY KEY(transport_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user2 (iduser INT AUTO_INCREMENT NOT NULL, nomuser VARCHAR(255) NOT NULL, prenomuser VARCHAR(255) NOT NULL, mailuser VARCHAR(255) NOT NULL, mdpuser VARCHAR(255) DEFAULT NULL, adressuser VARCHAR(255) DEFAULT NULL, walletuser DOUBLE PRECISION DEFAULT \'250\' NOT NULL, classeuser VARCHAR(200) NOT NULL, roleuser VARCHAR(200) NOT NULL, isBlocked TINYINT(1) DEFAULT NULL, reset_token VARCHAR(180) DEFAULT NULL, PRIMARY KEY(iduser)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (iduser INT NOT NULL, event_id INT NOT NULL, INDEX IDX_24CC0DF25E5C27E9 (iduser), INDEX IDX_24CC0DF271F7E88B (event_id), PRIMARY KEY(iduser, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C6B3CA4B FOREIGN KEY (id_user) REFERENCES user2 (iduser)');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B072885E5C27E9 FOREIGN KEY (iduser) REFERENCES user2 (iduser)');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B072884B45B202 FOREIGN KEY (idtopic) REFERENCES topic (idtopic)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE64B64DCC FOREIGN KEY (userId) REFERENCES user2 (iduser)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE7332E169 FOREIGN KEY (services) REFERENCES service (serviceId)');
        $this->addSql('ALTER TABLE orderservices ADD CONSTRAINT FK_F100A95FFA237437 FOREIGN KEY (orderId) REFERENCES orders (orderId)');
        $this->addSql('ALTER TABLE orderservices ADD CONSTRAINT FK_F100A95F89697FA8 FOREIGN KEY (serviceId) REFERENCES service (serviceId)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2C6A49BA FOREIGN KEY (idEvent) REFERENCES event (idEvent)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FFE6E88D7 FOREIGN KEY (idUser) REFERENCES user2 (iduser)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6B3CA4B FOREIGN KEY (id_user) REFERENCES user2 (iduser)');
        $this->addSql('ALTER TABLE raiting ADD CONSTRAINT FK_3AE2A209BF396750 FOREIGN KEY (id) REFERENCES documents (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE raiting ADD CONSTRAINT FK_3AE2A2095E5C27E9 FOREIGN KEY (iduser) REFERENCES user2 (iduser)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3BAC8B44D FOREIGN KEY (id_transport_id) REFERENCES transport (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3FE6E88D7 FOREIGN KEY (idUser) REFERENCES user2 (iduser)');
        $this->addSql('ALTER TABLE transport_user ADD CONSTRAINT FK_504381CC9909C13F FOREIGN KEY (transport_id) REFERENCES transport (id)');
        $this->addSql('ALTER TABLE transport_user ADD CONSTRAINT FK_504381CCA76ED395 FOREIGN KEY (user_id) REFERENCES user2 (iduser)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF25E5C27E9 FOREIGN KEY (iduser) REFERENCES user2 (iduser)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF271F7E88B FOREIGN KEY (event_id) REFERENCES event (idEvent)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C6B3CA4B');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B072885E5C27E9');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B072884B45B202');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE64B64DCC');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE7332E169');
        $this->addSql('ALTER TABLE orderservices DROP FOREIGN KEY FK_F100A95FFA237437');
        $this->addSql('ALTER TABLE orderservices DROP FOREIGN KEY FK_F100A95F89697FA8');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F2C6A49BA');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FFE6E88D7');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6B3CA4B');
        $this->addSql('ALTER TABLE raiting DROP FOREIGN KEY FK_3AE2A209BF396750');
        $this->addSql('ALTER TABLE raiting DROP FOREIGN KEY FK_3AE2A2095E5C27E9');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3BAC8B44D');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3FE6E88D7');
        $this->addSql('ALTER TABLE transport_user DROP FOREIGN KEY FK_504381CC9909C13F');
        $this->addSql('ALTER TABLE transport_user DROP FOREIGN KEY FK_504381CCA76ED395');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF25E5C27E9');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF271F7E88B');
        $this->addSql('DROP TABLE codepromo');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE documents');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orderservices');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE raiting');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP TABLE transport');
        $this->addSql('DROP TABLE transport_user');
        $this->addSql('DROP TABLE user2');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
