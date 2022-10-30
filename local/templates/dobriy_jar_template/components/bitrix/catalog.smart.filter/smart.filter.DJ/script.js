function JCSmartFilter(ajaxURL, params) {
    this.ajaxURL = ajaxURL;
    this.form = null;
    this.timer = null;
    this.cacheKey = '';
    this.cache = [];
    this.popups = [];
    this.url_params = {};
    this.mobile_toggled = false;

    if (params && params.SEF_SET_FILTER_URL) {
        this.bindUrlToButton('set_filter', params.SEF_SET_FILTER_URL);
        this.sef = true;
    }
    if (params && params.SEF_DEL_FILTER_URL) {
        this.bindUrlToButton('del_filter', params.SEF_DEL_FILTER_URL);
    }

    let filter_node = document.querySelector('.filter-view')
    if (filter_node)
    {
        filter_node.style.display = null
        BX.bind(BX('activate-filter'), 'click', BX.delegate(function (){
            this.toggle_mobile()}, this))
        BX.bind(BX('filter-close'), 'click', BX.delegate(function (){
            this.toggle_mobile()}, this))
    }
    this.gatherParams()
}
JCSmartFilter.prototype.toggle_mobile = function (){
    BX.toggleClass(document.querySelector('.catalog-filter'), 'mobile-active');
    BX.toggleClass(BX('activate-filter'), 'active');
    this.mobile_toggled = !this.mobile_toggled
}

JCSmartFilter.prototype.mobile = function (){
    return window.innerWidth <= 980;
}

JCSmartFilter.prototype.mobileReveal = function (){
    return window.innerWidth <= 980;
}

JCSmartFilter.prototype.keyup = function () {
    if (!!this.timer) {
        clearTimeout(this.timer);
    }
    this.timer = setTimeout(BX.delegate(function () {
        this.reload();
    }, this), 500);
};

JCSmartFilter.prototype.click = function (main_filter = true) {
    if (main_filter) {
        this.url_params.page = 1
    }
    if (!!this.timer) {
        clearTimeout(this.timer);
    }

    this.timer = setTimeout(BX.delegate(function () {
        this.reload();
    }, this), 500);
};

JCSmartFilter.prototype.reload = function () {
    // boot up loading overlay
    if (!this.mobile_toggled){
        document.getElementById('catalog-loading-overlay').style.display = 'block';
    }
    if (this.cacheKey !== '') {
        //Postpone backend query if the cache key has already started booting
        if (!!this.timer) {
            clearTimeout(this.timer);
        }
        this.timer = setTimeout(BX.delegate(function () {
            this.reload();
        }, this), 1000);
        return;
    }

    this.cacheKey = '|';

    this.form = document.querySelector('.dj-filter');
    if (!this.form) {
        return;
    }

    let values = [];
    values[0] = {name: 'ajax', value: 'y'};
    this.gatherInputsValues(values, BX.findChildren(this.form, {'tag': new RegExp('^(input|select)$', 'i')}, true));
    this.values = values;
    for (let i = 0; i < values.length; i++)
        this.cacheKey += values[i].name + ':' + values[i].value + '|';
    for (const [key, value] of Object.entries(this.url_params)) {
        if (value) {
            this.cacheKey += key + ':' + value + '|'
        }
    }
    if (this.cache[this.cacheKey]) {
        this.postHandler(this.cache[this.cacheKey], true);
    } else {
        if (this.sef) {
            var set_filter = BX('set_filter');
            set_filter.disabled = true;
        }

        BX.ajax.loadJSON(
            this.ajaxURL,
            this.values2post(values),
            BX.delegate(this.postHandler, this)
        );
    }
};

JCSmartFilter.prototype.gatherParams = function () {
    const urlSearchParams = new URLSearchParams(window.location.search)
    this.url_params.page = urlSearchParams.get('PAGEN_1') || '1'
    this.url_params.price_sort = urlSearchParams.get('PRICE_SORT')
    return this.url_params;
}

// TODO: Разобраться с отключением фильтра при недоступности предложений
JCSmartFilter.prototype.updateItem = function (PID, arItem) {
    if (arItem.VALUES) {
        for (const i in arItem.VALUES) {
            if (arItem.VALUES.hasOwnProperty(i)) {
                const value = arItem.VALUES[i];
                const control = BX(value.CONTROL_ID);

                if (!!control) {
                    let label = document.querySelector('[data-role="label_' + value.CONTROL_ID + '"]');
                    if (value.DISABLED) {
                        if (label)
                            BX.addClass(label, 'disabled');
                        else
                            BX.addClass(control.parentNode, 'disabled');
                    } else {
                        if (label)
                            BX.removeClass(label, 'disabled');
                        else
                            BX.removeClass(control.parentNode, 'disabled');
                    }

                    if (value.hasOwnProperty('ELEMENT_COUNT')) {
                        label = document.querySelector('[data-role="count_' + value.CONTROL_ID + '"]');
                        if (label)
                            label.innerHTML = value.ELEMENT_COUNT;
                    }
                }
            }
        }
    }
};

JCSmartFilter.prototype.postHandler = function (result, fromCache) {

    let hrefFILTER, url;
    const modef = BX('modef');
    const modef_num = BX('modef_num');
    const del_filter = BX('del_filter');

    if (!!result && !!result.ITEMS) {

        for (const PID in result.ITEMS) {
            if (result.ITEMS.hasOwnProperty(PID)) {
                this.updateItem(PID, result.ITEMS[PID]);
            }
        }
        if (!!modef && !!modef_num) {
            modef_num.innerHTML = result.ELEMENT_COUNT;
            hrefFILTER = BX.findChildren(modef, {tag: 'A'}, true);

            if (result.FILTER_URL && hrefFILTER) {
                hrefFILTER[0].href = BX.util.htmlspecialcharsback(result.FILTER_URL);
            }

            if (result.FILTER_AJAX_URL && result.COMPONENT_CONTAINER_ID) {
                BX.unbindAll(hrefFILTER[0]);
                BX.bind(hrefFILTER[0], 'click', function (e) {
                    url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
                    BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
                    return BX.PreventDefault(e);
                });
            }
            modef.style.display = 'none'
            del_filter.style.display = 'none'
            for (let value of this.values){
                if (value['name'].includes('arrFilter')){
                    modef.style.display = null
                    del_filter.style.display = null
                    break
                }
            }

            let httpRequest = new XMLHttpRequest();
            if (!httpRequest) {
                alert('Giving up :( Cannot create an XMLHTTP instance');
                return false;
            }

            /* Установка параметров ajax_url */
            let ajaxUrl = new URL(result.FILTER_AJAX_URL, window.location.origin)

            let sort = this.url_params.price_sort
            if (!!sort && !fromCache) {
                ajaxUrl.searchParams.set("PRICE_SORT", sort);
            } else if (!fromCache) {
                ajaxUrl.searchParams.delete("PRICE_SORT");
            }

            let page = this.url_params.page
            if (!!page && !fromCache) {
                ajaxUrl.searchParams.set("PAGEN_1", page);
            } else if (!fromCache) {
                ajaxUrl.searchParams.delete("PAGEN_1");
            }

            function workResponse() {
                if (httpRequest.readyState === XMLHttpRequest.DONE) {
                    if (httpRequest.status === 200) {
                        const parser = new DOMParser();
                        const new_dom = parser.parseFromString(httpRequest.responseText,
                            "text/html")
                        const filtered_content = new_dom.querySelector('.catalog-products-container');
                        const filtered_navigation = new_dom.querySelector('.bx-pagination');

                        if (filtered_content.querySelector('.product-element') === null) {
                            filtered_content.innerHTML = 'К сожалению по выбранному фильтру найти товары не удалось.'
                        } else {
                            document.querySelector('.catalog-products-container').innerHTML = filtered_content.innerHTML;
                        }

                        for (let navdom of document.querySelectorAll('.bx-pagination')) {
                            navdom.innerHTML = filtered_navigation ? filtered_navigation.innerHTML : ''
                        }
                        document.getElementById('catalog-loading-overlay').style.display = 'none'

                    } else {
                        alert('There was a problem with the request.');
                    }
                }
            }

            /* Обработка аяксом при десктопной версии */
            if (!this.mobile_toggled){
                result.FILTER_AJAX_URL = ajaxUrl.href
                httpRequest.onreadystatechange = workResponse;
                httpRequest.open('GET', result.FILTER_AJAX_URL);
                httpRequest.send();
            }

            if (result.SEF_SET_FILTER_URL) {
                this.bindUrlToButton('set_filter', result.SEF_SET_FILTER_URL);
            }
        }
    }

    if (this.sef) {
        var set_filter = BX('set_filter');
        set_filter.disabled = false;
    }

    if (!fromCache && this.cacheKey !== '') {
        this.cache[this.cacheKey] = result;
    }
    this.cacheKey = '';
};

JCSmartFilter.prototype.bindUrlToButton = function (buttonId, url) {
    const button = BX(buttonId);
    if (button) {
        const proxy = function (j, func) {
            return function () {
                return func(j);
            }
        };

        if (button.type === 'submit')
            button.type = 'button';

        BX.bind(button, 'click', proxy(url, function (url) {
            window.location.href = url;
            return false;
        }));
    }
};

JCSmartFilter.prototype.gatherInputsValues = function (values, elements) {
    if (elements) {
        for (let i = 0; i < elements.length; i++) {
            const el = elements[i];
            if (el.disabled || !el.type)
                continue;

            switch (el.type.toLowerCase()) {
                case 'text':
                case 'textarea':
                case 'password':
                case 'hidden':
                case 'select-one':
                    if (el.value.length)
                        values[values.length] = {name: el.name, value: el.value};
                    break;
                case 'dropdown':
                    if (el.value.length) {
                        values[values.length] = {name: el.name, value: el.value};
                    }
                    break;
                case 'radio':
                case 'checkbox':
                    if (el.checked)
                        values[values.length] = {name: el.name, value: el.value};
                    break;
                case 'select-multiple':
                    for (let j = 0; j < el.options.length; j++) {
                        if (el.options[j].selected)
                            values[values.length] = {name: el.name, value: el.options[j].value};
                    }
                    break;
                default:
                    break;
            }
        }
    }
};

JCSmartFilter.prototype.values2post = function (values) {
    const post = [];
    let current = post;
    let i = 0;

    while (i < values.length) {
        const p = values[i].name.indexOf('[');
        if (p === -1) {
            current[values[i].name] = values[i].value;
            current = post;
            i++;
        } else {
            const name = values[i].name.substring(0, p);
            const rest = values[i].name.substring(p + 1);
            if (!current[name])
                current[name] = [];

            const pp = rest.indexOf(']');
            if (pp === -1) {
                //Error - not balanced brackets
                current = post;
                i++;
            } else if (pp === 0) {
                //No index specified - so take the next integer
                current = current[name];
                values[i].name = '' + current.length;
            } else {
                //Now index name becomes and name, and we go deeper into the array
                current = current[name];
                values[i].name = rest.substring(0, pp) + rest.substring(pp + 1);
            }
        }
    }
    return post;
};

JCSmartFilter.prototype.change_dropdown = function (element) {
    const selectBox = element;
    const selectedValue = selectBox.options[selectBox.selectedIndex];
    selectBox.name = selectedValue.attributes["name"].value;
    selectBox.value = selectedValue.value;
    selectedValue.selected = 'selected';
    this.click(element);
}

JCSmartFilter.prototype.selectDropDownItem = function (element, controlId) {
    this.keyup(BX(controlId));

    const wrapContainer = BX.findParent(BX(controlId), {className: "bx-filter-select-container"}, false);

    const currentOption = wrapContainer.querySelector('[data-role="currentOption"]');
    currentOption.innerHTML = element.innerHTML;
    BX.PopupWindowManager.getCurrentPopup().close();
};

BX.namespace("BX.Iblock.SmartFilter");

