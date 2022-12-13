<?php
namespace User;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig() {
        return [
            'factories' => [
                Model\UserTable::class => function($container) {
                    $userTableGateway = $container->get(Model\UserTableGateway::class);
                    $userToDepartmentTableGateway = $container->get(Model\UserToDepartmentTableGateway::class);
                    return new Model\UserTable($userTableGateway, $userToDepartmentTableGateway);
                },
                Model\UserTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },
                Model\CityTable::class => function($container) {
                    $tableGateway = $container->get(Model\CityTableGateway::class);
                    return new Model\CityTable($tableGateway);
                },
                Model\CityTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\City());
                    return new TableGateway('cities', $dbAdapter, null, $resultSetPrototype);
                },
                Model\UserToDepartmentTable::class => function($container) {
                    $tableGateway = $container->get(Model\UserToDepartmentTableGateway::class);
                    return new Model\UserToDepartmentTable($tableGateway);
                },
                Model\UserToDepartmentTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new TableGateway('users_to_departments', $dbAdapter, null, null);
                },
            ],
        ];
    }

    public function getControllerConfig() {
        return [
            'factories' => [
                Controller\UserController::class => function($container) {
                    return new Controller\UserController(
                        $container->get(Model\UserTable::class),
                        $container->get(Model\CityTable::class)
                    );
                },
                Controller\DepartmentController::class => function($container) {
                    return new Controller\DepartmentController(
                        $container->get(Model\UserTable::class),
                        $container->get(Model\UserToDepartmentTable::class)
                    );
                },
            ],
        ];
    }

}