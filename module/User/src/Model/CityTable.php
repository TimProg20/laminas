<?php 
namespace User\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class CityTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }
}