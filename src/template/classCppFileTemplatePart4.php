<?php
/**
 * @author BourneSuper
 *
 */
// param1 class name
// param2 method name
// param3 return_reference
// param4 required_num_args
// param5 zend arg info
$str =<<<HEREDOC
ZEND_BEGIN_ARG_INFO_EX( %s_%s_ArgInfo, 0, %s, %s )
%s
ZEND_END_ARG_INFO()
HEREDOC;

return $str;