<?php namespace Dcarrith\LxMPD\Exception;

class MPDConnectionException extends \Exception {

    private $previous;
   
    public function __construct($message, $code = 0, Exception $previous = null) {

        parent::__construct($message, $code);
       
        if (!is_null($previous))
        {
            $this->previous = $previous;
        }
    }

    // custom string representation of object
    public function __toString() {

        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function customFunction() {

        echo "A custom function for this type of exception\n";
    }
}

?>
