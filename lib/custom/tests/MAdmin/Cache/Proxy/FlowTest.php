<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */


namespace Aimeos\MAdmin\Cache\Proxy;


class FlowTest extends \PHPUnit_Framework_TestCase
{
	private $object;
	private $mock;


	protected function setUp()
	{
		if( class_exists( 'Neos\Cache\Frontend\StringFrontend' ) === false ) {
			$this->markTestSkipped( 'Class \\Neos\\Cache\\Frontend\\StringFrontend not found' );
		}

		$this->mock = $this->getMockBuilder( 'Neos\Cache\Frontend\StringFrontend' )
			->disableOriginalConstructor()
			->getMock();

		$this->object = new \Aimeos\MAdmin\Cache\Proxy\Flow( \TestHelper::getContext(), $this->mock );
	}


	protected function tearDown()
	{
		unset( $this->mock, $this->object );
	}


	public function testGetObject()
	{
		$class = new \ReflectionClass( '\Aimeos\MAdmin\Cache\Proxy\Flow' );
		$method = $class->getMethod( 'getObject' );
		$method->setAccessible( true );

		$result = $method->invokeArgs( $this->object, [] );
		$this->assertInstanceOf( '\Aimeos\MW\Cache\Iface', $result );
	}
}
