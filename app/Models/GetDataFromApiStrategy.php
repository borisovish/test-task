<?php
namespace App\Models;
/**
 * The base class receives data files in XML OR CSV.
   Return array of orders
 */
abstract class GetDataFromApiStrategy
{

    protected $_extension;
    protected $_data;
    protected $_file;
    public $_orders;

}
