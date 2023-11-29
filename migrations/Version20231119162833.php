<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119162833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE feedback (feeds INT AUTO_INCREMENT NOT NULL, oreder_id VARCHAR(255) NOT NULL, service_id VARCHAR(255) NOT NULL, comments VARCHAR(255) NOT NULL, PRIMARY KEY(feeds)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (services INT DEFAULT NULL, orderId INT AUTO_INCREMENT NOT NULL, num_order INT NOT NULL, pickupDateTime DATETIME NOT NULL, status VARCHAR(255) DEFAULT \'\'\'disponible\'\'\' NOT NULL, phonenumber VARCHAR(255) DEFAULT \'NULL\', priceOrder INT DEFAULT NULL, userId INT DEFAULT NULL, INDEX fk_idserv (services), INDEX fk_iduser (userId), PRIMARY KEY(orderId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orderservices (orderId INT NOT NULL, serviceId INT NOT NULL, INDEX IDX_F100A95FFA237437 (orderId), INDEX IDX_F100A95F89697FA8 (serviceId), PRIMARY KEY(orderId, serviceId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (rate_id INT AUTO_INCREMENT NOT NULL, orders VARCHAR(255) NOT NULL, service VARCHAR(255) NOT NULL, stars INT NOT NULL, PRIMARY KEY(rate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (serviceId INT AUTO_INCREMENT NOT NULL, serviceName VARCHAR(100) NOT NULL, price DOUBLE PRECISION NOT NULL, img VARCHAR(200) NOT NULL, description VARCHAR(30) DEFAULT NULL, PRIMARY KEY(serviceId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user2 (iduser INT AUTO_INCREMENT NOT NULL, nomuser VARCHAR(100) NOT NULL, prenomuser VARCHAR(100) NOT NULL, mailuser VARCHAR(200) NOT NULL, mdpuser VARCHAR(300) NOT NULL, adressuser VARCHAR(200) NOT NULL, walletuser DOUBLE PRECISION DEFAULT \'250\' NOT NULL, classeuser VARCHAR(200) NOT NULL, roleuser VARCHAR(255) DEFAULT \'NULL\', isBlocked TINYINT(1) DEFAULT NULL, PRIMARY KEY(iduser)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE64B64DCC FOREIGN KEY (userId) REFERENCES user2 (iduser)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE7332E169 FOREIGN KEY (services) REFERENCES service (serviceId)');
        $this->addSql('ALTER TABLE orderservices ADD CONSTRAINT FK_F100A95FFA237437 FOREIGN KEY (orderId) REFERENCES orders (orderId)');
        $this->addSql('ALTER TABLE orderservices ADD CONSTRAINT FK_F100A95F89697FA8 FOREIGN KEY (serviceId) REFERENCES service (serviceId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE64B64DCC');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE7332E169');
        $this->addSql('ALTER TABLE orderservices DROP FOREIGN KEY FK_F100A95FFA237437');
        $this->addSql('ALTER TABLE orderservices DROP FOREIGN KEY FK_F100A95F89697FA8');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orderservices');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user2');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
