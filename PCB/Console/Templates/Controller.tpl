<?php

/**
 * ---------------------------------
 * PHP CODE BOOSTER
 * ---------------------------------
 *
 * @Author: Sandip Patel
 * @package PHPCodebooster
 * @version 5.0
 * @copyright (c) 2014, Sandip Patel
 **/
namespace Controllers;

use PCB\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class ${controller} extends BaseController {

	public function index() {
		return $this->render('${namespace}/index.php', array(
				'message' => 'Welcome to PHPCodeBooster'
		));
	}
}