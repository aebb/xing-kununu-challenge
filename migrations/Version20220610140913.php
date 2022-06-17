<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610140913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE companies (id INT AUTO_INCREMENT NOT NULL, score_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, industry VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8244AA3A5E237E06 (name), UNIQUE INDEX UNIQ_8244AA3A989D9B62 (slug), UNIQUE INDEX UNIQ_8244AA3A12EB0A51 (score_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE companies_score (id INT AUTO_INCREMENT NOT NULL, score INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX score_index (score), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ratings (id INT AUTO_INCREMENT NOT NULL, culture INT NOT NULL, management INT NOT NULL, work_life_balance INT NOT NULL, career_development INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reviews (id INT AUTO_INCREMENT NOT NULL, rating_id INT DEFAULT NULL, sentiment_id INT DEFAULT NULL, company_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(200) NOT NULL, pro VARCHAR(255) DEFAULT NULL, contra VARCHAR(255) DEFAULT NULL, suggestions VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_6970EB0FA32EFC6 (rating_id), UNIQUE INDEX UNIQ_6970EB0F4D40E392 (sentiment_id), INDEX IDX_6970EB0F979B1AD6 (company_id), INDEX IDX_6970EB0FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reviews_sentiment (id INT AUTO_INCREMENT NOT NULL, positive DOUBLE PRECISION NOT NULL, negative DOUBLE PRECISION NOT NULL, abusive DOUBLE PRECISION NOT NULL, analyzed TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, apiToken VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), UNIQUE INDEX UNIQ_1483A5E9E22488D7 (apiToken), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE companies ADD CONSTRAINT FK_8244AA3A12EB0A51 FOREIGN KEY (score_id) REFERENCES companies_score (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FA32EFC6 FOREIGN KEY (rating_id) REFERENCES ratings (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F4D40E392 FOREIGN KEY (sentiment_id) REFERENCES reviews_sentiment (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F979B1AD6');
        $this->addSql('ALTER TABLE companies DROP FOREIGN KEY FK_8244AA3A12EB0A51');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0FA32EFC6');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F4D40E392');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0FA76ED395');
        $this->addSql('DROP TABLE companies');
        $this->addSql('DROP TABLE companies_score');
        $this->addSql('DROP TABLE ratings');
        $this->addSql('DROP TABLE reviews');
        $this->addSql('DROP TABLE reviews_sentiment');
        $this->addSql('DROP TABLE users');
    }
}
