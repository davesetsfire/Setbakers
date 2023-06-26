<?php

namespace App\Vendor\LaravelSevdeskApi;

class SevdeskApi extends \Exlo89\LaravelSevdeskApi\SevdeskApi {

    protected function getApiInstance($method) {
        $class = "\\App\\Vendor\\LaravelSevdeskApi\\Api\\" . ucwords($method);

        if (class_exists($class)) {
            return new $class();
        }

        $class = "\\Exlo89\\LaravelSevdeskApi\\Api\\" . ucwords($method);

        if (class_exists($class)) {
            return new $class();
        }

        throw new \BadMethodCallException("Undefined method [{$method}] called.");
    }

}
