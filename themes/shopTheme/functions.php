<?php

function startup_features()
{
    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'startup_features');





function addWoocommerceFeatures()
{
    // Register theme features.
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('woocommerce');
    add_theme_support('widgets');
}

add_action('after_setup_theme', 'addWoocommerceFeatures');

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);


add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 10);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 9);
add_action('woocommerce_single_variation', 'woocommerce_template_single_excerpt', 5);


//Hide Price Range for WooCommerce Variable Products
add_filter(
    'woocommerce_variable_sale_price_html',
    'lw_variable_product_price',
    10,
    2
);
add_filter(
    'woocommerce_variable_price_html',
    'lw_variable_product_price',
    10,
    2
);

function lw_variable_product_price($v_price, $v_product)
{

    // Product Price
    $prod_prices = array(
        $v_product->get_variation_price('min', true),
        $v_product->get_variation_price('max', true)
    );
    $prod_price = $prod_prices[0] !== $prod_prices[1] ? sprintf(
        __('From: %1$s', 'woocommerce'),
        wc_price($prod_prices[0])
    ) : wc_price($prod_prices[0]);

    // Regular Price
    $regular_prices = array(
        $v_product->get_variation_regular_price('min', true),
        $v_product->get_variation_regular_price('max', true)
    );
    sort($regular_prices);
    $regular_price = $regular_prices[0] !== $regular_prices[1] ? sprintf(
        __('From: %1$s', 'woocommerce'),
        wc_price($regular_prices[0])
    ) : wc_price($regular_prices[0]);

    if ($prod_price !== $regular_price) {
        $prod_price = '<del>' . $regular_price . $v_product->get_price_suffix() . '</del> <ins>' .
            $prod_price . $v_product->get_price_suffix() . '</ins>';
    }
    return $prod_price;
}



// add_filter('woocommerce_dropdown_variation_attribute_options_args', 'change_vars', 10);

// function change_vars()
// {
//     $args['show_option_none'] = apply_filters('the_title', get_taxonomy($args['attribute'])->labels->singular_name);
//     return $args;
// }
