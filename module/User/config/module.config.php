<?php
namespace User;

use Laminas\Router\Http\Segment;

return [
    'router' => [
      'routes' => [
        'user' => [
          'type' => Segment::class, // exact match of URI path
          'options' => [
              'route' => '/user[/:action[/:id]]', // URI path
              'constraints' => [
                  'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                  'id'     => '[0-9]*',
              ],
              'defaults' => [
                  'controller' => Controller\UserController::class, // unique name
                  'action'     => 'index',
              ],
          ],
        ],
        'user-department' => [
          'type' => Segment::class, // exact match of URI path
          'options' => [
              'route' => '/user-department[/:action]/:id', // URI path
              'constraints' => [
                  'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                  'id'     => '[0-9]*',
              ],
              'defaults' => [
                  'controller' => Controller\DepartmentController::class, // unique name
                  'action'     => 'index',
              ],
          ],
        ],
      ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'user' => __DIR__ . '/../view',
        ],
    ],
];