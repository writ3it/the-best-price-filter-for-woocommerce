<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 18.10.19
 * Time: 20:23
 */

function tbwpf_activation()
{
    global $wpdb;

    $floatTable = new tbwpf_OptFloatProperties($wpdb);

    if (!$floatTable->isCreated()) {
        $floatTable->create();
    }

    $floatTable->clearAndRegenerate(['_price']);
}

register_activation_hook(TBWPF, 'tbwpf_activation');