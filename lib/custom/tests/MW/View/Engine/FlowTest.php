<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

namespace Aimeos\MW\View\Engine;


class Typo3Test extends \PHPUnit_Framework_TestCase
{
	private $object;
	private $mock;


	protected function setUp()
	{
		$this->mock = $this->getMockBuilder( '\TYPO3\Fluid\View\StandaloneView' )
			->setMethods( array( 'assign', 'assignMultiple', 'render', 'setTemplatePathAndFilename' ) )
			->disableOriginalConstructor()
			->getMock();

		$this->object = new \Aimeos\MW\View\Engine\Flow( $this->mock );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->mock );
	}


	public function testRender()
	{
		$v = new \Aimeos\MW\View\Standard( array() );

		$this->mock->expects( $this->once() )->method( 'setTemplatePathAndFilename' );
		$this->mock->expects( $this->once() )->method( 'assignMultiple' );
		$this->mock->expects( $this->once() )->method( 'assign' );
		$this->mock->expects( $this->once() )->method( 'render' )
			->will( $this->returnValue( 'test' ) );

		$result = $this->object->render( $v, 'filepath', array( 'key' => 'value' ) );
		$this->assertEquals( 'test', $result );
	}
}
