<?php
namespace App\Models;

use App\Models\GetCSVType;
use App\Models\GetXMLType;

class GetDataFromApi extends GetDataFromApiStrategy
{

    public function __construct($file)
    {
        $this->_file = $file;
        $this->GetFileType();
        if (preg_match('/xml/', $this->_extension)) {
            $this->_data = new GetXMLType($this->_file);
        } elseif (preg_match('/csv/', $this->_extension)) {
            $this->_data = new GetCSVType($this->_file);
        } else {
            throw new \Exception("Type's file is not found");
        }

    }

    protected function GetFileType()
    {
        $info             = new \SplFileInfo($this->_file);
        $this->_extension = $info->getExtension();
    }

    public function GetData()
    {
        return $this->_data->GenerateData($this->_file);
    }

}
