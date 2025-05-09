<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418202447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA cart
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA sale
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA tracking
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA catalog
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA report
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA identity
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE cart.cart_items_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE cart.carts_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE sale.order_items_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE tracking.order_status_tracking_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE sale.orders_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE catalog.products_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE identity.users_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cart.cart_items (id INT NOT NULL, cart_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E13279841AD5CDBF ON cart.cart_items (cart_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E13279844584665A ON cart.cart_items (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN cart.cart_items.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN cart.cart_items.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cart.carts (id INT NOT NULL, user_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_D194F9C0A76ED395 ON cart.carts (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN cart.carts.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN cart.carts.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sale.order_items (id INT NOT NULL, order_id INT DEFAULT NULL, product_id INT DEFAULT NULL, price INT NOT NULL, quantity INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AC2381468D9F6D38 ON sale.order_items (order_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AC2381464584665A ON sale.order_items (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN sale.order_items.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN sale.order_items.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tracking.order_status_tracking (id INT NOT NULL, created_by INT DEFAULT NULL, order_id INT DEFAULT NULL, old_status VARCHAR(20) NOT NULL, new_status VARCHAR(20) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3697DFB7DE12AB56 ON tracking.order_status_tracking (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3697DFB78D9F6D38 ON tracking.order_status_tracking (order_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN tracking.order_status_tracking.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sale.orders (id INT NOT NULL, user_id INT DEFAULT NULL, notification_type VARCHAR(20) NOT NULL, total_cost INT NOT NULL, delivery_address VARCHAR(255) DEFAULT NULL, delivery_type VARCHAR(255) NOT NULL, kladr_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DB5128B3A76ED395 ON sale.orders (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN sale.orders.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN sale.orders.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE catalog.products (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, cost INT NOT NULL, tax INT NOT NULL, weight INT NOT NULL, height INT NOT NULL, width INT NOT NULL, length INT NOT NULL, version INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN catalog.products.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN catalog.products.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE report.reports (uuid UUID NOT NULL, status VARCHAR(20) NOT NULL, file_path VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(uuid))
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN report.reports.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN report.reports.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE identity.users (id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, password VARCHAR(255) NOT NULL, group_name VARCHAR(20) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_3EA6317DE7927C74 ON identity.users (email)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_3EA6317D444F97DD ON identity.users (phone)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN identity.users.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN identity.users.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.available_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
                BEGIN
                    PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                    RETURN NEW;
                END;
            $$ LANGUAGE plpgsql;
        SQL);
        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart.cart_items ADD CONSTRAINT FK_E13279841AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart.carts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart.cart_items ADD CONSTRAINT FK_E13279844584665A FOREIGN KEY (product_id) REFERENCES catalog.products (id) ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart.carts ADD CONSTRAINT FK_D194F9C0A76ED395 FOREIGN KEY (user_id) REFERENCES identity.users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sale.order_items ADD CONSTRAINT FK_AC2381468D9F6D38 FOREIGN KEY (order_id) REFERENCES sale.orders (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sale.order_items ADD CONSTRAINT FK_AC2381464584665A FOREIGN KEY (product_id) REFERENCES catalog.products (id) ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tracking.order_status_tracking ADD CONSTRAINT FK_3697DFB7DE12AB56 FOREIGN KEY (created_by) REFERENCES identity.users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tracking.order_status_tracking ADD CONSTRAINT FK_3697DFB78D9F6D38 FOREIGN KEY (order_id) REFERENCES sale.orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sale.orders ADD CONSTRAINT FK_DB5128B3A76ED395 FOREIGN KEY (user_id) REFERENCES identity.users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE cart.cart_items_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE cart.carts_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE sale.order_items_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE tracking.order_status_tracking_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE sale.orders_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE catalog.products_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE identity.users_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart.cart_items DROP CONSTRAINT FK_E13279841AD5CDBF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart.cart_items DROP CONSTRAINT FK_E13279844584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart.carts DROP CONSTRAINT FK_D194F9C0A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sale.order_items DROP CONSTRAINT FK_AC2381468D9F6D38
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sale.order_items DROP CONSTRAINT FK_AC2381464584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tracking.order_status_tracking DROP CONSTRAINT FK_3697DFB7DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tracking.order_status_tracking DROP CONSTRAINT FK_3697DFB78D9F6D38
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sale.orders DROP CONSTRAINT FK_DB5128B3A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cart.cart_items
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cart.carts
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sale.order_items
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tracking.order_status_tracking
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sale.orders
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE catalog.products
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE report.reports
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE identity.users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
