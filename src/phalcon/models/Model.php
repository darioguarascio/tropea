<?php

class Model extends Phalcon\Mvc\Model implements  Illuminate\Contracts\Support\Arrayable {

    protected static function distanceFilter() {
        return self::distanceValue() . " < :nearby_limit";
    }
    protected static function distanceValue() {
        return "ROUND( (2 * 3961 * asin(sqrt((sin(radians((:lat - coords[0]) / 2))) ^ 2 + cos(radians(coords[0])) * cos(radians(:lat)) * (sin(radians((:lng - coords[1]) / 2))) ^ 2) ) )::numeric ,2)";
    }
 

    protected static function distanceIndexFilter() {
        return "ST_DWithin(geom::geography,ST_SetSRID(ST_MakePoint(:lat,:lng),4326)::geography, :nearby_limit * 1609.34)";
    }
}
