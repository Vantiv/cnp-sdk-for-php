<?php
/**
 * Created by PhpStorm.
 * User: araju
 * Date: 11/7/18
 * Time: 1:52 PM
 */

namespace cnp\sdk\exceptions;


use Exception;

class SchemaErrorHandler extends Exception
{
    public function __construct()
    {
    }

    function libxml_display_error($error)
    {
        $return = "\n";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning $error->code: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "Error Validating Schema\n $error->code: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code: ";
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .=    " in $error->file";
        }
        $return .= " on line $error->line\n";

        return $return;
    }

    function libxml_display_errors() {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            print $this->libxml_display_error($error);
        }
        libxml_clear_errors();
    }

}