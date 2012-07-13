<?php
/**
 * Displays the contents of the README file.
 *
 * Returns contents of the README.txt Child theme file, if it exists.
 *
 *
 * 
 * colabsthemes_readme_menu_admin()
 */
function colabsthemes_readme_menu_admin() {

    // Get Theme Name   
    $themename =  get_option( 'colabs_themename' );
    
	// Assume we cannot find the file.
	$file = false;

	// Get the file contents
	$file = @file_get_contents( get_template_directory() . '/README.txt');

	if ( !$file || empty($file) ) {
		$file = '<b>README.txt file not found.</b>';
	}

?>
	<div id="genesis-readme-file" class="wrap">
		<?php screen_icon('edit-pages'); ?>
		<h2><?php _e( $themename.' - README.txt Theme File', 'colabsthemes'); ?></h2>
		<?php echo wpautop( $file ); ?>
	</div>
<?php
}