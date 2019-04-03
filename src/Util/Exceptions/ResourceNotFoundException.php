<?php

namespace App\Util\Exceptions;

class ResourceNotFoundException extends \Exception { 

    public function get_Message() :string
    { 
          
        $errorMesage = $this->getMessage() . " is not found"; 

        return $errorMesage;
    } 
} 
