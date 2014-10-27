<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014
 */


/**
 * Test class for MW_View_Helper_Url_Flow.
 */
class MW_View_Helper_Url_FlowTest extends MW_Unittest_Testcase
{
	private $_object;


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

		$view = new MW_View_Default();

		$mockHttpRequest = $this->getMockBuilder( 'TYPO3\Flow\Http\Request' )
			->disableOriginalConstructor()
			->setMethods( array( 'getBaseUri' ) )
			->getMock();
		$mockHttpRequest->expects( $this->any() )
			->method( 'getBaseUri' )
			->will( $this->returnValue( 'http://localhost/' ) );

		$mockMainRequest = $this->getMock( 'TYPO3\Flow\Mvc\ActionRequest', array( 'isDispatched' ), array( $mockHttpRequest ) );
		$mockMainRequest->expects( $this->any() )
			->method( 'getArgumentNamespace' )
			->will( $this->returnValue( 'ai' ) );

		$mockRouter = $this->getMock( 'TYPO3\Flow\Mvc\Routing\Router' );
		$mockEnv = $this->getMock( 'TYPO3\Flow\Utility\Environment', array( 'isRewriteEnabled' ), array(), '', false );

		$builder = new \TYPO3\Flow\Mvc\Routing\UriBuilder();
		$builder->setRequest( $mockMainRequest );

		$objectReflection = new \ReflectionObject( $builder );

		$property = $objectReflection->getProperty( 'router' );
		$property->setAccessible( true );
		$property->setValue( $builder, $mockRouter );

		$property = $objectReflection->getProperty( 'environment' );
		$property->setAccessible( true );
		$property->setValue( $builder, $mockEnv );

		$this->_object = new MW_View_Helper_Url_Flow( $view, $builder );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		$this->_object = null;
	}


	public function testTransform()
	{
		$this->assertEquals( '/index.php/shop', $this->_object->transform( '/shop', 'catalog', 'list' ) );
	}


	public function testTransformSlashes()
	{
		$this->assertEquals( '/index.php/shop?test=a%2Fb', $this->_object->transform( '/shop', 'catalog', 'list', array( 'test' => 'a/b' ) ) );
	}


	public function testTransformArrays()
	{
		$this->assertEquals( '/index.php/shop?test%5B0%5D=a&test%5B1%5D=b', $this->_object->transform( '/shop', 'catalog', 'list', array( 'test' => array( 'a', 'b' ) ) ) );
	}


	public function testTransformTrailing()
	{
		$this->assertEquals( '/index.php/shop#a/b', $this->_object->transform( '/shop', 'catalog', 'list', array(), array( 'a', 'b' ) ) );
	}


	public function testTransformAbsolute()
	{
		$options = array( 'absoluteUri' => true );
		$result = $this->_object->transform( '/shop', 'catalog', 'list', array(), array(), $options );
		$this->assertEquals( 'http://localhost/index.php/shop', $result );
	}
}
