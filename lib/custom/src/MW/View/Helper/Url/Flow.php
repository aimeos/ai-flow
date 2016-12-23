<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014
 * @package MW
 * @subpackage View
 */


namespace Aimeos\MW\View\Helper\Url;


/**
 * View helper class for generating URLs using the Flow URI builder.
 *
 * @package MW
 * @subpackage View
 */
class Flow
	extends \Aimeos\MW\View\Helper\Url\Base
	implements \Aimeos\MW\View\Helper\Url\Iface
{
	private $builder;


	/**
	 * Initializes the URL view helper.
	 *
	 * @param \Aimeos\MW\View\Iface $view View instance with registered view helpers
	 * @param \TYPO3\Flow\Mvc\Routing\UriBuilder $builder Flow URI builder object
	 * @param array Associative list of fixed parameters that should be available for all routes
	 */
	public function __construct( $view, \TYPO3\Flow\Mvc\Routing\UriBuilder $builder, array $fixed )
	{
		parent::__construct( $view );

		$this->builder = $builder;
		$this->fixed = $fixed;
	}


	/**
	 * Returns the URL assembled from the given arguments.
	 *
	 * @param string|null $target Route or page which should be the target of the link (if any)
	 * @param string|null $controller Name of the controller which should be part of the link (if any)
	 * @param string|null $action Name of the action which should be part of the link (if any)
	 * @param array $params Associative list of parameters that should be part of the URL
	 * @param array $trailing Trailing URL parts that are not relevant to identify the resource (for pretty URLs)
	 * @param array $config Additional configuration parameter per URL
	 * @return string Complete URL that can be used in the template
	 */
	public function transform( $target = null, $controller = null, $action = null, array $params = array(), array $trailing = array(), array $config = array() )
	{
		$params = $this->sanitize( $params );
		$values = $this->getValues( $config );

		$this->builder
			->reset()
			->setSection( join( '/', $trailing ) )
			->setCreateAbsoluteUri( $values['absoluteUri'] )
			->setFormat( $values['format'] );

		$params['node'] = $target;

		return $this->builder->uriFor( $action, $params + $this->fixed, $controller, $values['package'], $values['subpackage'] );
	}


	/**
	 * Returns the sanitized configuration values.
	 *
	 * @param array $config Associative list of key/value pairs
	 * @return array Associative list of sanitized key/value pairs
	 */
	protected function getValues( array $config )
	{
		$values = array(
			'package' => 'Aimeos.Shop',
			'subpackage' => null,
			'absoluteUri' => false,
			'format' => 'html',
		);

		if( isset( $config['package'] ) ) {
			$values['package'] = (string) $config['package'];
		}

		if( isset( $config['subpackage'] ) ) {
			$values['subpackage'] = (string) $config['subpackage'];
		}

		if( isset( $config['absoluteUri'] ) ) {
			$values['absoluteUri'] = (bool) $config['absoluteUri'];
		}

		if( isset( $config['format'] ) ) {
			$values['format'] = (string) $config['format'];
		}

		return $values;
	}
}