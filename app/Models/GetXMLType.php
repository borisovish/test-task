<?php

namespace App\Models;

class GetXMLType extends GetDataFromApiStrategy
{
 /**
 * [GenerateData description]
 * @param [type] $file [patch &file format xml ]
 */
    public function GenerateData($file)
    {
        $xml = simplexml_load_file($file);
        $orders = [];
        foreach ($xml as $row) {
            $orders[] = [
                'advcampaign_id' => (string)$row->advcampaign_id,
                'order_id'       => (string)$row->order_id,
                'order_payment'  => (string)$row->payment,
                'cart'           => (string)$row->cart,
                'currency'       => (string)$row->currency,
                'status'         => (string)$row->action,
                'action_date'    => (string)$row->action_date,
                'description'    => (string)$row->comment,
            ];
        }
        $this->_orders = $orders;
        return $this->_orders;
    }

}

