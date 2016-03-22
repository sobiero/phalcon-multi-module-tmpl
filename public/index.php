<?php
namespace Pims;

error_reporting(E_ALL);
define('APP_PATH', realpath('..') . '/');
date_default_timezone_set('Africa/Nairobi');

use Phalcon\Loader;
use Phalcon\Mvc\Router;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\Application as BaseApplication;
use Phalcon\Mvc\Dispatcher as PhDispatcher;
use Phalcon\Db\Adapter\Pdo\Mysql as MySQLAdapter;
use Pims\Library\SecurityPlugin;

class Application extends BaseApplication
{
	
	/**
	 * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
	 */
	protected function registerServices()
	{

		$di = new FactoryDefault();

		$loader = new Loader();

		$loader->registerNamespaces(array(			
			'Pims\Library'      => APP_PATH . 'apps/library/',
			'Pims\Common'      =>  APP_PATH . 'apps/common/classes/',
		));

		/**
		 * Read configuration
		 */
		//$config = include __DIR__ . "/../apps/config/config.php";

		$config = new \Phalcon\Config\Adapter\Php(APP_PATH . 'apps/config/config.php'); 
		$di->set('config', $config);


		/**
		 * We're a registering a set of directories taken from the configuration file
		 */
		$loader->registerDirs(
			array(
				APP_PATH . 'apps/library/',
				APP_PATH . 'apps/config',
				APP_PATH . 'apps/common/controllers'
			)
		)->register();

		/**
	     * MVC dispatcher
	     */
	    $di->setShared('dispatcher', function () {

	        $eventsManager = new EventsManager;

	        /**
	         * Check if the user is logged in
	         */
	        $eventsManager->attach('dispatch:beforeDispatchLoop', new SecurityPlugin);

	        /**
	         * Handle exceptions and not-found exceptions using NotFoundPlugin
	         */
	        //$eventsManager->attach('dispatch:beforeException', new \Pims\Library\NotFoundPlugin);

	        $dispatcher = new PhDispatcher;
	        
	        $dispatcher->setEventsManager($eventsManager);

	        return $dispatcher;
	    });
		
				

		$di->set('view', function() {
	        $view = new View(); 
	        return $view;
	    }, true);

		$di->set('commonv', function() {
	        $view = new View(); 
	        return $view;
	    }, true);


		//Registering a router
		$di->set('router', function(){

			$router = new Router();

			$router->removeExtraSlashes(true);

			$router->setDefaultModule("main");

			$router->add('/', array(
				'module'     => 'main',
				'controller' => 'home',
				'action'     => 'index',
			));

			$router->add('/:module', array(
				'module'     => 1,
				'controller' => 'home',
				'action'     => 'index',
			));

			$router->add('/:module/:controller', array(
				'module'     => 1,
				'controller' => 2,
				'action'     => 'index',
			));

			$router->add('/:module/:controller/:action/:params', array(
				'module'     => 1,
				'controller' => 2,
				'action'     => 3,
				'params'     => 4
			));
			
			return $router;

		});

		$di->set('assets', function() {
            $assets = new \Phalcon\Assets\Manager();
            $assets
                ->collection('js')
                ->addJs('plugins/jQuery/jQuery-2.1.4.min.js', true)
                ->addJs('bootstrap/js/bootstrap.min.js', true)
                ->addJs('js/app.min.js', true)
                ->addJs('plugins/slimScroll/jquery.slimscroll.min.js', true)
                ->addJs('plugins/fastclick/fastclick.min.js', true)
                ->addJs('js/demo.js', true)
            ;
            $assets
                ->collection('css')
                ->addCss('bootstrap/css/bootstrap.min.css', true, true)
                ->addCss('css/AdminLTE.min.css', true, true)
                ->addCss('css/skins/_all-skins.min.css', true, true)
                ->addCss('css/custom.css', true, true)
            ;

            return $assets;
        });

        $di->set('url', function() use ($config) {
			$url = new \Phalcon\Mvc\Url();
			$url->setBaseUri( $config->app->base_url );
			return $url;
		});

		

	    /**
	     * Start the session the first time some component request the session service
	     */
	    
	    $di->setShared('session', function () {		
	        $session = new SessionAdapter();        
			$session->start();
	        return $session;
	    });

	    

	    /**
		 * Database connection is created based in the parameters defined in the configuration file
		 */		
		$di->set('db', function () use ($config) {	    	
	        $db = new MySQLAdapter([
	        		"host" => $config->db->host,
					"username"  => $config->db->username,
					"password"  => $config->db->password,
					"dbname"    => $config->db->name,
					"options"   => [
						\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
					]
	        	]
	        );

	        return $db;
	    });


		$this->setDI($di);
	}

	public function main()
	{

		try 
		{

			$this->registerServices();

			//Register the installed modules
			$this->registerModules(array(
				'main' => array(
					'className' => 'Pims\Main\Module',
					'path' => APP_PATH .'apps/main/Module.php'
				),
				'evaluation' => array(
					'className' => 'Pims\Evaluation\Module',
					'path' => APP_PATH .'apps/modules/evaluation/Module.php'
				),
				'reporting' => array(
					'className' => 'Pims\Reporting\Module',
					'path' => APP_PATH .'apps/modules/reporting/Module.php'
				),
				'test' => array(
					'className' => 'Pims\Test\Module',
					'path' => APP_PATH .'apps/modules/test/Module.php'
				)
			));

			echo $this->handle()->getContent();

		} catch (\Exception $e) {
			echo 'Exception: ', $e->getMessage();
			echo '<pre>' . $e->getTraceAsString() . '</pre>';
		}
	}

}


$application = new Application();
$application->main();