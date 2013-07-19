<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * TwentyTen-ACC WooCommerce
 *
 * $Id$
 *
 * (c) 2013 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package TwentyTen-ACC
 * @subpackage WooCommerce
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 * @see https://gist.github.com/thegdshop/3171026
 *
 */

//  Set number of columns for related products
add_filter ( 'woocommerce_product_thumbnails_columns', 'acc_thumb_cols' );
function acc_thumb_cols()
{
    return 4;
}

//  Set number of columns for products on shop page
add_filter ( 'loop_shop_columns', 'acc_loop_shop_columns' );
function acc_loop_shop_columns()
{
    return 3;
}

/**
 * Need a role for WooCommerce Shop Fulfillment
 *
 */
function acc_add_roles()
{
    //  List of WooCommerce capabilities needed for fulfillment
    //  based on email from WC Support.  Some have been set to false
    //  to prevent certain operations.

    $acc_wc_fulfillment_role = new WP_Role('acc_shop_fulfillment', array(
        'read' => true,
        'edit_shop_order' => true,
        'read_shop_order' => true,
        'delete_shop_order' => false,
        'edit_shop_orders' => true,
        'edit_others_shop_orders' => true,
        'publish_shop_orders' => false,
        'read_private_shop_orders' => true,
        'delete_shop_orders' => false,
        'delete_private_shop_orders' => false,
        'delete_published_shop_orders' => false,
        'delete_others_shop_orders' => false,
        'edit_private_shop_orders' => true,
        'edit_published_shop_orders' => true,
        'manage_shop_order_terms' => false,
        'edit_shop_order_terms' => false,
        'delete_shop_order_terms' => false,
        'assign_shop_order_terms' => false,
        'view_woocommerce_reports' => true,
    ));

    //  If the role already exists, test it to see if any capability
    //  has been changed.  If it has, the role needs to be removed and
    //  then added again with the new capabilities.

    $role = get_role($acc_wc_fulfillment_role->name);

    //  Sort the data by keys so the array can be compared

    if (is_array($role->capabilities))
        ksort($role->capabilities) ;
    ksort($acc_wc_fulfillment_role->capabilities) ;

    //  If what is stored doesn't match what the default is, delete it and add it again

    if ($role->capabilities != $acc_wc_fulfillment_role->capabilities)
    {
        $role = remove_role($acc_wc_fulfillment_role->name);

        $role = add_role($acc_wc_fulfillment_role->name,
            __('Shop Fullfillment', 'woocommerce'), $acc_wc_fulfillment_role->capabilities) ;
    }
}

/**
 * Initiate the action to add roles after the theme is set up
 **/
add_action('after_setup_theme','acc_add_roles');
?>
