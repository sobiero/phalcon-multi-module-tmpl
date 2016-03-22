<?php

namespace Pims\Reporting\Plugins;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * PermissionsPlugin
 *
 * This is the security plugin which controls that users only have access to the actions they're assigned to
 */

class PermissionsPlugin extends Plugin
{

	/**
	 * Returns an existing or new access control list
	 *
	 * @returns AclList
	 */
	public function getAcl()
	{
		if (!isset($this->persistent->acl)) {

			$acl = new AclList();

			$acl->setDefaultAction(Acl::DENY);

			//Register roles
			$roles = array(
				'admin'  => new Role('Administrators'),
				'member'  => new Role('Members'),
				'quas'  => new Role('Qas'),
				'evaluation'  => new Role('Evaluation'),
				'smt' => new Role('Management')
			);
			foreach ($roles as $role) {
				$acl->addRole($role);
			}

			//Private area resources
			$privateResources = [				
				'home' => ['index'],
				'report' => ['list', 'detail', 'new', 'score', 'submit', 'open']
			];
			
			foreach ($privateResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}
			
			//Grant access to private area to role Administrators & Qas
			foreach ($privateResources as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('Administrators', $resource, $action);
					$acl->allow('Qas', $resource, $action);
				}
			}
			
			//grant access to members
			$acl->allow('Members', "report", "list");
			$acl->allow('Members', "report", "detail");

			/*
			//grant access to evaluation
			$acl->allow('Evaluation', "Report", "list");
			$acl->allow('Evaluation', "Report", "detail");

			//grant access to evaluation
			$acl->allow('Management', "Report", "list");
			$acl->allow('Management', "Report", "detail");
			*/

			//The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $acl;
		}

		return $this->persistent->acl;
	}

	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 * @return bool
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher)
	{
		$auth = $this->session->get('auth');

		$role = $auth['role'];
		//$role = $auth->role;

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		$acl = $this->getAcl();

		$allowed = $acl->isAllowed($role, $controller, $action);

		if ($allowed != Acl::ALLOW) {
			$dispatcher->forward(array(
				'controller' => 'home',
				'action'     => 'index'
			));

			return false;
		}
	}
}
