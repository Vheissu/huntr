<?php

use Timber\Timber;

global $wp_query;

$data = Timber::context();
$data['posts'] = Timber::get_posts();

if (isset($wp_query->query_vars['author'])) {
    $author = Timber::get_user($wp_query->query_vars['author']);
    $data['author'] = $author;
    $data['title'] = 'Author Archives: ' . $author->name();
}
Timber::render(array('author.twig', 'archive.twig'), $data);
