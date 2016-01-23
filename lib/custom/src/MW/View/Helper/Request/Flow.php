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
	extends \Aimeos\MW\View\Helper\Request\Standard
	implements \Aimeos\MW\View\Helper\Request\Iface
{
	private $request;
	private $files;


	/**
	 * Initializes the request view helper.
	 *
	 * @param \\Aimeos\MW\View\Iface $view View instance with registered view helpers
	 * @param \TYPO3\Flow\Http\Request $request Flow request object
	 * @param array $files Uploaded files from $_FILES
	 */
	public function __construct( \Aimeos\MW\View\Iface $view, \TYPO3\Flow\Http\Request $request, array $files )
	{
		\Aimeos\MW\View\Helper\Base::__construct( $view );

		$this->request = $request;
		$this->files = $files;
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


	/**
	 * Returns the current page or route name
	 *
	 * @return string|null Current page or route name
	 */
	public function getTarget()
	{
		return null;
	}


	/**
	 * Retrieve normalized file upload data.
	 *
	 * This method returns upload metadata in a normalized tree, with each leaf
	 * an instance of Psr\Http\Message\UploadedFileInterface.
	 *
	 * These values MAY be prepared from $_FILES or the message body during
	 * instantiation, or MAY be injected via withUploadedFiles().
	 *
	 * @return array An array tree of UploadedFileInterface instances; an empty
	 *     array MUST be returned if no data is present.
	 */
	public function getUploadedFiles()
	{
		$list = array();

		foreach( $this->files as $name => $array ) {
			$list[$name] = $this->createUploadedFiles( $array );
		}

		return $list;
	}
}
