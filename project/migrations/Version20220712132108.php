<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220712132108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE task_task');
        $this->addSql('ALTER TABLE task ADD child_task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25EF02499D FOREIGN KEY (child_task_id) REFERENCES task (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25EF02499D ON task (child_task_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_task (task_source INT NOT NULL, task_target INT NOT NULL, INDEX IDX_21CD4F5E6423FBA0 (task_source), INDEX IDX_21CD4F5E7DC6AB2F (task_target), PRIMARY KEY(task_source, task_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE task_task ADD CONSTRAINT FK_21CD4F5E6423FBA0 FOREIGN KEY (task_source) REFERENCES task (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_task ADD CONSTRAINT FK_21CD4F5E7DC6AB2F FOREIGN KEY (task_target) REFERENCES task (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25EF02499D');
        $this->addSql('DROP INDEX IDX_527EDB25EF02499D ON task');
        $this->addSql('ALTER TABLE task DROP child_task_id');
    }
}
