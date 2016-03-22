<?php

namespace Pims\Main\Controllers;

use Pims\Common\Controllers\BaseController as Controller;

class HomeController extends Controller
{

	public function indexAction()
	{
		//$this->view->disable();
		echo ' Welcome Main Module Controller Home Action Index ';
		echo '<br>', __METHOD__;
	}
}