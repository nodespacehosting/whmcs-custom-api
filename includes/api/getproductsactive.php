<?php
/****************************************************************
 * Copyright (c) 2025 NodeSpace Technologies, LLC
 * Original: https://github.com/drosendo/whmcs-custom-apis/
 * 
 * This file adds a custom API endpoint to WHMCS that will return
 * a list of non-hidden products. It is used exactly the same way
 * as getProducts.
 *
 * License: MIT License
 ***************************************************************/

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly!");
}

function get_env($vars)
{
    $array = array('action' => array(), 'gid' => null, 'pid' => null);

    if (isset($vars['cmd'])) {
        // Local API mode
        $array['action'] = $vars['cmd'];
        $array['params'] = (object) $vars['apivalues1'];
        $array['adminuser'] = $vars['adminuser'];
        // Extract gid and pid from params if set
        $array['gid'] = $vars['apivalues1']['gid'] ?? null;
        $array['pid'] = $vars['apivalues1']['pid'] ?? null;
    } else {
        // Post CURL mode
        $array['action'] = $vars['action'] ?? null;
        unset($vars['_POST']['username']);
        unset($vars['_POST']['password']);
        unset($vars['_POST']['action']);
        $array['gid'] = $vars['gid'] ?? null;
        $array['pid'] = $vars['pid'] ?? null;
    }
    return (object) $array;
}

try {

    $post_fields = get_env(get_defined_vars());

    $command = 'GetProducts';

    $postData = array_filter([
        'gid' => $post_fields->gid,
        'pid' => $post_fields->pid
    ], function ($value) {
        return $value !== null && $value !== '';
    });

    $results = localAPI($command, $postData);

    // Query hidden products
    $query = Capsule::table('tblproducts')->select('id')->where('hidden', 1);

    if ($post_fields->gid) {
        $query->where('gid', $post_fields->gid);
    }

    if ($post_fields->pid) {
        $query->where('id', $post_fields->pid);
    }

    $hiddenProducts = $query->pluck('id')->toArray();

    // Filter out hidden products from the results
    if (!empty($hiddenProducts) && !empty($results['products']['product'])) {
        foreach ($results['products']['product'] as $key => $product) {
            if (in_array($product['pid'], $hiddenProducts, true)) {
                unset($results['products']['product'][$key]);
                $results['totalresults']--;
            }
        }
        // Reindex array keys to keep them sequential
        $results['products']['product'] = array_values($results['products']['product']);
    }

    // If after filtering, no products left, you might want to handle that
    if (empty($results['products']['product'])) {
        $results['totalresults'] = 0;
        $results['products']['product'] = [];
    }

    $apiresults = $results;
} catch (Exception $e) {
    $apiresults = array("result" => "error", "message" => $e->getMessage());
}