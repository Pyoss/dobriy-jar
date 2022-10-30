<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * Class AjaxRes
 * Базовый класс для асинхронной работы
 */
abstract class AjaxRes {
    public $actionComplete;
    public $jsonResponse = [];

    public function sendJsonResponse(){
        echo json_encode(['status' => $this -> actionComplete, 'info' => $this -> jsonResponse]);
    }
}
