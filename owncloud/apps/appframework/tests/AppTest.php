<?php

/**
 * ownCloud - App Framework
 *
 * @author Bernhard Posselt
 * @copyright 2012 Bernhard Posselt nukeawhale@gmail.com
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */


namespace OCA\AppFramework;

use OCA\AppFramework\Http\Request;
use OCA\AppFramework\Core\API;
use OCA\AppFramework\Middleware\MiddlewareDispatcher;


require_once(__DIR__ . "/../3rdparty/Pimple/Pimple.php");
require_once(__DIR__ . "/classloader.php");


class AppTest extends \PHPUnit_Framework_TestCase {

	private $container;
	private $api;
	private $controller;
	private $dispatcher;
	private $params;
	private $controllerName;
	private $controllerMethod;

	protected function setUp() {
		$this->container = new \Pimple();
		$this->controller = $this->getMockBuilder(
			'OCA\AppFramework\Controller\Controller')
			->disableOriginalConstructor()
			->getMock();
		$this->dispatcher = $this->getMockBuilder(
			'OCA\AppFramework\Http\Dispatcher')
			->disableOriginalConstructor()
			->getMock();


		$this->headers = array('key' => 'value');
		$this->output = 'hi';
		$this->controllerName = 'Controller';
		$this->controllerMethod = 'method';

		$this->container[$this->controllerName] = $this->controller;
		$this->container['Dispatcher'] = $this->dispatcher;
	}


	public function testControllerNameAndMethodAreBeingPassed(){
		$return = array(null, array(), null);
		$this->dispatcher->expects($this->once())
			->method('dispatch')
			->with($this->equalTo($this->controller), 
				$this->equalTo($this->controllerMethod))
			->will($this->returnValue($return));

		$this->expectOutputString('');

		App::main($this->controllerName, $this->controllerMethod, array(),
			$this->container);
	}


	public function testOutputIsPrinted(){
		$return = array(null, array(), $this->output);
		$this->dispatcher->expects($this->once())
			->method('dispatch')
			->with($this->equalTo($this->controller), 
				$this->equalTo($this->controllerMethod))
			->will($this->returnValue($return));

		$this->expectOutputString($this->output);

		App::main($this->controllerName, $this->controllerMethod, array(),
			$this->container);
	}


	// FIXME: if someone manages to test the headers output, I'd be grateful


}