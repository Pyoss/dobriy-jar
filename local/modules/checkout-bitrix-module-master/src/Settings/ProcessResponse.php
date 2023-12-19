<?php

namespace TinkoffCheckout\Settings;

use COption;

class ProcessResponse
{
    public static function process($moduleID, $fields, $globals, $files = [], $validator = null)
    {
        list($Update, $Apply, $RestoreDefaults) = $globals;

        $validateString = $Update . $Apply . $RestoreDefaults;
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || strlen($validateString) == 0 || !check_bitrix_sessid()) {
            return;
        }

        // Сбрасываем значения по умолчанию
        if ($RestoreDefaults) {
            foreach ($fields as $fieldID) {
                COption::RemoveOption($fieldID);
            }
            foreach ($files as $file) {
                COption::RemoveOption($file);
            }
        } else {
            // Сохранение значений полей
            foreach ($fields as $fieldID) {
                $value = isset($_REQUEST[$fieldID]) && ($_REQUEST[$fieldID] || $_REQUEST[$fieldID] === '0') ? $_REQUEST[$fieldID] : '';
                $value = is_array($value) ? json_encode($value) : $value;

                if (is_callable($validator)){
                    $value = $validator($value, $fieldID, $moduleID);
                }

                COption::SetOptionString($moduleID, $fieldID, $value);
            }

            $storage = __DIR__ . '/../../storage/uploads';
            foreach ($files as $file) {
                $fileIndex = $file . '_file';

                $filesQuery  = isset($_FILES[$fileIndex]) && $_FILES[$fileIndex] ? $_FILES[$fileIndex] : null;
                $fileTmpPath = $filesQuery ? $filesQuery['tmp_name'] : null;
                if (!$filesQuery || !$fileTmpPath) {
                    continue;
                }

                $name = basename(time() . $filesQuery['name']);
                $path = $storage . '/' . time() . $name;
                if (move_uploaded_file($fileTmpPath, $path)) {
                    $path = realpath($path);
                    COption::SetOptionString($moduleID, $file, $path);
                }
            }
        }
    }
}