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
     * @param array $configData
     * @return Connection
     */
    private function getDatabaseConnection(array $configData)
    {
        // Connection and schema setup
        $config = new Configuration();
        $connectionParams = $configData;

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