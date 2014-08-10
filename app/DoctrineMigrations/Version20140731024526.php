<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140731024526 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE tip_category (tip_id VARCHAR(255) NOT NULL, category_id VARCHAR(255) NOT NULL, INDEX IDX_4FCC19CE476C47F6 (tip_id), INDEX IDX_4FCC19CE12469DE2 (category_id), PRIMARY KEY(tip_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE tip_category ADD CONSTRAINT FK_4FCC19CE476C47F6 FOREIGN KEY (tip_id) REFERENCES tips (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE tip_category ADD CONSTRAINT FK_4FCC19CE12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE tip_category");
    }
}
