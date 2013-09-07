<?php namespace Dcarrith\CIZip;

use Illuminate\Support\Facades\Facade;

class CIZipFacade extends Facade {

        /**
         * Get the registered name of the component.
         *
         * @return string
         */
        protected static function getFacadeAccessor() { return 'cizip'; }
}
