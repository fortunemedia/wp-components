<?php
/**
 * Pagination component.
 *
 * @package WP_Components
 */

namespace WP_Components;

/**
 * Pagination.
 */
class Pagination extends Component {

	use WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'pagination';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() {
		return [
			'base_url'             => '',
			'url_params_to_remove' => [],
		];
	}

	/**
	 * Hook into query being set.
	 */
	public function query_has_set() {

		// Get the pagination links for the query.
		$pagination_links = $this->get_pagination_links();

		// Validate result.
		if ( empty( $pagination_links ) ) {
			return;
		}

		// Convert each HTML link to a Pagination_Item.
		foreach ( $pagination_links as $link_html ) {
			$this->append_child(

				// Create a new pagination item using anchor HTML, and remove
				// various url params.
				( new Pagination_Item() )
					->set_from_html( $link_html )
					->remove_url_params(
						(array) $this->get_config( 'url_params_to_remove' )
					)
			);
		}

		return $this;
	}

	/**
	 * We need to carefully insert the Irving query as the global query so
	 * the various core functions reference the correct query.
	 */
	public function get_pagination_links() {
		global $wp_query;

		// Get the current global object and replace with our current query.
		$current_global_wp_query = $wp_query;

		// phpcs:ignore WordPress.WP.GlobalVariablesOverride
		$wp_query = $this->query;

		// Set the links as an array of HTML elements.
		$links = paginate_links(
			[
				'base' => $this->get_config( 'base_url' ) . '%_%',
				'type' => 'array',
			]
		);

		// Set the global wp_query to what it originally was.
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride
		$wp_query = $current_global_wp_query;

		return $links;
	}
}