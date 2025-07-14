<?php
// Load WordPress core
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

// Only allow admin to run
if (!current_user_can('manage_options')) {
    wp_die('Access denied.');
}

// Path to CSV file
$csv_path = ABSPATH . 'custom-tabs.csv';

if (!file_exists($csv_path)) {
    wp_die('CSV file not found at: ' . $csv_path);
}

$csv = fopen($csv_path, 'r');
$headers = fgetcsv($csv); // COMMA-separated

if (!$headers || count($headers) < 3) {
    echo '<pre>'; print_r($headers); echo '</pre>';
    wp_die('Invalid header row in CSV.');
}

$updated = 0;

while (($row = fgetcsv($csv)) !== false) {
    $data = array_combine($headers, $row);

    if (!$data || !isset($data['Title'])) continue;

    $title = trim($data['Title']);
    $product = get_page_by_title($title, OBJECT, 'product');

    if ($product) {
        update_post_meta($product->ID, '_custom_tab_title', sanitize_text_field($data['custom_tab_title1']));
        update_post_meta($product->ID, '_custom_tab_content', wp_kses_post($data['custom_tab_content1']));
        $updated++;
    }
}

fclose($csv);
echo "$updated products updated with custom tabs.";