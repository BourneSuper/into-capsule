<?php
/**
 * @author BourneSuper
 *
 */
// param1 string lowercase snakecase extentsion name
// param2 string capital snakecase extentsion name
// param3 string capital snakecase extentsion name
// param4 string lowercase snakecase extentsion name
// param5 string lowercase snakecase extentsion name
// param6 string lowercase snakecase extentsion name
// param7 string capital snakecase extentsion name
// param8 string capital snakecase extentsion name
// param9 string capital snakecase extentsion name
$str =<<<HEREDOC

/* %s extension for PHP */

#ifndef PHP_%s_H
# define PHP_%s_H

extern zend_module_entry %s_module_entry;
# define phpext_%s_ptr &%s_module_entry

# define PHP_%s_VERSION "0.1.0"

# if defined(ZTS) && defined(COMPILE_DL_%s)
ZEND_TSRMLS_CACHE_EXTERN()
# endif

#endif  /* PHP_%s_H */
        
        

HEREDOC;

return $str;

