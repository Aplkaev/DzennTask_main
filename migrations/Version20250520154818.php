<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520154818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE kanban_column (id UUID NOT NULL, project_id UUID NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, position INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_157CF286166D1F9C ON kanban_column (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN kanban_column.id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN kanban_column.project_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE kanban_column ADD CONSTRAINT FK_157CF286166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD kanban_column_id UUID NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN task.kanban_column_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD CONSTRAINT FK_527EDB25FCC01C3F FOREIGN KEY (kanban_column_id) REFERENCES kanban_column (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_527EDB25FCC01C3F ON task (kanban_column_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task DROP CONSTRAINT FK_527EDB25FCC01C3F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE kanban_column DROP CONSTRAINT FK_157CF286166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE kanban_column
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_527EDB25FCC01C3F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task DROP kanban_column_id
        SQL);
    }
}
