<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250615224029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE tracking.order_status_tracking ALTER old_status TYPE VARCHAR(30)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tracking.order_status_tracking ALTER new_status TYPE VARCHAR(30)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE tracking.order_status_tracking ALTER old_status TYPE VARCHAR(20)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tracking.order_status_tracking ALTER new_status TYPE VARCHAR(20)
        SQL);
    }
}
