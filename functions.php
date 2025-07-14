<?php
// Add custom product tab from imported meta
add_filter('woocommerce_product_tabs', 'add_custom_notes_tab');
function add_custom_notes_tab($tabs) {
    global $post;

    $tab_title   = get_post_meta($post->ID, '_custom_tab_title', true);
    $tab_content = get_post_meta($post->ID, '_custom_tab_content', true);

    if (!empty($tab_title) && !empty($tab_content)) {
        $tabs['custom_notes_tab'] = array(
            'title'    => esc_html($tab_title),
            'priority' => 50,
            'callback' => 'render_custom_notes_tab',
        );
    }

    return $tabs;
}

// Render the content of the custom tab
function render_custom_notes_tab() {
    global $post;
    $content = get_post_meta($post->ID, '_custom_tab_content', true);
    echo wp_kses_post($content);
}