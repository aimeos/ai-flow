<?php

namespace Aimeos\MW\View\Helper\Request;


/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */
class FlowTest extends \PHPUnit_Framework_TestCase
{
	private $object;


	protected function setUp()
	{
		if( !class_exists( '\TYPO3\Flow\Http\Request' ) ) {
			$this->markTestSkipped( '\TYPO3\Flow\Http\Request is not available' );
		}

		if( !class_exists( '\Zend\Diactoros\ServerRequestFactory' ) ) {
			$this->markTestSkipped( '\Zend\Diactoros\ServerRequestFactory is not available' );
		}

		$view = new \Aimeos\MW\View\Standard();
		$server = array( 'REMOTE_ADDR' => '127.0.0.1' );
		$request = new \TYPO3\Flow\Http\Request( array(), array(), array(), $server );
		$this->object = new \Aimeos\MW\View\Helper\Request\Flow( $view, $request, array(), array(), array(), array(), $server );
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
