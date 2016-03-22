<?php
namespace Pims\Common\Controllers;

use Phalcon\Mvc\Controller;


class BaseController extends Controller
{
	private $javascript = array();

	public function addJavaScript($script)	
    {
    	array_push($this->javascript, $script);
    	$this->view->javascript = $this->javascript;
    }

    public function getJavaScript()
    {
    	return $this->javascript;
    }	

	public function initialize()
    {
    	//$this->view->user = Profile::
        $this->view->javascript = $this->javascript;
    }
}