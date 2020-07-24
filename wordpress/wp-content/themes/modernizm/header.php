<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<title><?php if ( function_exists( 'colabs_title') ){ colabs_title(); }else{ echo get_bloginfo('name'); ?>&nbsp;<?php wp_title(); } ?></title>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<?php 
	if ( function_exists( 'colabs_meta') ) colabs_meta();
	if ( function_exists( 'colabs_meta_head') )colabs_meta_head(); 
    global $colabs_options;
?>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'template_directory' ); ?>/includes/css/colabs-css.css">
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'template_directory' ); ?>/custom.css">
	<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'template_directory' ); ?>/includes/css/flexslider.css">
	<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'template_directory' ); ?>/includes/css/jquery.jtweetsanywhere.css">

<?php //if ( function_exists( 'colabs_head') ) colabs_head(); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="container">
	<div id="header" class="columns col12">
		<div id="logo" class="columns col6">
			<h1 id="site-title">
				 <?php if ( $colabs_options['colabs_logo_head'] != '' ){ ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<img src="<?php echo $colabs_options['colabs_logo_head']; ?>" alt="<?php bloginfo('name'); ?>" />
				</a>
				<?php } ?>
			</h1>
			<p><?php bloginfo( 'description' ); ?></p>
		</div><!-- #logo -->
		<div id="topmenu" class="columns col6">
			<?php colabs_nav('topmenu');?>
			<div class="select-menu"><?php colabs_dropdown_menu('show_option_none=Navigate to&theme_location=main-menu&fallback_cb=colabs_dropdown_pages');?>	

			</div>
		</div><!-- #topmenu -->
	</div><!-- #header -->
