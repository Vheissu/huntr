<?php

use Timber\Timber;

$context = Timber::context();
$posts = Timber::get_posts();

$context['title'] = 'Search results for '. get_search_query();
$context['posts'] = $posts;

$templates = array( 'search.twig', 'archive.twig', 'index.twig' );

Timber::render( $templates, $context );