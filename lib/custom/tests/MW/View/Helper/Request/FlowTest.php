<?php

namespace Aimeos\MW\View\Helper\Request;


/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */
class FlowTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp()
	{
		if( !class_exists( '\Neos\Flow\Http\Request' ) ) {
			$this->markTestSkipped( '\Neos\Flow\Http\Request is not available' );
		}

		if( !class_exists( '\Zend\Diactoros\ServerRequestFactory' ) ) {
			$this->markTestSkipped( '\Zend\Diactoros\ServerRequestFactory is not available' );
		}

		$view = new \Aimeos\MW\View\Standard();
		$server = array( 'REMOTE_ADDR' => '127.0.0.1' );
		$request = new \Neos\Flow\Http\Request( [], [], [], $server );
		$this->object = new \Aimeos\MW\View\Helper\Request\Flow( $view, $request, [], [], [], [], $server );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->mock );
	}


	public function testTransform()
	{
		$this->assertInstanceOf( '\Aimeos\MW\View\Helper\Request\Flow', $this->object->transform() );
	}


	public function testGetClientAddress()
	{
		$this->assertEquals( '127.0.0.1', $this->object->getClientAddress() );
	}


	public function testGetTarget()
	{
		$this->assertEquals( null, $this->object->getTarget() );
	}
}
