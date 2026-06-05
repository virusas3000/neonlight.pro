<?php
/**
 * Must-Use Plugin: Force front page for /?lang= queries and contact redirects
 */
add_filter('query_vars', function($vars) {
    $vars[] = 'lang';
    $vars[] = 'contact_submitted';
    $vars[] = 'contact_error';
    return $vars;
});

// Intercept early — before WordPress marks it as 404
add_action('parse_request', function($wp) {
    if (!empty($_GET['lang']) && in_array($_GET['lang'], ['en', 'zh', 'cn'])) {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        // Root path with ?lang= (with or without extra params)
        if ($uri === '/' || preg_match('#^/\?#', $uri)) {
            $wp->query_vars = ['page_id' => get_option('page_on_front')];
            $wp->is_404         = false;
            $wp->is_front_page   = true;
            $wp->is_page         = true;
            $wp->is_home         = false;
        }
    }
}, 1);
