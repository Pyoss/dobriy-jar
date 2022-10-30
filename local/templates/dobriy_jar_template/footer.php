<?php
$APPLICATION->IncludeComponent(
	"dj_components:main.feedback", 
	"dj_feedback", 
	array(
		"EMAIL_TO" => "nazliev@dobriy-jar.ru",
		"EVENT_MESSAGE_ID" => array(
		),
		"OK_TEXT" => "Спасибо, ваше сообщение принято.",
		"REQUIRED_FIELDS" => array(
			0 => "NAME",
			1 => "EMAIL",
			2 => "PHONE",
			3 => "CITY",
		),
		"USE_CAPTCHA" => "N",
		"COMPONENT_TEMPLATE" => "dj_feedback",
		"BACKGROUND_FILE" => "/upload/medialibrary/988/6lftgi0idkcxwrhmhg7cpjhz2zj11kdw.png"
	),
	false
); ?></main>
<footer class="footer">
    <div class="footer-wrapper">
        <div class="footer-social-block">
            <div class="footer-logo">
            </div>
            <div class="footer-phone-block">
                <a class="phone-main" href="tel:8(800) 600-45-96"><i class="inline-icon footer-phone-icon"></i> 8(800) 600-45-96</a>
                <span class="call-back callback_button">ЗАКАЗАТЬ ЗВОНОК</span>
            </div>
            <div class="footer-social-network">
                <span class="footer-social-network--title">Мы в соцсетях
                </span>
                <ul class="footer-social-network--list">
                    <li><a href="https://vk.com/distillyatory" class="inline-icon vk-icon"></a></li>
                    <li><a href="https://www.youtube.com/c/%D0%94%D0%BE%D0%B1%D1%80%D1%8B%D0%B9%D0%96%D0%B0%D1%80%D0%B4%D0%BE%D0%B1%D1%80%D1%8B%D0%B9_%D0%B6%D0%B0%D1%80" class="inline-icon youtube-icon"></a></li>
                    <li><a href="https://ok.ru/dobriyjar" class="inline-icon ok-icon"></a></li>
                    <li><a href=" https://zen.yandex.ru/dobriy_jar" class="inline-icon zen-icon"></a></li>
                </ul>
            </div>
        </div>
        <div class="footer-link-groups">
            <?php $APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"menu.footer", 
	array(
		"COMPONENT_TEMPLATE" => "menu.footer",
		"ROOT_MENU_TYPE" => "catalog",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"EXT_ONLY" => "Y",
		"FOOTER_MENU_NAME" => "Каталог",
		"CHILD_MENU_TYPE" => "left"
	),
	false
);?>
            <?php $APPLICATION->IncludeComponent(
                'bitrix:menu',
                'menu.footer',
                [
                    'COMPONENT_TEMPLATE' => 'menu.footer',
                    'ROOT_MENU_TYPE' => 'support',
                    'MENU_CACHE_TYPE' => 'N',
                    'MENU_CACHE_TIME' => '3600',
                    'MENU_CACHE_USE_GROUPS' => 'Y',
                    'MENU_CACHE_GET_VARS' => [
                    ],
                    'MAX_LEVEL' => '1',
                    'USE_EXT' => 'Y',
                    'DELAY' => 'N',
                    'ALLOW_MULTI_SELECT' => 'N',
                    'FOOTER_MENU_NAME' => 'Поддержка'
                ],
                false
            );?>
            <?php $APPLICATION->IncludeComponent(
                'bitrix:menu',
                'menu.footer',
                [
                    'COMPONENT_TEMPLATE' => 'menu.footer',
                    'ROOT_MENU_TYPE' => 'blog',
                    'MENU_CACHE_TYPE' => 'N',
                    'MENU_CACHE_TIME' => '3600',
                    'MENU_CACHE_USE_GROUPS' => 'Y',
                    'MENU_CACHE_GET_VARS' => [
                    ],
                    'MAX_LEVEL' => '1',
                    'USE_EXT' => 'Y',
                    'DELAY' => 'N',
                    'ALLOW_MULTI_SELECT' => 'N',
                    'FOOTER_MENU_NAME' => 'Блог ДЖ'
                ],
                false
            );?>
            <?php $APPLICATION->IncludeComponent(
                'bitrix:menu',
                'menu.footer',
                [
                    'COMPONENT_TEMPLATE' => 'menu.footer',
                    'ROOT_MENU_TYPE' => 'dj',
                    'MENU_CACHE_TYPE' => 'N',
                    'MENU_CACHE_TIME' => '3600',
                    'MENU_CACHE_USE_GROUPS' => 'Y',
                    'MENU_CACHE_GET_VARS' => [
                    ],
                    'MAX_LEVEL' => '1',
                    'USE_EXT' => 'Y',
                    'DELAY' => 'N',
                    'ALLOW_MULTI_SELECT' => 'N',
                    'FOOTER_MENU_NAME' => 'Добрый Жар'
                ],
                false
            );
            /***
             * Устанавливаем title и description с учетом GEODJ
             */
            foreach ($GLOBALS['SEO_PROP'] as $prop_id => $prop_value){
                $APPLICATION->SetPageProperty($prop_id, $prop_value);
            }
            $title = $APPLICATION -> GetPageProperty('title');
            DJgeo::geoReplace($title);
            $APPLICATION -> SetPageProperty('title', $title);
            $description = $APPLICATION -> GetPageProperty('description');
            DJgeo::geoReplace($description);
            $APPLICATION -> SetPageProperty('description', $description);
            ?>
        </div>
    </div>
    </div>
</footer>
<?php if ($GLOBALS['show_canonical']){
    ?><link rel="canonical"
            href="https://<?=strtok($_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"], '?');?>"/><?php
}
include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . "/analytics.php";
?>