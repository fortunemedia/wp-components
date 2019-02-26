<?php
/**
 * Social Sharing component.
 *
 * @package WP_Components
 */

namespace WP_Components;

/**
 * Social Sharing.
 */
class Social_Sharing extends Component {

	use WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'social-sharing';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() {
		return [];
	}

	/**
	 * Hook into post being set.
	 */
	public function post_has_set() {
		foreach ( $this->config as $property => $enabled ) {
			if ( (bool) $enabled && method_exists( $this, "get_{$property}_component" ) ) {
				$this->append_child( call_user_func( [ $this, "get_{$property}_component" ] ) );
			}
		}
		return $this;
	}

	/**
	 * Get a Facebook Social_Sharing_Item component.
	 *
	 * @return \WP_Components\Social_Sharing_Item
	 */
	public function get_facebook_component() {
		return ( new Social_Sharing_Item() )
			->set_config( 'type', 'facebook' )
			->set_config(
				'url',
				add_query_arg(
					[
						'u' => $this->get_url(),
					],
					'https://www.facebook.com/sharer.php/'
				)
			);
	}

	/**
	 * Get a Twitter Social_Sharing_Item component.
	 *
	 * @return \WP_Components\Social_Sharing_Item
	 */
	public function get_twitter_component() {
		return ( new Social_Sharing_Item() )
			->set_config( 'type', 'twitter' )
			->set_config(
				'url',
				add_query_arg(
					[
						'text' => $this->get_title(),
						'url'  => $this->get_url(),
					],
					'https://twitter.com/share/'
				)
			);
	}

	/**
	 * Get a Whatsapp Social_Sharing_Item component.
	 *
	 * @return \WP_Components\Social_Sharing_Item
	 */
	public function get_whatsapp_component() {
		return ( new Social_Sharing_Item() )
			->set_config( 'type', 'whatsapp' )
			->set_config(
				'url',
				add_query_arg(
					[
						'text' => rawurlencode(
							sprintf(
								// Translators: %1$s - article title, %2$s - article url.
								esc_html__( 'Check out this story: %1$s %2$s', 'wp-components' ),
								$this->get_title(),
								$this->get_url()
							)
						),
					],
					'https://api.whatsapp.com/send/'
				)
			);
	}

	/**
	 * Get a LinkedIn Social_Sharing_Item component.
	 *
	 * @return \WP_Components\Social_Sharing_Item
	 */
	public function get_linkedin_component() {
		return ( new Social_Sharing_Item() )
			->set_config( 'type', 'twitter' )
			->set_config(
				'url',
				add_query_arg(
					[
						'url'     => $this->get_url(),
						'title'   => $this->get_title(),
						'summary' => $this->get_excerpt(),
					],
					'https://www.linkedin.com/shareArticle/'
				)
			);
	}

	/**
	 * Get a Pinterest Social_Sharing_Item component.
	 *
	 * @return \WP_Components\Social_Sharing_Item
	 */
	public function get_pinterest_component() {
		return ( new Social_Sharing_Item() )
			->set_config( 'type', 'pinterest' )
			->set_config(
				'url',
				add_query_arg(
					[
						'url'         => $this->get_url(),
						'media'       => $this->get_featured_image_url(),
						'description' => $this->get_excerpt(),
					],
					'https://pinterest.com/pin/create/button/'
				)
			);
	}

	/**
	 * Get an Email Social_Sharing_Item component.
	 *
	 * @return \WP_Components\Social_Sharing_Item
	 */
	public function get_email_component() {
		return ( new Social_Sharing_Item() )
			->set_config( 'type', 'email' )
			->set_config(
				'url',
				add_query_arg(
					[
						'url'         => $this->get_url(),
						'media'       => $this->get_featured_image_url(),
						'description' => $this->get_excerpt(),
					],
					'mailto:'
				)
			);
	}

	/**
	 * Helper for getting a url encoded url.
	 *
	 * @return string
	 */
	public function get_url() {
		return rawurlencode( $this->wp_post_get_permalink() );
	}

	/**
	 * Helper for getting a url encoded title.
	 *
	 * @return string
	 */
	public function get_title() {
		return rawurlencode( $this->wp_post_get_title() );
	}

	/**
	 * Helper for getting a url encoded excerpt.
	 *
	 * @return string
	 */
	public function get_excerpt() {
		return rawurlencode( $this->wp_post_get_excerpt() );
	}

	/**
	 * Helper for getting a url encoded excerpt.
	 *
	 * @return string
	 */
	public function get_featured_image_url() {
		return rawurlencode( get_the_post_thumbnail_url( $this->post, 'full' ) );
	}
}