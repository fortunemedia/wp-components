<?php
/**
 * Class file for the Social Links component.
 *
 * @package WP_Components
 */

namespace WP_Components;

/**
 * Defines the Social Item component.
 */
class Social_Links extends Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'social-links';

	/**
	 * Define a default config shape.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'services'      => [],
			'display_icons' => true,
		];
	}

	/**
	 * Retrieve service labels for use in custom fields.
	 *
	 * @param  array $link_configs Array of configs to use for creating new social item components.
	 * @return self
	 */
	public function create_link_components( $link_configs ) : self {
		foreach ( $this->config['services'] as $service => $enabled ) {
			if ( (bool) $enabled && ! empty( $link_configs[ $service ] ) ) {
				$this->append_child( ( new Social_Item() )->merge_config( $link_configs[ $service ] ) );
			}
		}
		return $this;
	}
}
