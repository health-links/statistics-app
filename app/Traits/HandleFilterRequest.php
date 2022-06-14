<?php

namespace App\Traits;

trait HandleFilterRequest{
    public function checkFilterParams($key){
        $filter = request()->filter;
        if($filter && array_key_exists($key, $filter) && $filter[$key] !== 'all'){
            return true;
        }
        return false;
    }
}
