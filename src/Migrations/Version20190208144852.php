<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190208144852 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE my_entity DROP FOREIGN KEY FK_924D847312469DE2');
        $this->addSql('ALTER TABLE article_tags DROP FOREIGN KEY FK_DFFE1327677473C9');
        $this->addSql('ALTER TABLE my_entity DROP FOREIGN KEY FK_924D84737294869C');
        $this->addSql('ALTER TABLE article_tags DROP FOREIGN KEY FK_DFFE1327BAD26311');
        $this->addSql('DROP TABLE article_tags');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE my_entity');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_tags (my_entity_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_DFFE1327677473C9 (my_entity_id), INDEX IDX_DFFE1327BAD26311 (tag_id), PRIMARY KEY(my_entity_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, prenom VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, objet VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, message LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, client_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, client_acronym VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, reference VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, invoice_date DATETIME DEFAULT NULL, due_date DATETIME DEFAULT NULL, object VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE my_entity (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, article_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, content VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_enabled TINYINT(1) DEFAULT NULL, nb_like VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, cover VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_924D847312469DE2 (category_id), UNIQUE INDEX UNIQ_924D84737294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, codeposte VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, nomposte VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, fonction VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, etat VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, tension INT DEFAULT NULL, longitudeposte_dd NUMERIC(20, 15) NOT NULL, latitudeposte_dd NUMERIC(20, 15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_tags ADD CONSTRAINT FK_DFFE1327677473C9 FOREIGN KEY (my_entity_id) REFERENCES my_entity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_tags ADD CONSTRAINT FK_DFFE1327BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE my_entity ADD CONSTRAINT FK_924D847312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE my_entity ADD CONSTRAINT FK_924D84737294869C FOREIGN KEY (article_id) REFERENCES my_entity (id)');
    }
}
