<?php
/*
Plugin Name: Custom Registration Fields (crf)
Plugin URI:
Description: simple testing plugin for add additional users profile custom fields
Version: 0.1
Author: inweb
Author URI:
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


// TODO form for back end registration


/**
 * Front end registration
 */

// 1. Add a new form elements
add_action( 'register_form', 'crf_register_form' );

function crf_register_form() {

	$first_name 		= ( ! empty( $_POST['first_name'] ) ) ? sanitize_text_field( $_POST['first_name'] ) : '';
	$last_name 			= ( ! empty( $_POST['last_name'] ) ) ? sanitize_text_field( $_POST['last_name'] ) : '';
	$user_specialty = ( ! empty( $_POST['user_specialty'] ) ) ? sanitize_text_field( $_POST['user_specialty'] ) : '';
	$user_job_place = ( ! empty( $_POST['user_job_place'] ) ) ? sanitize_text_field( $_POST['user_job_place'] ) : '';
	$user_phone_num = ( ! empty( $_POST['user_phone_num'] ) ) ? sanitize_text_field( $_POST['user_phone_num'] ) : '';

	?>
	<p>
		<label for="first_name"><?php _e( 'Nume', 'mydomain' ) ?><br />
			<input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr(  $first_name  ); ?>" size="25" />
		</label>
	</p>
	<p>
		<label for="last_name"><?php _e( 'Prenume', 'mydomain' ) ?><br />
			<input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( $last_name ); ?>" size="25" />
		</label>
	</p>
	<p>
		<label for="user_specialty"><?php _e( 'Specialitatea', 'mydomain' ) ?><br />
			<input type="text" name="user_specialty" id="user_specialty" class="input" value="<?php echo esc_attr( $user_specialty ); ?>" size="25" />
		</label>
	</p>
	<p>
		<label for="user_job_place"><?php _e( 'Locul de Muncă', 'mydomain' ) ?><br />
			<input type="text" name="user_job_place" id="user_job_place" class="input" value="<?php echo esc_attr( $user_job_place ); ?>" size="40" />
		</label>
	</p>
	<p>
		<label for="user_phone_num"><?php _e( 'Telefon (mobil)', 'mydomain' ) ?><br />
			<input type="text" name="user_phone_num" id="user_phone_num" class="input" value="<?php echo esc_attr( $user_phone_num ); ?>" size="10" />
		</label>
	</p>
	<?php
}


// 2. Add validation.
add_filter( 'registration_errors', 'crf_registration_errors', 10, 3 );

function crf_registration_errors( $errors ) {

	if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
		$errors->add( 'first_name_error', sprintf('<strong>%s</strong>: %s',__( 'ERROR', 'mydomain' ),__( 'You must include a first name.', 'mydomain' ) ) );

	}

	if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {
		$errors->add( 'last_name_error', sprintf('<strong>%s</strong>: %s',__( 'ERROR', 'mydomain' ),__( 'You must include a last name.', 'mydomain' ) ) );

	}

	if ( empty( $_POST['user_specialty'] ) || ! empty( $_POST['user_specialty'] ) && trim( $_POST['user_specialty'] ) == '' ) {
		$errors->add( 'specialty_name_error', sprintf('<strong>%s</strong>: %s',__( 'ERROR', 'mydomain' ),__( 'You must include a specialty.', 'mydomain' ) ) );

	}

	if ( empty( $_POST['user_job_place'] ) || ! empty( $_POST['user_job_place'] ) && trim( $_POST['user_job_place'] ) == '' ) {
		$errors->add( 'job_place_name_error', sprintf('<strong>%s</strong>: %s',__( 'ERROR', 'mydomain' ),__( 'You must include a job place.', 'mydomain' ) ) );

	}

	if ( empty( $_POST['user_phone_num'] ) || ! empty( $_POST['user_phone_num'] ) && trim( $_POST['user_phone_num'] ) == '' ) {
		$errors->add( 'phone_num_error', sprintf('<strong>%s</strong>: %s',__( 'ERROR', 'mydomain' ),__( 'You must include a phone number.', 'mydomain' ) ) );

	}

	return $errors;
}


// 3. Save our extra registration user meta.
add_action( 'user_register', 'crf_user_register' );

function crf_user_register( $user_id ) {

	if ( ! empty( $_POST['first_name'] ) ) {
		update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
	}

	if ( ! empty( $_POST['last_name'] ) ) {
		update_user_meta( $user_id, 'last_name', sanitize_text_field( $_POST['last_name'] ) );
	}

	if ( ! empty( $_POST['user_specialty'] ) ) {
		update_user_meta( $user_id, 'user_specialty', sanitize_text_field( $_POST['user_specialty'] ) );
	}

	if ( ! empty( $_POST['user_job_place'] ) ) {
		update_user_meta( $user_id, 'user_job_place', sanitize_text_field( $_POST['user_job_place'] ) );
	}

	if ( ! empty( $_POST['user_phone_num'] ) ) {
		update_user_meta( $user_id, 'user_phone_num', sanitize_text_field( $_POST['user_phone_num'] ) );
	}
}



/**
 * Back end profile view
 */

// Show custom user profile fields
add_action( 'show_user_profile', 'crf_show_extra_profile_fields', 10, 1 );
add_action( 'edit_user_profile', 'crf_show_extra_profile_fields', 10, 1 );

function crf_show_extra_profile_fields( $user_id ) {
	?>
	<h3><?php esc_html_e( 'Additional information', 'mydomain' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="user_specialty"><?php esc_html_e( 'Specialty', 'mydomain' ); ?></label></th>
			<td><input type="text" name="user_specialty" id="user_specialty" class="input" value="<?php echo esc_attr( get_the_author_meta(  'user_specialty', $user_id->ID )); ?>" ></td>
		</tr>
		<tr>
			<th><label for="user_job_place"><?php esc_html_e( 'Work place', 'mydomain' ); ?></label></th>
			<td><input type="text" name="user_job_place" id="user_job_place" class="input" value="<?php echo esc_attr( get_the_author_meta( 'user_job_place', $user_id->ID)); ?>" ></td>
		</tr>
		<tr>
			<th><label for="user_phone_num"><?php esc_html_e( 'Phone', 'mydomain' ); ?></label></th>
			<td><input type="text" name="user_phone_num" id="user_phone_num" class="input" value="<?php echo esc_attr( get_the_author_meta( 'user_phone_num', $user_id->ID)); ?>" ></td>
		</tr>
	</table>
	<?php
}



// Custom fields update option
add_action( 'personal_options_update', 'crf_usermeta_form_field_update' );
add_action( 'edit_user_profile_update ', 'crf_usermeta_form_field_update' );

function crf_usermeta_form_field_update($user_id)
{

//	if ( !current_user_can('edit_user', $user_id) )
// 		wp_die(__('Sorry, you are not allowed to edit this user.'));

	update_user_meta(
		$user_id,
		'user_specialty',
		$_POST['user_specialty']
	);

	update_user_meta(
		$user_id,
		'user_job_place',
		$_POST['user_job_place']
	);

	update_user_meta(
		$user_id,
		'user_phone_num',
		$_POST['user_phone_num']
	);

}

