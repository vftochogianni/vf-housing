<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

class Version20180617193307 extends AbstractMigration
{
    const USERS_TABLE_NAME = 'users';

    public function up(Schema $schema)
    {
        $schema->createTable(self::USERS_TABLE_NAME);
        $schema->getTable(self::USERS_TABLE_NAME)->addColumn('identity', TYPE::STRING, ['length' => 36, 'unique' => true]);
        $schema->getTable(self::USERS_TABLE_NAME)->addColumn('username', TYPE::STRING, ['length' => 255]);
        $schema->getTable(self::USERS_TABLE_NAME)->addColumn('password', TYPE::STRING, ['length' => 255]);
        $schema->getTable(self::USERS_TABLE_NAME)->addColumn('email', TYPE::STRING, ['length' => 255, 'unique' => true]);
        $schema->getTable(self::USERS_TABLE_NAME)->addColumn('name', TYPE::STRING, ['length' => 255]);
        $schema->getTable(self::USERS_TABLE_NAME)->addColumn('telephone_number', TYPE::STRING, ['length' => 15]);
        $schema->getTable(self::USERS_TABLE_NAME)->addColumn('security_question', TYPE::STRING, ['length' => 255]);
        $schema->getTable(self::USERS_TABLE_NAME)->addColumn('security_answer', TYPE::STRING, ['length' => 255]);
        $schema->getTable(self::USERS_TABLE_NAME)->addColumn('created_at', TYPE::DATETIME_IMMUTABLE, ['nullable' => false]);
        $schema->getTable(self::USERS_TABLE_NAME)->addColumn('updated_at', TYPE::DATETIME, ['nullable' => false]);
        $schema->getTable(self::USERS_TABLE_NAME)->addColumn('is_enabled', TYPE::BOOLEAN, ['default' => true]);
        $schema->getTable(self::USERS_TABLE_NAME)->addIndex(['identity', 'email']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(self::USERS_TABLE_NAME);
    }
}
