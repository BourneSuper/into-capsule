<?php
/**
 * @author BourneSuper
 *
 */
// param1 class name
// param2 method name
// param3 class name
// param4 method name
// param4 visibility modifier eg. "ZEND_ACC_PUBLIC | ZEND_ACC_STATIC"
$str =<<<HEREDOC
    PHP_ME( %s, %s, %s_%s_ArgInfo, %s )
HEREDOC;

return $str;