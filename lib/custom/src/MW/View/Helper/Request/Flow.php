<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package MW
 * @subpackage View
 */


namespace Aimeos\MW\View\Helper\Request;


/**
 * View helper class for generating URLs using the Laravel 5 URL builder.
 *
 * @package MW
 * @subpackage View
 */
class Flow
	extends \Aimeos\MW\View\Helper\Base
	implements \Aimeos\MW\View\Helper\Iface
{
	private $request;


	/**
	 * Initializes the request view helper.
	 *
	 * @param \\Aimeos\MW\View\Iface $view View instance with registered view helpers
	 * @param \TYPO3\Flow\Http\Request $request Flow request object
	 */
	public function __construct( \Aimeos\MW\View\Iface $view, \TYPO3\Flow\Http\Request $request )
	{
		parent::__construct( $view );

		$this->request = $request;
	}


	/**
	 * Returns the request view helper.
	 *
	 * @return \Aimeos\MW\View\Helper\Iface Request view helper
	 */
	public function transform()
	{
		return $this;
	}


	/**
	 * Returns the request body.
	 *
	 * @return string Request body
	 */
	public function getBody()
	{
		return $this->request->getContent();
	}


	/**
	 * Returns the client IP address.
	 *
	 * @return string Client IP address
	 */
	public function getClientAddress()
	{
		return $this->request->getClientIpAddress();
	}
}
