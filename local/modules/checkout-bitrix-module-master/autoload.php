<?php

function checkoutSPL($className)
{
    // Формируем корректное имя класса
    $class_name = str_replace('TinkoffCheckout\\', '', $className);
    $class_name = str_replace('\\', '/', $class_name) . '.php';

    $file_path = __DIR__ . '/src/' . $class_name;

    $file_path = str_replace('//', '/', $file_path);
    if (file_exists($file_path)) {
        require_once $file_path;
    }
}

spl_autoload_register('checkoutSPL');