<?php
$docroot = isset($_SERVER['DOCUMENT_ROOT'])?$_SERVER['DOCUMENT_ROOT']:'/var/www/html';
set_include_path(
    //Skinner Library
    $docroot.'/_site/'
    //Symlink to MoshpitEngine version
    .PATH_SEPARATOR.$docroot.'/_engine/'
    //Standard include path
    .PATH_SEPARATOR.get_include_path()
);
spl_autoload_extensions(".class.php");
spl_autoload_register();
spl_autoload_register('\Errors\FourZeroFour::pageNotFound');
spl_autoload_register('\Errors\GenericError::showerror');

/** /function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler("exception_error_handler");
/**/
$url = \Moshpit\Common::getValue($_SERVER, 'REDIRECT_URL', 'FourZeroFour');
//echo $url;
//Generate a class name to execute from URL
$class = 
    '\\Pages'.
    preg_replace('|\\\\$|', '\\Index',                  // if ends with a backslash add index
        preg_replace('| |', '\\',                       // Replace spaces with \
            preg_replace('|-|', '_',                    // Replace - with _
                preg_replace('|\.|', '__',              // Replace . with __
                    ucwords(strtolower(                 // Lowercase whole string and uppercase Each first letter
                        preg_replace('|/|', ' ', $url)  // replace forward slash with space
                    ))
                )
            )
        )
    );
//echo $class;
//exit();
try {
    $page = new $class();
} catch (\Errors\Redirection $redirection) {
    $redirection->redirect();
} catch (\Exception $error) {
    $page = new \Errors\GenericError($error);
}
?>