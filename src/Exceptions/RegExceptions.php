<?php

namespace RegApi\Exceptions;

use Exception;

class RegExceptions extends Exception
{
    /**
     * @param Exception $e
     *
     * @return $this
     */
    public static function render(Exception $e)
    {
        switch ($e->message) {
            default:
                $return = redirect()->back()->withErrors([$e->getMessage()]);
        }

        return $return;
    }
}
