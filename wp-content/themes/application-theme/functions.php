<?php

//use Timber\Timber;

if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

Timber\Timber::$dirname = array( 'templates', 'views' );

Timber\Timber::init();

class Application extends Timber\Site {

    public function __construct() {
        add_theme_support('post-formats');
        add_theme_support('post-thumbnails');
        add_theme_support('menus');

        add_filter( 'timber_context', [$this, 'add_to_context'] );
        add_filter( 'get_twig', [$this, 'add_to_twig'] );
		add_action( 'init', [ $this, 'register_post_types' ] );
		add_action( 'init', [ $this, 'register_taxonomies' ] );
		add_action( 'init', [ $this, 'register_menus' ] );

        add_filter( 'timber/twig/environment/options', function( $options ) {
            $options['autoescape'] = false;
        
            return $options;
        } );

		parent::__construct();
    }

    public function register_post_types() {
        require('lib/custom-post-types.php');
    }

    public function register_taxonomies() {
        require('lib/custom-taxonomies.php');
    }

    public function register_menus() {
        require('lib/custom-menus.php');
    }

    public function add_to_context( $context ) {
        $context['menu'] = Timber\Timber::get_menu( 'primary' );
        $context['site'] = $this;

        if ( function_exists('get_fields') ) {
            $context['options'] = get_fields( 'options' );
        }

        return $context;
    }

    public function add_to_twig( $twig ) {
		$twig->addExtension(new Twig\Extension\StringLoaderExtension());
		//$twig->addFunction( new Twig\TwigFunction( 'is_page', 'is_page' ) );
        //$twig->addFilter( new Twig\TwigFilter( 'permalink', [$this, 'map_permalinks'] ) );
		return $twig;
	}

}

new Application();

function custom_application_scripts() {
	wp_enqueue_style( 'app-styles', get_template_directory_uri() . '/assets/css/main.css', 1.0);
	wp_enqueue_script( 'app-js', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'custom_application_scripts' );