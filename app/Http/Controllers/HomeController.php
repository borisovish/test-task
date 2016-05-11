<?php

namespace App\Http\Controllers;

use App\Models\ApiData;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home', ['title' => 'Заказы']);
    }

    public function loaddata()
    {
        $idkey             = 'id';
        $where_            = null;
        $default_row_grud  = env("rowNum", 20);
        $default_type_sort = env("sortorder", "ASC");

        $options               = array();
        $options['limit']      = \Request::input('rows', $default_row_grud);
        $options['table_sort'] = \Request::input('sidx', $idkey);
        $options['type_sort']  = \Request::input('sord', $default_type_sort);
        $count                 = ApiData::JqGridFullCount($where_);

        $total_pages         = (is_numeric($count) and $count > 0) ? ceil($count / $options['limit']) : 0;
        $page                = \Request::input('page', 1);
        $options['start']    = max($options['limit'] * $page - $options['limit'], 0);
        $query               = ApiData::JqGridFullDesignation($options, $where_, null);
        $responce['page']    = ($page > $total_pages) ? $total_pages : $page;
        $responce['total']   = $total_pages;
        $responce['records'] = $count;
        $i                   = 0;
        foreach ($query as $row) {
            $responce['rows'][$i]['id']   = $row->{$idkey};
            $responce['rows'][$i]['cell'] = array(
                $row->id,
                $row->advcampaign_id,
                $row->order_id,
                $row->cart,
                $row->currency,
                $row->status,
                $row->action_date,
                $row->description,
            );
            $i++;
        }
        return response()->json($responce);
    }
}
