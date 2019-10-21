<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 19.10.19
 * Time: 19:07
 */

class tbwpf_PriceUpdater
{
    /**
     * @var tbwpf_tbwpf_OptFloatProperties
     */
    private $properties;

    public function __construct()
    {
        global $wpdb;
        $this->properties = new tbwpf_OptFloatProperties($wpdb);
        add_action('woocommerce_after_product_object_save', array($this, 'updateProductPrices'), 999, 2);
    }

    /**
     * @param WC_Product $product
     * @param $dataStore
     */
    public function updateProductPrices($product, $dataStore)
    {
        $prices = $this->getPrices($product);

        $productId = $product->get_id();

        $this->properties->removeUselessValues($productId, '_price', $prices);

        $currentPrices = $this->properties->getValues($productId, '_price');

        $toAdd = array_diff($prices, $currentPrices);
        if (empty($toAdd)) {
            return;
        }

        $this->properties->insert($productId, '_price', $toAdd);
    }

    private function getPrices(WC_Product $product)
    {
        $prices = ['price' => []];
        if ($product instanceof WC_Product_Variable) {
            $prices = $product->get_variation_prices(false);
        } else {
            $prices['price'][] = $product->get_price(false);
        }
        return array_unique(array_map('floatval', $prices['price']));
    }
}