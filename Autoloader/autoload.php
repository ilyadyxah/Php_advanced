<?php

spl_autoload_register(function ($class) {
    $file = sprintf("classes/test/%s.php", str_replace(['_', '\\'], DIRECTORY_SEPARATOR, $class));
    if (file_exists($file)) {
        require $file;
    }
});

print new TestClasecho();