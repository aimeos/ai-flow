<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014-2018
 */


namespace Aimeos\MW\View\Helper\Url;


class FlowTest extends \PHPUnit\Framework\TestCase
{
	private $object;
	private $buildMock;


	protected function setUp()
	{
		if( !class_exists( '\\Neos\\Flow\\Mvc\\Routing\\UriBuilder' ) ) {
			$this->markTestSkipped( '\\Neos\\Flow\\Mvc\\Routing\\UriBuilder is not available' );
		}

		$view = new \Aimeos\MW\View\Standard();

		$this->buildMock = $this->getMockbuilder( '\\Neos\\Flow\\Mvc\\Routing\\UriBuilder' )
			->setMethods( array( 'uriFor' ) )
			->disableOriginalConstructor()
			->getMock();

		$this->object = new \Aimeos\MW\View\Helper\Url\Flow( $view, $this->buildMock, array( 'site' => 'unittest' ) );
	}


	protected function tearDown()
	{
		$this->object = null;
	}


	public function testTransform()
	{
		$this->buildMock->expects( $this->once() )->method( 'uriFor' )
			->will( $this->returnValue( '/shop/catalog/lists') );

		$this->assertEquals( '/shop/catalog/lists', $this->object->transform( 'shop', 'catalog', 'lists' ) );
	}


	public function testTransformSlashes()
	{
		$this->buildMock->expects( $this->once() )->method( 'uriFor' )
			->will( $this->returnValue( '/shop/catalog/lists?test=a%2Fb') );

		$this->assertEquals( '/shop/catalog/lists?test=a%2Fb', $this->object->transform( 'shop', 'catalog', 'lists', array( 'test' => 'a/b' ) ) );
	}


	public function testTransformArrays()
	{
		$this->buildMock->expects( $this->once() )->method( 'uriFor' )
			->will( $this->returnValue( '/shop/catalog/list?test%5B0%5D=a&test%5B1%5D=b') );

		$this->assertEquals( '/shop/catalog/list?test%5B0%5D=a&test%5B1%5D=b', $this->object->transform( 'shop', 'catalog', 'lists', array( 'test' => array( 'a', 'b' ) ) ) );
	}


	public function testTransformTrailing()
	{
		$this->buildMock->expects( $this->once() )->method( 'uriFor' )
			->will( $this->returnValue( '/shop/catalog/lists#a/b') );

		$this->assertEquals( '/shop/catalog/lists#a/b', $this->object->transform( 'shop', 'catalog', 'lists', [], array( 'a', 'b' ) ) );
	}


	public function testTransformAbsolute()
	{
		$this->buildMock->expects( $this->once() )->method( 'uriFor' )
			->will( $this->returnValue( 'http://localhost/shop/catalog/lists') );

		$options = array( 'absoluteUri' => true );
		$result = $this->object->transform( 'shop', 'catalog', 'lists', [], [], $options );

		$this->assertEquals( 'http://localhost/shop/catalog/lists', $result );
	}


	public function testTransformConfig()
	{
		$this->buildMock->expects( $this->once() )->method( 'uriFor' )
			->will( $this->returnValue( '/shop/catalog/lists') );

		$options = array( 'package' => 'test', 'subpackage' => 'subtest', 'format' => 'fmt' );
		$result = $this->object->transform( 'shop', 'catalog', 'lists', [], [], $options );

		$this->assertEquals( '/shop/catalog/lists', $result );
	}
}
