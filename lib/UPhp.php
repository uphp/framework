<?php
use \ActiveRecord\ConnectionManager;

class UPhp
{
    public static $app;
    private $connection = null;

    public function __construct()
    {
        self::$app = new self();
    }

    public function db(string $configNameDB)
    {
        if (empty($configNameDB) $this->setConnection(ConnectionManager::get_connection());
        else $this->setConnection(ConnectionManager::get_connection(null, $configNameDB));
    }

    public function executeSQL(string $sql = null, array $values = [])
    {
        if (! is_array($values)) throw new \Exception('Os valores precisam ser passado no formado de array. Ex.: ["Valor1", "Valor2], ...]');
        if (empty($sql)) throw new \Exception('SQL não informado!');

        $connection = $this->getConnection();
        if (empty($connection)) throw new \Exception('Conexão não estabelecida.');
        return $connection->query($sql, $values);
    }

    private function setConnection($connection)
    {
        $this->connection = $connection;
    }

    private function getConnection()
    {
        return $this->connection;
    }
}