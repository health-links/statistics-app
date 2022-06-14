<?php

namespace App\Traits;

trait HandleFilterRequest{
    public function checkFilterParams($key){
        $filter = request()->filter;
        if($filter && $filter[$key] && $filter[$key] !== 'all'){
            return true;
        }
        return false;
    }
}
