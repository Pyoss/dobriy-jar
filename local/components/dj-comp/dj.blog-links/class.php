<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
CModule::IncludeModule('dj.imgref');

use DJScripts\ImgRef;
use Bitrix\Main\Page\Asset;

class DJBlogComponent extends CBitrixComponent
{

    public function getFileId($path)
    {
        $path = explode('/', $path);
        unset($path[count($path) - 1]);
        unset($path[1]);;
        unset($path[0]);
        $res = CFile::getList(array(), ['SUBDIR' => implode('/', $path)]);

        return $res->fetch()['ID'];
    }

    public function loadJsLibs(){
            $assets = Asset::getInstance();
            $assets -> addJs(SITE_TEMPLATE_PATH . "/js/jquery-3.6.0.min.js");
            $assets -> addJs(SITE_TEMPLATE_PATH . "/js/slick.min.js");
        $assets -> addCss(SITE_TEMPLATE_PATH . "/css/slick.css");

    }

    public function isMobile()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    public function formatImage($file_path_or_id, $mobile_sizes, $desktop_sizes)
    {
        if(!is_numeric($file_path_or_id)){
            $file_path_or_id = DJbanners::getFileId($file_path_or_id);
        }
        $is_mobile = $this->isMobile();
        if ($is_mobile) {
            $size_array = $mobile_sizes;
        } else {
            $size_array = $desktop_sizes;
        }
        $img = ImgRef::optimizeImg($file_path_or_id, $size_array);
        return $img['auto'];
    }

}

?>