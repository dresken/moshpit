<?php
$docroot = isset($_SERVER['DOCUMENT_ROOT'])?$_SERVER['DOCUMENT_ROOT']:'/var/www/html';
set_include_path(
    //Skinner Library
    $docroot.'/_site/'
    //Symlink to MoshpitEngine version
    .$docroot.'/_engine/'
    //Standard include path
    .PATH_SEPARATOR.get_include_path()
);

spl_autoload_extensions(".class.php");
spl_autoload_register();
spl_autoload_register('\Errors\FourZeroFour::pageNotFound');

$url = \Site\Common::getValue($_SERVER, 'REDIRECT_URL', 'FourZeroFour.php');
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
    )
        ;                   

try {
    $page = new $class();
} catch (\Errors\Redirection $redirection) {
    $redirection->redirect();
} catch (Exception $error) {
    $page = new \Errors\GenericError($error);
}
?>