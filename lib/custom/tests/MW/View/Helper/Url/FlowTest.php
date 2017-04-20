<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014-2016
 */


namespace Aimeos\MW\View\Helper\Url;


/**
 * Test class for \Aimeos\MW\View\Helper\Url\Flow.
 */
class FlowTest extends \PHPUnit\Framework\TestCase
{
	private $object;
	private $mockRouter;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		if( !class_exists( '\\Neos\\Flow\\Mvc\\Routing\\UriBuilder' ) ) {
			$this->markTestSkipped( '\\Neos\\Flow\\Mvc\\Routing\\UriBuilder is not available' );
		}

		$view = new \Aimeos\MW\View\Standard();

		$mockHttpRequest = $this->getMockBuilder( 'Neos\Flow\Http\Request' )
			->setMethods( array( 'getBaseUri' ) )
			->disableOriginalConstructor()
			->getMock();
		$mockHttpRequest->expects( $this->any() )
			->method( 'getBaseUri' )
			->will( $this->returnValue( 'http://localhost/' ) );

		$mockMainRequest = $this->getMockBuilder( 'Neos\Flow\Mvc\ActionRequest' )
			->setMethods( array( 'getControllerObjectName', 'getArgumentNamespace' ) )
			->setConstructorArgs( array( $mockHttpRequest ) )
			->getMock();
		$mockMainRequest->expects( $this->any() )
			->method( 'getArgumentNamespace' )
			->will( $this->returnValue( 'ai' ) );

		$this->mockRouter = $this->getMockBuilder( 'Neos\Flow\Mvc\Routing\Router' )->getMock();
		$mockEnv = $this->getMockBuilder( 'Neos\Flow\Utility\Environment' )
			->setMethods( array( 'isRewriteEnabled' ) )
			->disableOriginalConstructor()
			->getMock();

		$builder = new \Neos\Flow\Mvc\Routing\UriBuilder();
		$builder->setRequest( $mockMainRequest );

		$objectReflection = new \ReflectionObject( $builder );

		$property = $objectReflection->getProperty( 'router' );
		$property->setAccessible( true );
		$property->setValue( $builder, $this->mockRouter );

		$property = $objectReflection->getProperty( 'environment' );
		$property->setAccessible( true );
		$property->setValue( $builder, $mockEnv );

		$this->object = new \Aimeos\MW\View\Helper\Url\Flow( $view, $builder, array( 'site' => 'unittest' ) );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		$this->object = null;
	}


	public function testTransform()
	{
		$this->mockRouter->expects( $this->once() )->method( 'resolve' )
			->will( $this->returnValue( 'shop/catalog/lists') );

		$this->assertEquals( '/index.php/shop/catalog/lists', $this->object->transform( 'shop', 'catalog', 'lists' ) );
	}


	public function testTransformSlashes()
	{
		$this->mockRouter->expects( $this->once() )->method( 'resolve' )
			->will( $this->returnValue( 'shop/catalog/lists?test=a%2Fb') );

		$this->assertEquals( '/index.php/shop/catalog/lists?test=a%2Fb', $this->object->transform( 'shop', 'catalog', 'lists', array( 'test' => 'a/b' ) ) );
	}


	public function testTransformArrays()
	{
		$this->mockRouter->expects( $this->once() )->method( 'resolve' )
			->will( $this->returnValue( 'shop/catalog/list?test%5B0%5D=a&test%5B1%5D=b') );

		$this->assertEquals( '/index.php/shop/catalog/list?test%5B0%5D=a&test%5B1%5D=b', $this->object->transform( 'shop', 'catalog', 'lists', array( 'test' => array( 'a', 'b' ) ) ) );
	}


	public function testTransformTrailing()
	{
		$this->mockRouter->expects( $this->once() )->method( 'resolve' )
			->will( $this->returnValue( 'shop/catalog/lists') );

		$this->assertEquals( '/index.php/shop/catalog/lists#a/b', $this->object->transform( 'shop', 'catalog', 'lists', [], array( 'a', 'b' ) ) );
	}


	public function testTransformAbsolute()
	{
		$this->mockRouter->expects( $this->once() )->method( 'resolve' )
			->will( $this->returnValue( 'shop/catalog/lists') );

		$options = array( 'absoluteUri' => true );
		$result = $this->object->transform( 'shop', 'catalog', 'lists', [], [], $options );

		$this->assertEquals( 'http://localhost/index.php/shop/catalog/lists', $result );
	}


	public function testTransformConfig()
	{
		$this->mockRouter->expects( $this->once() )->method( 'resolve' )
			->will( $this->returnValue( 'shop/catalog/lists') );

		$options = array( 'package' => 'test', 'subpackage' => 'subtest', 'format' => 'fmt' );
		$result = $this->object->transform( 'shop', 'catalog', 'lists', [], [], $options );

		$this->assertEquals( '/index.php/shop/catalog/lists', $result );
	}
}
