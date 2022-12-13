<?php 
namespace User\Model;

use RuntimeException;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;

class UserToDepartmentTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchUserDepartments($idUser)
    {

        return $this->tableGateway->select(function (Select $select) use($idUser) {
            $select->columns([])
            ->join(
                'departments',
                "departments.id = users_to_departments.id_department",
                ['id', 'name'], 
            )
            ->where(['id_user' => $idUser])
            ->order('id DESC');
        });
    }

    public function fetchOtherDepartments($idUser)
    {

        $idUser = (int) $idUser;

        return $this->tableGateway->select(function (Select $select) use($idUser) {

            $join = new \Laminas\Db\Sql\Expression('departments.id = users_to_departments.id_department AND users_to_departments.id_user = '.$idUser.' ');

            $select->columns([])
            ->join(
                'departments',
                $join,
                ['id', 'name'], 
                $select::JOIN_RIGHT
            )
            ->where([
                'id_department' => null,
            ])
            ->order('id DESC');
        });
    }

    public function saveDepartment($departmentId, $userId) {
        $data = [
            'id_department' => $departmentId,
            'id_user' => $userId
        ];

        $rowset = $this->tableGateway->select($data);
        $row = $rowset->current();
        if ($row) {
            return;
        }

        $this->tableGateway->insert($data);
        return;
    }

    public function deleteDepartment($departmentId, $userId) {
        $data = [
            'id_department' => $departmentId,
            'id_user' => $userId
        ];

        $this->tableGateway->delete($data);
        return;
    }
}