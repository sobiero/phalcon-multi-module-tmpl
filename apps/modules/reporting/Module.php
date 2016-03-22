<?php
namespace Pims\Reporting;

use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\DiInterface;
use Phalcon\Mvc\View;
use Phalcon\Events\Manager as EventsManager;
use Pims\Reporting\Plugins\PermissionsPlugin;

class Module
{


	public function registerAutoloaders()
	{

		$loader = new Loader();

		$loader->registerNamespaces(array(
			'Pims\Reporting'   => APP_PATH .'apps/modules/reporting/classes/',
			'Pims\Common'      => APP_PATH .'apps/common/classes/',
		));

		$loader->register();
	}

	/**
	 * Register the services here to make them general or register in the Module
	 * Definition to make them module-specific
	 */
	public function registerServices(DiInterface $di)
	{		
				
		$dispatcher = $di->get("dispatcher");
		
		$di->set('dispatcher', function() use ($dispatcher) {	
			
			$eventsManager = new EventsManager;
	        $eventsManager->attach('dispatch:permissions', new PermissionsPlugin);	

	        //$dispatcher->setEventsManager($eventsManager);	

			$dispatcher->setDefaultNamespace("Pims\Reporting\Controllers");
			return $dispatcher;
		});

		
		$di->set('view', function () {
		    $view = new \Phalcon\Mvc\View();
		    $view->setViewsDir(APP_PATH .'apps/modules/reporting/views/');	
		    $view->setLayoutsDir('../../../common/views/layout/');		    
		    $view->setTemplateAfter('base');		    

		    return $view;
		});
		

		$commonv = $di->get("commonv");

		$di->set('commonv', function() use ($commonv) {						
			$commonv->setViewsDir(APP_PATH .'apps/modules/reporting/views/');        
	        $commonv->setPartialsDir('partials/');
	        return $commonv;
		});
				
	}

}
