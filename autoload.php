<?php

namespace sskaje;

function autoload($classname)
{
    $classname = ltrim($classname, '\\');
    $filename  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($classname, '\\')) {
        $namespace = substr($classname, 0, $lastNsPos);
        $classname = substr($classname, $lastNsPos + 1);
        $filename  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $filename .= $classname . '.php';
    if (strpos($filename, 'sskaje/') === 0) {
        $filename = substr($filename, strlen('sskaje/'));
    }
    require $filename;
}

\spl_autoload_register('sskaje\autoload');

# EOF