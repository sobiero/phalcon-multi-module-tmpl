<?php
namespace Pims\Evaluation;

use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\DiInterface;

class Module
{

	public function registerAutoloaders()
	{

		$loader = new Loader();

		$loader->registerNamespaces(array(
			'Pims\Evaluation' => APP_PATH .'apps/modules/evaluation/classes/',
		));

		$loader->register();
	}

	/**
	 * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
	 */
	public function registerServices(DiInterface $di)
	{

		//Registering a dispatcher
		$di->set('dispatcher', function() {
			$dispatcher = new Dispatcher();
			$dispatcher->setDefaultNamespace("Pims\Evaluation\Controllers");
			return $dispatcher;
		});		
	}

}
