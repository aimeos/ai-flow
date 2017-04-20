<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */


namespace Aimeos\MW\View\Helper\Response;


class FlowTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp()
	{
		if( !class_exists( '\Zend\Diactoros\Response' ) ) {
			$this->markTestSkipped( '\Zend\Diactoros\Response is not available' );
		}

		$view = new \Aimeos\MW\View\Standard();
		$this->object = new \Aimeos\MW\View\Helper\Response\Flow( $view );
	}


	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testTransform()
	{
		$this->assertInstanceOf( '\Aimeos\MW\View\Helper\Response\Flow', $this->object->transform() );
	}
}
