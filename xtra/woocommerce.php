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
?>
