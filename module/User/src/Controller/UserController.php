<?php

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

use Laminas\Session\Container;

use User\Model\UserTable;
use User\Model\User;

use User\Model\CityTable;

use User\Form\UserForm;
use User\Form\UserFilter;

class UserController extends AbstractActionController
{

    private $usersTable;
    private $citiesTable;

    public function __construct(UserTable $usersTable, CityTable $citiesTable) {
        $this->usersTable = $usersTable;
        $this->citiesTable = $citiesTable;
    }

    public function indexAction() {

        $localSession = new Container('local');

        return new ViewModel([
          'users' => $this->usersTable->fetchAll(),
		  'lang' => $localSession->lang,
        ]);
    }

    public function createAction() {
		$form = new UserForm($this->citiesTable->fetchAll());
		$form->get('submit')->setValue('Create');

		$request = $this->getRequest();

		if (! $request->isPost()) {
			return ['form' => $form];
		}

		$user = new User();
		$form->setInputFilter($user->getInputFilter());
		$form->setData($request->getPost());

		if (! $form->isValid()) {
			return ['form' => $form];
		}

		$user->exchangeArray($form->getData());
		$this->usersTable->saveUser($user);
		return $this->redirect()->toRoute('user');
    }

    public function updateAction() {
      
		$id = (int) $this->params()->fromRoute('id', 0);

		if (0 === $id) {
			return $this->redirect()->toRoute('user', ['action' => 'create']);
		}

		try {
			$user = $this->usersTable->getUser($id);
		} catch (\Exception $e) {
			return $this->redirect()->toRoute('user', ['action' => 'index']);
		}

		$form = new UserForm($this->citiesTable->fetchAll());;
		$form->bind($user);
		$form->get('submit')->setAttribute('value', 'Edit');

		$request = $this->getRequest();
		$viewData = ['id' => $id, 'form' => $form];

		if (! $request->isPost()) {
			return $viewData;
		}

		$form->setInputFilter($user->getInputFilter());
		$form->setData($request->getPost());

		if (! $form->isValid()) {
			return $viewData;
		}

		try {
			$this->usersTable->saveUser($user);
		} catch (\Exception $e) {
		}

		return $this->redirect()->toRoute('user', ['action' => 'index']);
    }

    public function deleteAction() {
      
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('user');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');

			if ($del == 'Yes' || $del == 'Да') {
				$id = (int) $request->getPost('id');
				$this->usersTable->deleteUser($id);
			}

			return $this->redirect()->toRoute('user');
		}

		return [
			'id'    => $id,
			'user' => $this->usersTable->getUser($id),
		];
    }
}