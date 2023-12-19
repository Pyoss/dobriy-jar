<?php

namespace TinkoffCheckout\Settings\Builders;

class SettingsTabsBuilder extends Builder
{
    /** @var TabBuilder[] */
    private $tabs = [];
    private $headline = '';

    public function build($APPLICATION)
    {
        IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/options.php");

        $rights = $APPLICATION->GetGroupRight("subscribe");
        if ($rights == "D") {
            $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
        }

        $tabs = [];
        foreach ($this->tabs as $tab) {
            $tabs[] = [
                'DIV'   => $tab->getId(),
                'TAB'   => $tab->getName(),
                'ICON'  => 'perfmon_settings',
                'TITLE' => $tab->getHeadline()
            ];
        }
        $tabs = new \CAdminTabControl("tabControl", $tabs);

        \CModule::IncludeModule($this->getModuleID());

        $formURL = $this->getFormURL($APPLICATION, LANGUAGE_ID);

        echo '<h1>' . $this->getHeadline() . '</h1>';

        echo '<form action="' . $formURL . '" method="POST" enctype="multipart/form-data">';
        $tabs->Begin();
        foreach ($this->tabs as $tab) {
            $tabs->BeginNextTab();
            echo bitrix_sessid_post();
            $tab->build();
//            $tab->build();
        }

        $tabs->Buttons(
            array(
                "disabled" => $rights < "W",
                "back_url" => "settings.php?mid=" . $this->moduleID . "&lang=" . LANG,

            )
        );
        $tabs->End();
        echo '</form>';
    }

    private function getFormURL($APPLICATION, $langID)
    {
        $formQuery = http_build_query([
            'mid'  => $this->getModuleID(),
            'lang' => $langID
        ]);

        return $APPLICATION->GetCurPage() . '?' . $formQuery;
    }

    public function addTab($tab)
    {
        $this->tabs[] = $tab;
    }

    /**
     * @return string
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * @param string $headline
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;
    }


}