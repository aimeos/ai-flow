<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014
 */


namespace Aimeos\MW\View\Helper\Url;


/**
 * Test class for \Aimeos\MW\View\Helper\Url\Flow.
 */
class FlowTest extends \PHPUnit_Framework_TestCase
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
		if( !class_exists( '\\TYPO3\\Flow\\Mvc\\Routing\\UriBuilder' ) ) {
			$this->markTestSkipped( '\\TYPO3\\Flow\\Mvc\\Routing\\UriBuilder is not available' );
		}

		$view = new \Aimeos\MW\View\Standard();

		$mockHttpRequest = $this->getMockBuilder( 'TYPO3\Flow\Http\Request' )
			->disableOriginalConstructor()
			->setMethods( array( 'getBaseUri' ) )
			->getMock();
		$mockHttpRequest->expects( $this->any() )
			->method( 'getBaseUri' )
			->will( $this->returnValue( 'http://localhost/' ) );

		$mockMainRequest = $this->getMock( 'TYPO3\Flow\Mvc\ActionRequest', array( 'getControllerObjectName' ), array( $mockHttpRequest ) );
		$mockMainRequest->expects( $this->any() )
			->method( 'getArgumentNamespace' )
			->will( $this->returnValue( 'ai' ) );

		$this->mockRouter = $this->getMock( 'TYPO3\Flow\Mvc\Routing\Router' );
		$mockEnv = $this->getMock( 'TYPO3\Flow\Utility\Environment', array( 'isRewriteEnabled' ), array(), '', false );

		$builder = new \TYPO3\Flow\Mvc\Routing\UriBuilder();
		$builder->setRequest( $mockMainRequest );

		$objectReflection = new \ReflectionObject( $builder );

		$property = $objectReflection->getProperty( 'router' );
		$property->setAccessible( true );
		$property->setValue( $builder, $this->mockRouter );

		$property = $objectReflection->getProperty( 'environment' );
		$property->setAccessible( true );
		$property->setValue( $builder, $mockEnv );

		$this->object = new \Aimeos\MW\View\Helper\Url\Flow( $view, $builder );
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

		$this->assertEquals( '/index.php/shop/catalog/lists#a/b', $this->object->transform( 'shop', 'catalog', 'lists', array(), array( 'a', 'b' ) ) );
	}


	public function testTransformAbsolute()
	{
		$this->mockRouter->expects( $this->once() )->method( 'resolve' )
			->will( $this->returnValue( 'shop/catalog/lists') );

		$options = array( 'absoluteUri' => true );
		$result = $this->object->transform( 'shop', 'catalog', 'lists', array(), array(), $options );

		$this->assertEquals( 'http://localhost/index.php/shop/catalog/lists', $result );
	}
}
