<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250314145629 extends AbstractMigration
{
    private const TABLE_PRODUCTS = 'products';
    private const TABLE_USERS = 'users';
    private const TABLE_ORDERS = 'orders';
    private const TABLE_CARTS = 'carts';
    private const TABLE_CART_ITEMS = 'cart_items';
    private const TABLE_ORDER_ITEMS = 'order_items';
    private const TABLE_ORDER_STATUS_TRACKING = 'order_status_tracking';
    private const TABLE_REPORTS = 'reports';
    private const TABLE_USER_GROUPS = 'user_groups';

    public function getDescription(): string
    {
        return 'Создание ссновных таблиц';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE ' . self::TABLE_USER_GROUPS . ' (
            id INT AUTO_INCREMENT PRIMARY KEY,
            group_name VARCHAR(50) NOT NULL,
            UNIQUE INDEX UNIQ_GROUPS_NAME (group_name)
        )');

        $this->addSql('CREATE TABLE ' . self::TABLE_PRODUCTS . ' (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description VARCHAR(255),
            cost INT NOT NULL,
            tax INT NOT NULL,
            weight INT NOT NULL,
            height INT NOT NULL,
            width INT NOT NULL,
            length INT NOT NULL,
            version INT NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL
        )');

        $this->addSql('CREATE TABLE ' . self::TABLE_USERS . ' (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            phone VARCHAR(20) NOT NULL UNIQUE,
            group_id INT NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
            UNIQUE INDEX UNIQ_USERS_EMAIL (email),
            UNIQUE INDEX UNIQ_USERS_PHONE (phone),
            CONSTRAINT FK_USERS_GROUP FOREIGN KEY (group_id) REFERENCES ' . self::TABLE_USER_GROUPS . ' (id) ON DELETE CASCADE
        )');

        $this->addSql('CREATE TABLE ' . self::TABLE_ORDERS . ' (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            notification_type VARCHAR(255),
            total_cost INT NOT NULL,
            delivery_address VARCHAR(255) DEFAULT NULL,
            delivery_type VARCHAR(255) NOT NULL,
            kladr_id INT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
            INDEX IDX_ORDERS_USER (user_id),
            CONSTRAINT FK_ORDERS_USER FOREIGN KEY (user_id) REFERENCES ' . self::TABLE_USERS . ' (id) ON DELETE CASCADE
        )');

        $this->addSql('CREATE TABLE ' . self::TABLE_CARTS . ' (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
            UNIQUE INDEX IDX_CARTS_USER (user_id),
            CONSTRAINT FK_CARTS_USER FOREIGN KEY (user_id) REFERENCES ' . self::TABLE_USERS . ' (id) ON DELETE CASCADE
        )');

        $this->addSql('CREATE TABLE ' . self::TABLE_CART_ITEMS . ' (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cart_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
            INDEX IDX_CART_ITEMS_CART (cart_id),
            INDEX IDX_CART_ITEMS_PRODUCT (product_id),
            CONSTRAINT FK_CART_ITEMS_CART FOREIGN KEY (cart_id) REFERENCES ' . self::TABLE_CARTS . ' (id) ON DELETE CASCADE,
            CONSTRAINT FK_CART_ITEMS_PRODUCT FOREIGN KEY (product_id) REFERENCES ' . self::TABLE_PRODUCTS . ' (id) ON DELETE CASCADE
        )');

        $this->addSql('CREATE TABLE ' . self::TABLE_ORDER_ITEMS . ' (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            price INT NOT NULL,
            quantity INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
            INDEX IDX_ORDER_ITEMS_ORDER (order_id),
            INDEX IDX_ORDER_ITEMS_PRODUCT (product_id),
            CONSTRAINT FK_ORDER_ITEMS_ORDER FOREIGN KEY (order_id) REFERENCES ' . self::TABLE_ORDERS . ' (id) ON DELETE CASCADE,
            CONSTRAINT FK_ORDER_ITEMS_PRODUCT FOREIGN KEY (product_id) REFERENCES ' . self::TABLE_PRODUCTS . ' (id) ON DELETE CASCADE
        )');

        $this->addSql('CREATE TABLE ' . self::TABLE_ORDER_STATUS_TRACKING . ' (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            old_status VARCHAR(255) NOT NULL,
            new_status VARCHAR(255) NOT NULL,
            created_by INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            INDEX IDX_ORDER_STATUS_TRACKING_ORDER (order_id),
            CONSTRAINT FK_ORDER_STATUS_TRACKING_ORDER FOREIGN KEY (order_id) REFERENCES ' . self::TABLE_ORDERS . ' (id) ON DELETE CASCADE,
            CONSTRAINT FK_ORDER_STATUS_TRACKING_CREATED_BY FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE RESTRICT
        )');

        $this->addSql('CREATE TABLE ' . self::TABLE_REPORTS . ' (
            uuid VARCHAR(36) NOT NULL PRIMARY KEY,
            status VARCHAR(255) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE ' . self::TABLE_ORDER_STATUS_TRACKING);
        $this->addSql('DROP TABLE ' . self::TABLE_ORDER_ITEMS);
        $this->addSql('DROP TABLE ' . self::TABLE_CART_ITEMS);
        $this->addSql('DROP TABLE ' . self::TABLE_CARTS);
        $this->addSql('DROP TABLE ' . self::TABLE_ORDERS);
        $this->addSql('DROP TABLE ' . self::TABLE_USERS);
        $this->addSql('DROP TABLE ' . self::TABLE_PRODUCTS);
        $this->addSql('DROP TABLE ' . self::TABLE_REPORTS);
        $this->addSql('DROP TABLE ' . self::TABLE_USER_GROUPS);
    }
}
