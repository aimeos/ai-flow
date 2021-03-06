<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 * @package MW
 * @subpackage View
 */


namespace Aimeos\MW\View\Engine;


/**
 * Flow view engine implementation
 *
 * @package MW
 * @subpackage View
 */
class Flow implements Iface
{
	private $view;


	/**
	 * Initializes the view object
	 *
	 * @param \Neos\FluidAdaptor\View\StandaloneView $view Flow template view object
	 */
	public function __construct( \Neos\FluidAdaptor\View\StandaloneView $view )
	{
		$this->view = $view;
	}


	/**
	 * Renders the output based on the given template file name and the key/value pairs
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param string $filename File name of the view template
	 * @param array $values Associative list of key/value pairs
	 * @return string Output generated by the template
	 * @throws \Aimeos\MW\View\Exception If the template isn't found
	 */
	public function render( \Aimeos\MW\View\Iface $view, string $filename, array $values ) : string
	{
		$fluid = clone $this->view;

		$fluid->setTemplatePathAndFilename( $filename );
		$fluid->assign( '_aimeos_view', $view );
		$fluid->assignMultiple( $values );

		return $fluid->render();
	}
}
