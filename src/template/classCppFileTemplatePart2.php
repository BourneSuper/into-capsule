<?php
/**
 * @author BourneSuper
 *
 */
// param1 full class name
// param2 method declaration
$str =<<<HEREDOC

zend_function_entry %s_functions[] = {
%s
    PHP_FE_END
};


HEREDOC;

return $str;