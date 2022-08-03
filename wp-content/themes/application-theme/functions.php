<?php

include(get_stylesheet_directory() . '/lib/helpers.php');

//use Timber\Timber;

// if (!class_exists('Timber')) {

//     add_action(
//         'admin_notices',
//         function () {
//             echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
//         }
//     );

//     add_filter(
//         'template_include',
//         function ($template) {
//             return get_stylesheet_directory() . '/static/no-timber.html';
//         }
//     );
//     return;
// }

Timber\Timber::$dirname = array('templates', 'views');

Timber\Timber::init();

class ApplicationSetup extends Timber\Site
{

    public function __construct()
    {
        add_theme_support('post-formats');
        add_theme_support('post-thumbnails');
        add_theme_support('menus');

        add_filter('timber_context', [$this, 'add_to_context']);
        add_filter('get_twig', [$this, 'add_to_twig']);
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('init', [$this, 'register_menus']);
        add_action('init', [$this, 'register_image_sizes']);

        add_filter('body_class', [$this, 'my_body_classes']);

        add_filter('timber/twig/environment/options', function ($options) {
            $options['autoescape'] = false;

            return $options;
        });

        add_action('rest_api_init', 'register_custom_rest_fields');
        function register_custom_rest_fields()
        {
            register_rest_field(
                'products',
                'featured_media_src',
                array(
                    'get_callback'    => function ($object) {
                        if ($object['featured_media']) {
                            $img = wp_get_attachment_image_src($object['featured_media'], 'large');
                            return $img[0];
                        }

                        return false;
                    },
                    'update_callback' => null,
                    'schema'          => null,
                )
            );
            register_rest_field(
                'products',
                'tiny_media_src',
                array(
                    'get_callback'    => function ($object) {
                        if ($object['featured_media']) {
                            $img = wp_get_attachment_image_src($object['featured_media'], 'tiny');
                            return $img[0];
                        }

                        return false;
                    },
                    'update_callback' => null,
                    'schema'          => null,
                )
            );

            register_rest_field(
                'products',
                'vote_count',
                array(
                    'get_callback'    => function ($object) {
                        $votes = get_field('votes', $object['id']);

                        if (!$votes) {
                            return 0;
                        }

                        return count($votes);
                    },
                    'update_callback' => null,
                    'schema'          => null,
                )
            );

            register_rest_field(
                'products',
                'hero',
                array(
                    'get_callback'    => function ($object) {
                        return get_field('hero', $object['id']);
                    },
                    'update_callback' => null,
                    'schema'          => null,
                )
            );
        }

        add_action('rest_api_init', 'register_custom_api_endpoints');
        function register_custom_api_endpoints() {
            register_rest_route('utilities/v1', '/vote/(?P<id>\d+)', array(
                'methods' => 'POST',
                'permission_callback' => '__return_true',
                'callback' => function( WP_REST_Request $request ) {
                    $productId = (int)$request->get_param('id');
                    $body      = json_decode( $request->get_body() );

                    // WordPress post exists based on post ID
                    if (get_post($productId)) {
                        $votes = get_field('votes', $productId);

                        if (!$votes) {
                            $votes = 0;
                        }

                        // Refactor this code
                        if ($body->direction == 'up') {
                            $votes++;
                        } else {
                            $votes--;
                        }

                        update_field('votes', $votes, $productId);

                        return new WP_REST_Response([
                            'success' => true,
                            'votes' => $votes
                        ]);
                    } else {
                        return new WP_Error('vote_error', 'Invalid vote attempt', array('status' => 500));
                    }
                },
            ));
        }

        parent::__construct();
    }

    public function register_post_types()
    {
        require('lib/custom-post-types.php');
    }

    public function register_taxonomies()
    {
        require('lib/custom-taxonomies.php');
    }

    public function register_menus()
    {
        require('lib/custom-menus.php');
    }

    public function register_image_sizes()
    {
        require('lib/custom-image-sizes.php');
    }

    public function add_to_context($context)
    {
        $context['menu'] = Timber\Timber::get_menu('primary');
        $context['site'] = $this;

        if (function_exists('get_fields')) {
            $context['options'] = get_fields('options');
        }

        return $context;
    }

    public function add_to_twig($twig)
    {
        $twig->addExtension(new Twig\Extension\StringLoaderExtension());
        //$twig->addFunction( new Twig\TwigFunction( 'is_page', 'is_page' ) );
        //$twig->addFilter( new Twig\TwigFilter( 'permalink', [$this, 'map_permalinks'] ) );
        return $twig;
    }

    public function my_body_classes($classes)
    {
        if (wp_is_mobile()) {
            $classes[] = 'is-mobile';
        }

        return $classes;
    }

    // public function add_frontend_app_rewrites() {
    //     add_rewrite_rule('^app/([^/]*)/?$', 'index.php?pagename=app/$1', 'top');
    // }
}

new ApplicationSetup();

function custom_application_scripts()
{
    $app_style_path =  get_template_directory_uri() . '/assets/css/main.css';
    $app_js_path    = get_template_directory_uri() . '/assets/js/main.js';

    wp_enqueue_style('app-styles', $app_style_path, [], filemtime($app_style_path));
    wp_enqueue_script('app-js', $app_js_path, array('jquery'), filemtime($app_js_path), true);

    $app_frontend_path = get_site_url() . '/frontend/dist/entry.bundle.js';

    $data['nonce'] = wp_create_nonce('wp_rest');

    wp_enqueue_script('app_frontend', $app_frontend_path, array(), filemtime(ABSPATH . "frontend/dist/entry.bundle.js"), true);
    wp_localize_script('app_frontend', 'context', $data);
}

add_action('wp_enqueue_scripts', 'custom_application_scripts');
