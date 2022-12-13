<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

use Laminas\Session\Container;


class LangController extends AbstractActionController
{

    public function indexAction() {

      return new ViewModel();
    }

    public function updateAction()
    {

        $localSession = new Container('local');

        $localSession->lang = $this->params()->fromQuery('lang', 'en_US');

        return $this->redirect()->toRoute('user');
    }
}