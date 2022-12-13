<?php 
namespace User\Model;

use RuntimeException;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;

class UserTable
{
    private $userTableGateway;
    private $userToDepartmentTableGateway;

    public function __construct(TableGatewayInterface $userTableGateway, TableGatewayInterface $userToDepartmentTableGateway)
    {
        $this->userTableGateway = $userTableGateway;
        $this->userToDepartmentTableGateway = $userToDepartmentTableGateway;
    }

    public function fetchAll()
    {
        return $this->userTableGateway->select(function (Select $select) {
            $select->columns([
                'id', 'name', 'surname', 'furname', 'birthday'
            ])
            ->join(
                'cities',
                'cities.id = users.id_birthplace',
                ['birthplace_name' => 'name'], 
            );
            $select->order('id DESC');
        });
    }

    public function getuser($id)
    {
        $id = (int) $id;
        $rowset = $this->userTableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveUser(User $user)
    {
        $data = [
            'name' => $user->name,
            'surname' => $user->surname,
            'furname' => $user->furname,
            'birthday' => $user->birthday,
            'id_birthplace' => $user->id_birthplace,
        ];

        $id = (int) $user->id;

        if ($id === 0) {
            $this->userTableGateway->insert($data);
            return;
        }

        try {
            $this->getUser($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update user with identifier %d; does not exist',
                $id
            ));
        }

        $this->userTableGateway->update($data, ['id' => $id]);
    }

    public function deleteUser($id)
    {

        $id = (int) $id;

        $this->userToDepartmentTableGateway->delete(['id_user' => $id]);
        $this->userTableGateway->delete(['id' => $id]);
    }
}