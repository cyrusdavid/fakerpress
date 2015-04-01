<?php
namespace FakerPress\Module;

class User extends Base {

	public $dependencies = array(
		'\Faker\Provider\Lorem',
		'\Faker\Provider\DateTime',
		'\Faker\Provider\HTML',
	);


	public $provider = '\Faker\Provider\WP_User';


	public function init() {
		add_filter( "fakerpress.module.{$this->slug}.save", array( $this, 'do_save' ) );
	}

	public function do_save() {
		$user_id = wp_insert_user( $this->params );

		// Only set role if needed
		if ( ! is_null( $this->params['role'] ) ){
			$user = new \WP_User( $user_id );

			// Here we could add in the future the possibility to set multiple roles at once
			$user->set_role( $this->params['role'] );
		}

		// Relate this post to FakerPress to make it possible to delete
		add_user_meta( $user_id, $this->flag, 1 );

		return $user_id;
	}
}