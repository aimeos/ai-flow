<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014
 * @package MW
 * @subpackage View
 */


/**
 * View helper class for generating URLs using the Flow URI builder.
 *
 * @package MW
 * @subpackage View
 */
class MW_View_Helper_Url_Flow
	extends MW_View_Helper_Abstract
	implements MW_View_Helper_Interface
{
	private $_builder;


	/**
	 * Initializes the URL view helper.
	 *
	 * @param MW_View_Interface $view View instance with registered view helpers
	 * @param \TYPO3\Flow\Mvc\Routing\UriBuilder $builder Flow URI builder object
	 */
	public function __construct( $view, \TYPO3\Flow\Mvc\Routing\UriBuilder $builder )
	{
		parent::__construct( $view );

		$this->_builder = $builder;
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
		$values = $this->_getValues( $config );

		$this->_builder
			->reset()
			->setSection( join( '/', $trailing ) )
			->setCreateAbsoluteUri( $values['absoluteUri'] )
			->setFormat( $values['format'] );

		// Slashes in URL parameters confuses the builder
		foreach( $params as $key => $value ) {
			$params[$key] = str_replace( '/', '_', $value );
		}

		$params['node'] = $target;

		return $this->_builder->uriFor( $action, $params, $controller, $values['package'], $values['subpackage'] );
	}


	/**
	 * Returns the sanitized configuration values.
	 *
	 * @param array $config Associative list of key/value pairs
	 * @return array Associative list of sanitized key/value pairs
	 */
	protected function _getValues( array $config )
	{
		$values = array(
			'package' => null,
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