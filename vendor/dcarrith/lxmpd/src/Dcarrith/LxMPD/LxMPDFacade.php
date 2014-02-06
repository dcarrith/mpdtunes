<?php namespace Dcarrith\LxMPD;

use Illuminate\Support\Facades\Facade;

class LxMPDFacade extends Facade {

        /**
         * Get the registered name of the component.
         *
         * @return string
         */
        protected static function getFacadeAccessor() { return 'lxmpd'; }
}
