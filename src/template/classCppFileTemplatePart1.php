<?php
/**
 * @author BourneSuper
 *
 */
// param1 header file name of the class
// param2 full class name
$str =<<<HEREDOC

#ifdef HAVE_CONFIG_H
# include "config.h"
#endif

#include "php.h"
#include <zend_exceptions.h>
#include "ext/standard/info.h"
        
#include "%s"



zend_class_entry * %s_ce;



HEREDOC;

return $str;