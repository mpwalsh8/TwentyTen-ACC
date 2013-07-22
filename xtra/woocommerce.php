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

    if (!is_null($role) && is_array($role->capabilities))
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

/**
 * Add additonal fields to the checkout process
 *
 */

/**
 * Define Volunteer Checkbox fields which are used in
 * several places throughout the custom checkout process.
 *
 * @return mixed - array of meta fields and labels
 */
function acc_checkout_volunteer_checkbox_fields()
{
    return array(
        array(
            'meta' => 'acc_bod_interest',
            'label' => __('Board of Directors', 'woocommerce'),
            'desc' => __('(Officers, Committee Chair, etc.)', 'woocommerce'),
        ),
        array(
            'meta' => 'acc_committee_interest',
            'label' => __('Comittee Member', 'woocommerce'),
            'desc' => __('(Member of Committee, etc.)', 'woocommerce'),
        ),
        array(
            'meta' => 'acc_powder_puff',
            'label' => __('Powder Puff Football', 'woocommerce'),
            'desc' => __('', 'woocommerce'),
        ),
        array(
            'meta' => 'acc_sports_concessions',
            'label' => __('Sports Concessions', 'woocommerce'),
            'desc' => __('(Work in Concession Stand)', 'woocommerce'),
        ),
        array(
            'meta' => 'acc_other',
            'label' => __('Other - Please contact me!', 'woocommerce'),
            'desc' => __('', 'woocommerce'),
        ),
    ) ;
}

function acc_booster_club_custom_checkout_field( $checkout )
{
    echo '<div id="acc-booster-club-help-wanted">' .
        __('<h4>The Booster Club needs your help!</h4><small>Please indicate your areas of interest</small>', 'woocommerce') ;
 
    $volunteer_checkbox_fields = acc_checkout_volunteer_checkbox_fields() ;

    foreach ($volunteer_checkbox_fields as $cb)
    {
        woocommerce_form_field( $cb['meta'], array(
            'type'          => 'checkbox',
            'class'         => array('input-checkbox'),
            'label'         => sprintf('%s <i><small>%s</small></i>', $cb['label'], $cb['desc']),
            'required'  => false,
            ), $checkout->get_value( $cb['meta'] ));
    }
 
    echo '</div>';
}
 
/**  Add checkout field action **/
add_action('woocommerce_after_order_notes', 'acc_booster_club_custom_checkout_field');

/**
 * Process the checkout
 *
 * @return void
 */
function acc_booster_club_custom_checkout_field_process()
{
    global $woocommerce;
 
    // Check if set, if its not set add an error.
    //if ('select' === $_POST['acc_student_name'])
    //     $woocommerce->add_error( __('Please provide Student\'s name.', 'woocommerce') );
}

/**  Add checkout process action **/
//add_action('woocommerce_checkout_process', 'acc_booster_club_custom_checkout_field_process');
 
/**
 * Update the order meta with field value
 *
 * @param int order id
 * @return void
 */
function acc_booster_club_custom_checkout_field_update_order_meta( $order_id )
{
    //  Scan checkboxes for volunteer interest which is all stored in one meta field
    $volunteer_checkbox_fields = acc_checkout_volunteer_checkbox_fields() ;
    $volunteer_interest = array() ;

    foreach ($volunteer_checkbox_fields as $cb)
    {
        if ($_POST[$cb['meta']])
            $volunteer_interest[] = $cb['label'] ;
    }

    if (!empty($volunteer_interest))
        update_post_meta( $order_id, 'Volunteer Interest', esc_attr(implode(', ', $volunteer_interest)));
}

/**  Add checkout update order meta action **/
add_action('woocommerce_checkout_update_order_meta', 'acc_booster_club_custom_checkout_field_update_order_meta');

/**
 * Add the field to order emails
 **/
add_filter('woocommerce_email_order_meta_keys', 'acc_custom_checkout_field_order_meta_keys');
 
/**
 * Meta keys for order email
 *
 * @return mixed - array of meta keys to include in order email
 */
function acc_custom_checkout_field_order_meta_keys( $keys ) {
	$keys[] = 'Volunteer Interest';
	return $keys;
}
?>
