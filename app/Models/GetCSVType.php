<?php

namespace App\Models;

class GetCSVType extends GetDataFromApiStrategy
{

    public function GenerateData($file)
    {
        $arfile = \Excel::load($file, function ($reader) {
            return $reader->take(10);
        })->toArray();

        $orders = [];
        foreach ($arfile as $row) {
            $orders[] = [
                'advcampaign_id' => $row['campaign_id'],
                'order_id'       => $row['custom_id'],
                'order_payment'  => $row['ebay_item_id'],
                'cart'           => $row['ebay_leaf_category_id'],
                'currency'       => $row['meta_category_id'],
                'status'         => $row['event_type'],
                'action_date'    => $row['click_timestamp'],
                'description'    => $row['item_name'],
            ];
        }
        $this->_orders = $orders;
        return $this->_orders;
    }

    /*

array:24 [▼
"event_date" => "2016-02-06"
"posting_date" => "2016-03-08"
"event_type" => "Winning Bid (Revenue)"
"amount" => -0.09
"program_id" => 1.0
"program_name" => "eBay US"
"campaign_id" => 5337776397.0
"campaign_name" => "Default campaign"
"tool_id" => 10001.0
"tool_name" => "Link Generator"
"custom_id" => "14459738:293502:1453633787_176.51.26.86_653"
"click_timestamp" => "2016-02-06 08:49:32"
"ebay_item_id" => 201492651634.0
"ebay_leaf_category_id" => 45230.0
"ebay_quantity_sold" => null
"ebay_total_sale_amount" => null
"item_site_id" => 0.0
"meta_category_id" => 11450.0
"unique_transaction_id" => "2,01608720000331E+017"
"user_frequency_id" => 3.0
"earnings" => -0.01
"traffic_type" => "Classic"
"item_name" => "New  Winter Fashion Women Devil Hat Cute Cat Ears Wool Bowler Cap "
0 => null
]
 */

}
