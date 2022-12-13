<?php
namespace User\Controller;

use User\Model\UserToDepartmentTable;
use User\Model\UserTable;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class DepartmentController extends AbstractActionController
{
    private $usersTable;
    private $departmentsTable;

    public function __construct(UserTable $usersTable, UserToDepartmentTable $departmentsTable)
    {
      $this->usersTable = $usersTable;
      $this->departmentsTable = $departmentsTable;
    }

    public function indexAction() {

      $id = (int) $this->params()->fromRoute('id', 0);
      if (!$id) {
          return $this->redirect()->toRoute('user');
      }

      try {
        $user = $this->usersTable->getUser($id);
      } catch (\Exception $e) {
          return $this->redirect()->toRoute('user', ['action' => 'index']);
      }

      $userDepartments = $this->departmentsTable->fetchUserDepartments($id);
      $otherDepartments = $this->departmentsTable->fetchOtherDepartments($id);

      return ['id' => $id, 'user' => $user, 'userDepartments' => $userDepartments, 'otherDepartments' => $otherDepartments];
    }

    public function createAction() {

      $id = (int) $this->params()->fromRoute('id', 0);

      if (!$id) {
          return $this->redirect()->toRoute('user');
      }

      $request = $this->getRequest();

      if ( $request->isPost()) {

        $departments = $request->getPost('departments');

        if ($departments) {

          foreach($departments as $department) {
            $this->departmentsTable->saveDepartment((int)$department, $id);
          }
        }
      }

      return $this->redirect()->toRoute('user-department', ['id' => $id]);
    }

    public function deleteAction()
    {
        $userId = (int) $this->params()->fromRoute('id', 0);

        $departmentId = (int)$this->params()->fromQuery('department', 0);

        if (!$userId) {
            return $this->redirect()->toRoute('user');
        }

        if (!$departmentId) {
          return $this->redirect()->toRoute('user');
        }

        $this->departmentsTable->deleteDepartment($departmentId, $userId);

        return $this->redirect()->toRoute('user-department', ['id' => $userId]);
    }

}