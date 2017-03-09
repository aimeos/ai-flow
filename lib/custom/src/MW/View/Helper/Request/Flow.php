<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 * @package MW
 * @subpackage View
 */


namespace Aimeos\MW\View\Helper\Request;

use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Stream;


/**
 * View helper class for accessing request data from Flow
 *
 * @package MW
 * @subpackage View
 */
class Flow
	extends \Aimeos\MW\View\Helper\Request\Standard
	implements \Aimeos\MW\View\Helper\Request\Iface
{
	private $request;


	/**
	 * Initializes the request view helper.
	 *
	 * @param \\Aimeos\MW\View\Iface $view View instance with registered view helpers
	 * @param \Neos\Flow\Http\Request $request Flow request object
	 * @param array $files List of uploaded files like in $_FILES
	 * @param array $query List of uploaded files like in $_GET
	 * @param array $post List of uploaded files like in $_POST
	 * @param array $cookies List of uploaded files like in $_COOKIES
	 * @param array $server List of uploaded files like in $_SERVER
	 */
	public function __construct( \Aimeos\MW\View\Iface $view, \Neos\Flow\Http\Request $request, array $files = array(),
		array $query = array(), array $post = array(), array $cookies = array(), array $server = array() )
	{
		$this->request = $request;
		$psr7request = $this->createRequest( $request, $files, $query, $post, $cookies, $server );

		parent::__construct( $view, $psr7request );
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
	 * Creates a PSR-7 compatible request
	 *
	 * @param \Neos\Flow\Http\Request $nativeRequest Flow request object
	 * @param array $files List of uploaded files like in $_FILES
	 * @param array $query List of uploaded files like in $_GET
	 * @param array $post List of uploaded files like in $_POST
	 * @param array $cookies List of uploaded files like in $_COOKIES
	 * @param array $server List of uploaded files like in $_SERVER
	 * @return \Psr\Http\Message\ServerRequestInterface PSR-7 request object
	 */
	protected function createRequest( \Neos\Flow\Http\Request $nativeRequest, array $files,
		array $query, array $post, array $cookies, array $server )
	{
		$server = ServerRequestFactory::normalizeServer( $server );
		$files = ServerRequestFactory::normalizeFiles( $files );
		$headers = $nativeRequest->getHeaders()->getAll();
		$uri = (string) $nativeRequest->getUri();
		$method = $nativeRequest->getMethod();

		$body = new Stream('php://temp', 'wb+');
		$body->write( $nativeRequest->getContent() );

		return new ServerRequest( $server, $files, $uri, $method, $body, $headers, $cookies, $query, $post );
	}
}
