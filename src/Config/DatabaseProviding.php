<?php


namespace Printdeal\Voyager\Config;


use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\SchemaException;
use Prooph\EventStore\Adapter\Doctrine\Schema\EventStoreSchema;

trait DatabaseProviding
{
    private $connection;
    private $schema;

    /**
     * @return Connection
     */
    private function getDatabaseConnection()
    {
        // Connection and schema setup
        $config = new Configuration();
        $connectionParams = array(
            'dbname' => '',
            'user' => '',
            'password' => '',
            'host' => '',
            'port' => 3306,
            'driver' => 'pdo_mysql',
        );

        $connection = DriverManager::getConnection($connectionParams, $config);

        $schema = $connection->getSchemaManager()->createSchema();

        try {
            EventStoreSchema::createSingleStream($schema, 'event_stream', true);

            foreach ($schema->toSql($connection->getDatabasePlatform()) as $sql) {
                $connection->exec($sql);
            }
        } catch (SchemaException $e) {

        }

        return $connection;
    }
}