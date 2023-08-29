<?php

namespace App\CustomFilter;


trait CustomFilterTrait
{

    /**
     * Static function customFilter
     * 
     * @return
     */
    public static function customFilter($query, $column_key, $column_value) {
    
    /**
     * $query - (static::query) Data from eloquent query
     * $column_key - app request param field key
     * $column_value - app request param value
     * Add Eloquent Relationship filter
     * @return
     */
        // example
        if($column_key == 'name' && $column_value == 'devchithu') {
            $query->whereHas('post', function($q){
                return $q;
            });
        }

        return  $query;
    }

}