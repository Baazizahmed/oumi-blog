<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250607184426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE post_tag (post_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_5ACE3AF04B89032C (post_id), INDEX IDX_5ACE3AF0BAD26311 (tag_id), PRIMARY KEY(post_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_tag ADD CONSTRAINT FK_5ACE3AF04B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_tag ADD CONSTRAINT FK_5ACE3AF0BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE post_tag DROP FOREIGN KEY FK_5ACE3AF04B89032C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_tag DROP FOREIGN KEY FK_5ACE3AF0BAD26311
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE post_tag
        SQL);
    }
}
