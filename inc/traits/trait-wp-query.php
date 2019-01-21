<?php
/**
 * WP_Query trait.
 *
 * @package WP_Component
 */

namespace WP_Component;

/**
 * WP_Query trait.
 */
trait WP_Query {

	/**
	 * WP_Query object.
	 *
	 * @var null|\WP_Query
	 */
	public $query = null;

	/**
	 * Get the query posts.
	 *
	 * @return int
	 */
	public function get_posts() {
		return $this->query->posts ?? [];
	}

	/**
	 * Get the queried object.
	 *
	 * @return mixed
	 */
	public function get_queried_object() {
		return $this->query->get_queried_object();
	}

	/**
	 * Get the queried object ID.
	 *
	 * @return int
	 */
	public function get_queried_object_id() {
		return absint( $this->query->get_queried_object_id() ?? 0 );
	}

	/**
	 * Set the query object.
	 *
	 * @param mixed $wp_query \WP_Query object, or null to use global $wp_query
	 *                        object.
	 */
	public function set_query( $wp_query = null ) {

		// WP_Query object was passed.
		if ( $wp_query instanceof \WP_Query ) {
			$this->query = $wp_query;
			$this->query_has_set();
			return $this;
		}

		// Use global $wp_query.
		if ( is_null( $wp_query ) ) {
			global $wp_query;
			$this->query = $wp_query;
			$this->query_has_set();
			return $this;
		}

		// Something else went wrong.
		// @todo determine how to handle error messages.
		return $this;
	}

	/**
	 * Callback function for classes to override.
	 */
	public function query_has_set() {
		// Silence is golden.
	}
}