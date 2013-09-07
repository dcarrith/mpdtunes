<?php namespace Dcarrith\CIFirePHP;

use Illuminate\Support\Facades\Facade;

class CIFirePHPFacade extends Facade {

        /**
         * Get the registered name of the component.
         *
         * @return string
         */
        protected static function getFacadeAccessor() { return 'cifirephp'; }
}
