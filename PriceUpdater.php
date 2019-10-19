<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 19.10.19
 * Time: 19:07
 */

class PriceUpdater
{
    public function __construct()
    {
        add_action('woocommerce_after_product_object_save', array($this, 'updateProductPrices'), 999, 2);
    }

    public function updateProductPrices($product, $dataStore)
    {
        global $wpdb;
        //TODO: refactoring
        //TODO: extract opt api
        $prices = ['price' => []];
        if ($product instanceof WC_Product_Variable) {
            $prices = $product->get_variation_prices(false);
        } else {
            $prices['price'][] = $product->get_price(false);
        }
        $productId = $product->get_id();

        $tableName = tbwpf_tableName();
        $optPrices = array_unique(array_map('floatval',$prices['price']));
        $optPricesString = implode(',', $optPrices);

        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$tableName} WHERE post_id=%d AND property='_price' AND `value` NOT IN ({$optPricesString})",
            $productId
        )
        );

        $values = $wpdb->get_results($wpdb->prepare("SELECT `value` FROM {$tableName} WHERE post_id=%d AND property='_price'",$productId));
        $currentPrices = array_map(function ($price){
            return (float)$price->value;
        },$values);

        $toAdd = array_diff($optPrices, $currentPrices);
        if (empty($toAdd)) {
            return ;
        }
        $sql = "INSERT INTO {$tableName} (`post_id`,`property`,`value`) VALUES ";
        foreach($toAdd as $price){
            $sql .= " ({$productId}, '_price', {$price}),";
        }
        $sql = trim($sql, ',');
        $wpdb->query($sql);
    }
}