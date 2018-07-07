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
        $table = $schema->createTable(self::USERS_TABLE_NAME);

        $table->addColumn('identity', TYPE::STRING, ['length' => 36]);
        $table->addColumn('username', TYPE::STRING, ['length' => 255]);
        $table->addColumn('password', TYPE::STRING, ['length' => 255]);
        $table->addColumn('email', TYPE::STRING, ['length' => 100]);
        $table->addColumn('name', TYPE::STRING, ['length' => 255]);
        $table->addColumn('telephone_number', TYPE::STRING, ['length' => 25]);
        $table->addColumn('security_question', TYPE::STRING, ['length' => 255]);
        $table->addColumn('security_answer', TYPE::STRING, ['length' => 255]);
        $table->addColumn('created_at', TYPE::DATETIME_IMMUTABLE);
        $table->addColumn('updated_at', TYPE::DATETIME);
        $table->addColumn('is_enabled', TYPE::BOOLEAN, ['default' => 0]);

        $table->setPrimaryKey(['identity']);
        $table->addUniqueIndex(['identity', 'email']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(self::USERS_TABLE_NAME);
    }
}
