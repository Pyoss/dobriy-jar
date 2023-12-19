<?php

use TinkoffCheckout\Settings\Helpers\SettingsFields;

if ( ! defined( 'B_PROLOG_INCLUDED' ) || B_PROLOG_INCLUDED !== true ) {
	die();
}

if ( ! IsModuleInstalled( 'tinkoff.checkout' ) || ! isset( $arParams ) ) {
	return;
}

// Не страшно если закешируется. Необходимо лишь возможности добавлять несколько кнопок
$buttonID = 'tinkoff-checkout-button-' . time() . rand( 0, 10 );

function getTinkoffCheckoutSettings( $arParams, $index, $default = '' ) {
	if ( isset( $arParams[ strtolower( $index ) ] ) && $arParams[ strtolower( $index ) ] ) {
		return $arParams[ strtolower( $index ) ];
	}

	$index  = SettingsFields::getFieldName( $index );
	$option = COption::GetOptionString( 'tinkoff.checkout', $index );
	$option = json_decode( $option ) ? json_decode( $option, true ) : $option;
	$option = is_string( $option ) ? htmlspecialcharsbx( $option ) : $option;
	if ( $option || $option === '0' ) {
		return $option;
	}

	return $default;
}

?>
<style>
    tcs-checkout-button {
        --tcs-checkout-button-border-radius: <?=getTinkoffCheckoutSettings($arParams, TINKOFF_CHECKOUT_BUTTON_FIELD_BORDER_RADIUS, '8')?>px;
        --tcs-checkout-button-width: <?=getTinkoffCheckoutSettings($arParams, TINKOFF_CHECKOUT_BUTTON_FIELD_WIDTH, '320')?>px;
        --tcs-checkout-button-height: <?=getTinkoffCheckoutSettings($arParams, TINKOFF_CHECKOUT_BUTTON_FIELD_HEIGHT, '44')?>px;
    }
</style>

<div id="<?= $buttonID ?>"></div>

<script>
  const backend = {
    createOrder() {
      return BX.ajax.runAction('tinkoff:checkout.checkout.getCheckoutRedirect',)
        .then(function (response) {
          const redirect = response.data.url
          if (!redirect) {
            reject(null)
            return
          }

          window.location.href = redirect
        })
    }
  }

  function onLoadCheckoutSdk() {
    new TcsCheckoutButton(backend).mount(
      document.querySelector('#<?=$buttonID?>'),
      {
        theme: TcsCheckoutButton.Theme.<?=getTinkoffCheckoutSettings(
			$arParams,
			TINKOFF_CHECKOUT_BUTTON_FIELD_THEME,
			'LIGHT'
		)?>,
        type: TcsCheckoutButton.Type.<?=getTinkoffCheckoutSettings(
			$arParams,
			TINKOFF_CHECKOUT_BUTTON_FIELD_BACKGROUND,
			'BLACK'
		)?>,
        icon: !!<?=getTinkoffCheckoutSettings( $arParams, TINKOFF_CHECKOUT_BUTTON_FIELD_IS_SHOW_LOGO, 'true' )?>
      }
    )
  }
</script>
<script async onload="onLoadCheckoutSdk()"
        src="https://business.cdn-tinkoff.ru/static/projects/checkout/sdk/index.js"></script>