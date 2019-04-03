<?php
namespace App\Util\Exceptions;

class FormInvalidException extends \Exception { 

    public function get_Message() :string
    {
        $errorMessage = $this->getMessage(); 

        return $errorMessage; 
    } 
}
