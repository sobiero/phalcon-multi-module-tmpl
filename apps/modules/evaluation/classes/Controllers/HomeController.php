<?php

namespace Pims\Evaluation\Controllers;

use Phalcon\Mvc\Controller;

class HomeController extends Controller
{

	public function indexAction()
	{
		//$this->view->disable();
		echo ' Welcome Evaluation Module Controller Home Action Index ';
		echo '<br>', __METHOD__;
		
	}
}
