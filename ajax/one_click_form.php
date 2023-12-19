<?php if(!$_POST){die();}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$mail_message = "Пользователь запросил обратный звонок:\n";
$mail_message .= "Имя: " . ($_POST['name'] ?: 'не указано') . "\n";
$mail_message .= "Телефон: " . $_POST['phone'] . "\n";
$mail_message .= "Товар: " . $_POST['product'] . "\n";
$res = mail('nazliev@dobriy-jar.ru', 'Покупка в 1 клик', $mail_message);
$res = mail('kaushkal@dobriy-jar.ru', 'Покупка в 1 клик', $mail_message);
?>
<?=json_encode(array('mail_sent' => $res,
    'name' => $_POST['name'],
    'phone' => $_POST['phone'],
    'product' => $_POST['product']))?>

