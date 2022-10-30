/*  Скрипты для корзины */
BX(function () {
    const ajaxCart = new AjaxCart();
    const mobile_popup_button = document.querySelector('.footer-mobile--cart');

    BX.bind(BX('header-cart'), 'click', BX.delegate(ajaxCart.showCurrentCart, ajaxCart))
    BX.bind(mobile_popup_button, 'click', BX.delegate(ajaxCart.showCurrentCart, ajaxCart))

    BX.bindDelegate(
        document.querySelector('main'),
        'click',
        {
            className: 'basket-add'
        },
        BX.proxy(function () {
            this.basketAjaxAdd(event.target.dataset.productId, 1, BX.delegate(this.currentCartPopup, this));

            if (event.target.className.includes('goal_basket_v_add')){
                ym(61893639, 'reachGoal', 'goal_basket_v_add')
            }
            if (event.target.className.includes('goal_basket_add')){
                ym(61893639, 'reachGoal', 'goal_basket_add')
            }
        }, ajaxCart))
});

function formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ")
}

class AjaxCart {
    constructor(cart_id, orderBasket) {
        this.id = cart_id
        this.cartDOM = document.getElementById(cart_id)
        this.orderBasket = orderBasket || null
    }

    getProductRow(product_id){
        return this.cartDOM.querySelector('.cart-row[data-product-id="' + product_id + '"]')
    }

    incrementCartValue(event) {
        const product_id = event.target.dataset.productId
        const rowDOM = this.getProductRow(event.target.dataset.productId);
        const current_cart_value = rowDOM.querySelector('.cart-quantity').value;
        const new_value = this.validateQuan(parseInt(current_cart_value), 1);
        if (new_value !== current_cart_value) {
            rowDOM.querySelector('.cart-quantity').value = new_value;
            clearTimeout(window['basketUpdate' + product_id]);
            window['basketUpdate' + product_id] =
                setTimeout(this.basketAjaxUpdate.bind(this), 300, product_id, new_value, this.updateCartHtml.bind(this), false)
        }
    }

    decrementCartValue(event) {
        const product_id = event.target.dataset.productId;
        const rowDOM = this.getProductRow(event.target.dataset.productId);
        const current_cart_value = rowDOM.querySelector('.cart-quantity').value;
        const new_value = this.validateQuan(parseInt(current_cart_value), -1);
        if (new_value !== current_cart_value) {
            rowDOM.querySelector('.cart-quantity').value = new_value;
            clearTimeout(window['basketUpdate' + product_id]);
            window['basketUpdate' + product_id] =
                setTimeout(this.basketAjaxUpdate.bind(this), 300, product_id, new_value, this.updateCartHtml.bind(this), false)
        }
    }

    updateCartValue(event) {
        const product_id = event.target.dataset.productId;
        const new_value = this.validateQuan(event.target.value);
        clearTimeout(window['basketUpdate' + product_id]);
        window['basketUpdate' + product_id] =
            setTimeout(this.basketAjaxUpdate.bind(this), 300, product_id, new_value, this.updateCartHtml.bind(this), false)
    }

    deleteCartItem(event) {
        const product_id = event.target.dataset.productId;
        this.getProductRow(product_id).classList.add('transparent');
        clearTimeout(window['basketUpdate' + product_id]);
        window['basketUpdate' + product_id] =
            setTimeout(this.basketAjaxDelete.bind(this), 300, product_id, this.updateCartHtml.bind(this))
    }

    validateQuan(new_value, modificator) {
        if (isNaN(new_value)) {
            new_value = 1;
        } else {
            new_value = new_value + modificator;
        }
        if (new_value <= 0) {
            new_value = 1
        } else if (new_value > 99) {
            new_value = 99
        }
        return new_value
    }

    updateCartHtml(ajax_response) {
        const json_ajax_response = this.getJson(ajax_response);
        const new_basket_price = json_ajax_response.info.basket['PRICE'],
            new_basket_base_price = json_ajax_response.info.basket['BASE_PRICE'],
            new_basket_quantity = json_ajax_response.info.basket['QUANTITY']
        if ('item' in json_ajax_response.info) {
            const new_item_price = json_ajax_response.info.item['PRICE'],
                new_item_base_price = json_ajax_response.info.item['BASE_PRICE'],
                new_item_quantity = json_ajax_response.info.item['QUANTITY'],
                product_id = json_ajax_response.info.item['PRODUCT_ID']
            const item_element = this.getProductRow(product_id);
            item_element.querySelector('input.cart-quantity').value = formatNumber(new_item_quantity);

            if (new_item_quantity < 2) {
                item_element.querySelector('.cart-decrement').classList.add('inactive');
            } else {
                item_element.querySelector('.cart-decrement').classList.remove('inactive');
            }

            if (new_item_quantity > 99) {
                item_element.querySelector('.cart-increment').classList.add('inactive');
            } else {
                item_element.querySelector('.cart-increment').classList.remove('inactive');
            }
        } else if ('deleted_item' in json_ajax_response.info) {
            const deleted_item_id = json_ajax_response.info.deleted_item['PRODUCT_ID'];
            this.getProductRow(deleted_item_id).remove();
            if (json_ajax_response.info.basket === 'empty') {
                this.hideCart();
                return;
            }
        }
        if (!this.orderBasket){
            this.cartDOM.querySelector('.cart-footer--order-price').innerHTML = formatNumber(new_basket_price) + ' ₽';
            if (new_basket_price !== new_basket_base_price){
                this.cartDOM.querySelector('.cart-footer--order-bprice').innerHTML = formatNumber(new_basket_base_price) + ' ₽';
            } else {
                this.cartDOM.querySelector('.cart-footer--order-bprice').innerHTML = '';
            }
        } else {
            this.orderBasket.sendRequest()
        }
    }

    showCurrentCart() {
        BX.ajax.get('/personal/cart/ajax.php?basket_html=true', BX.delegate(this.currentCartPopup, this))
    }

    currentCartPopup(result) {
        let cart_popup, popupDOM;
        popupDOM = BX.create('div', {
            props:
                {className: 'cart-popup flying', id: 'cart-popup', innerHTML: result}
        })
        cart_popup = new Popup(popupDOM, {parent: document.body, focused: true, temp: true, animation: 'fade'})

        cart_popup.show()
        setCartListeners({cart_id: popupDOM.id})
        BX.bind(document.querySelector('.mobile-hide-button'), 'click', BX.delegate(this.hideCart, this))
        BX.bind(document.querySelector('.cart-close'), 'click', BX.delegate(this.hideCart, this))
        BX.bind(document.querySelector('.cart-footer--shop-button'), 'click', BX.delegate(this.hideCart, this))
    }

    getJson(ajax_response) {
        const parser = new DOMParser();
        const jsonResponse = parser.parseFromString(ajax_response, "text/html").querySelector('json').textContent;
        if (jsonResponse) {
            return JSON.parse(jsonResponse);
        }
        return '';
    }

    basketAjaxRequest(query, func) {
        BX.ajax.get('/personal/cart/ajax.php' + query, function (result){
            let ac = new AjaxCart
            const json_ajax_response = ac.getJson(result);
            if (json_ajax_response.info.basket.QUANTITY){
                BX("basket-quantity").innerText = json_ajax_response.info.basket.QUANTITY
            } else {
                BX("basket-quantity").innerText = ''
            }
            func(result)
        });
    }

    basketAjaxUpdate(product_id, quantity, func) {
        let query = '?action=UPDATE&product_id=' + product_id + '&quantity=' + quantity;
        this.basketAjaxRequest(query, func)
    }

    basketAjaxAdd(product_id, quantity, func) {
        let query = '?action=ADD&product_id=' + product_id + '&basket_html=true&quantity=' + quantity;
        this.basketAjaxRequest(query, func)
        let name = ''
        if (BX('type-' + product_id)){
            name = BX('type-' + product_id).textContent + ' ' +  BX('name-' + product_id).textContent
        } else {
            name = BX('name-' + product_id).textContent
        }
        try {
            dataLayer.push({
                "ecommerce": {
                    "currencyCode": "RUB",
                    "add": {
                        "products": [
                            {
                                "id": BX('article-' + product_id).textContent,
                                "name": name,
                                "price": parseFloat(BX('price-' + product_id).textContent.replace(/[^0-9.-]+/g,"")),
                                "quantity": quantity
                            }
                        ]
                    }
                }
            });
        } catch (error) {
            console.error(error);
            // expected output: ReferenceError: nonExistentFunction is not defined
            // Note - error messages will vary depending on browser
        }
    }

    basketAjaxDelete(product_id, func) {

        let query = '?action=DELETE&product_id=' + product_id;
        this.basketAjaxRequest(query, func)
    }

    hideCart() {
        popupManagerDev.hidePopup('cart-popup')
        mobileMenu.showMobileHeader();
    }
}

function setCartListeners(parameters= {cart_id: 'order-cart'}, orderBasket=false) {
    let ajax_cart = new AjaxCart(parameters.cart_id, orderBasket)
    const cart_rows = ajax_cart.cartDOM.querySelectorAll('.cart-row');
    for (const product_row of cart_rows) {
        product_row.querySelector('.cart-increment').addEventListener('click',
            ajax_cart.incrementCartValue.bind(ajax_cart))
        product_row.querySelector('.cart-decrement').addEventListener('click',
            ajax_cart.decrementCartValue.bind(ajax_cart))
        product_row.querySelector('.cart-row--del-block').addEventListener('click',
            ajax_cart.deleteCartItem.bind(ajax_cart))
        product_row.querySelector('input.cart-quantity').addEventListener('change',
            ajax_cart.updateCartValue.bind(ajax_cart))
    }
}