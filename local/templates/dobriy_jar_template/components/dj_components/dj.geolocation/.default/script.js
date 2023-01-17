class GeoData {
    constructor(domain_name, city_name, domain_id, key_city = false) {
        if (domain_id === '') {
            return undefined;
        }
        this.domain_id = domain_id
        this.domain_name = domain_name
        this.city_name = city_name
        this.key_city = key_city
    }
}

function processDomainData(current_domain, chosen_domain, ip_domain) {
    if (!chosen_domain.domain_id && ip_domain.domain_id !== undefined) {
        suggestDomain(ip_domain);
    } else if (chosen_domain.domain_id !== current_domain.domain_id && chosen_domain.domain_id !== undefined) {
        suggestDomain(chosen_domain);
    }
}

function setDomain(domain_id) {
    BX.setCookie('DOMAIN_ID', domain_id, {expires: 86400, domain: '.dobriy-jar.ru', path: '/'})
    popupManagerDev.hidePopup('geo-conf')
}

function redirectDomain(domain_id, domain_name) {
    BX.setCookie('DOMAIN_ID', domain_id, {expires: 86400, domain: '.dobriy-jar.ru', path: '/'})
    document.location = 'https://' + domain_name + window.location.pathname
}

function suggestDomain(domain_obj) {
    let geo_conf_text = BX.create('div', {
        props: {className: 'geo--popup-conf-text'},
        text: 'Ваш город - ' + domain_obj.city_name + '?'
    })
    let geo_conf_buttons = BX.create(
        'div',
        {
            props: {className: 'geo--buttons'},
            children: [
                BX.create('span',
                    {
                        props:
                            {
                                className: 'geo--button confirm'
                            }, 'text': 'Да',
                        events: {
                            click: function () {
                                redirectDomain(domain_obj.domain_id, domain_obj.domain_name)
                            }
                        }
                    }),
                BX.create('span', {
                    props: {className: 'geo--button'}, 'text': 'Другой', events: {
                        click: function () {
                            showDomainChoicePopup()
                        }
                    }
                }),
            ]
        })
    let geo_conf_node = BX.create('div', {
        props: {className: 'geo--popup-conf', id: 'geo-conf'},
        children: [geo_conf_text, geo_conf_buttons]
    })
    let geo_popup = new Popup(geo_conf_node, {parent: document.getElementById('geo-wrapper')})
    geo_popup.show()
}


function showDomainChoicePopup() {
    if (document.getElementById('geo-conf')) {
        popupManagerDev.hidePopup('geo-conf')
    }
    let popup_container = BX('geo-popup');
    if (!popup_container) {
        let geo_title = BX.create('div', {
            props:
                {className: 'geo--popup-title'},
            text: 'Уточните ваш регион'
        })
        let geo_close = BX.create('span', {
            props:
                {className: 'geo--popup-close'},
            text: 'X',
            events: {
                click: () => {
                    console.log(1)
                    popupManagerDev.hidePopup('geo-popup')
                }
            }
        })

        let geo_chosen_city = BX.create('div', {
            props:
                {className: 'geo--popup-chosen-city'},
            html: '<span>Выбран город: </span><span class="geo--chosen-city">' + current_domain.domain_name + '</span>'
        })

        let geo_city_input = BX.create('input', {
            props:
                {className: 'geo--popup-city-search'},
            attrs: {
                type: 'text',
                placeholder: 'Поиск города'
            },
            events: {
                input: (event) => {
                    let query = event.target.value
                    for (let city of document.querySelectorAll('.geo--popup-city')) {
                        if (!(city.textContent.toLowerCase().indexOf(query.toLowerCase()) >= 0)) {
                            city.style.display = 'none'
                        } else {
                            city.style.display = ''
                        }
                    }
                }
            }
        })
        let key_city_nodes_array = []
        for (let geoData of domain_array) {
            if (!geoData.key_city) {
                continue
            }
            key_city_nodes_array.push(BX.create('span',
                {
                    props:
                        {className: 'geo--popup-city-key noselect'},
                    text: geoData.city_name,
                    events: {
                        click: function () {
                            redirectDomain(geoData.domain_id, geoData.domain_name)
                        }
                    }
                }))
        }
        let key_city_nodes = BX.create('div',
            {
                props: {
                    className: 'geo--popup-cities-key'
                }, children: key_city_nodes_array
            })

        let geo_nodes_title = BX.create('h5', {
            props:
                {className: 'geo--nodes-title'},
            text: 'Города с розничными магазинами'
        })

        let city_nodes_array = []
        for (let geoData of domain_array) {
            city_nodes_array.push(BX.create('span',
                {
                    props:
                        {className: 'geo--popup-city noselect'},
                    text: geoData.city_name,
                    events: {
                        click: function () {
                            redirectDomain(geoData.domain_id, geoData.domain_name)
                        }
                    }
                }))
        }
        let city_nodes = BX.create('div',
            {
                props: {
                    className: 'geo--popup-cities'
                }, children: city_nodes_array
            })


        popup_container = BX.create('div', {
            props: {className: 'geo--popup', id: 'geo-popup'},
            children: [
                geo_title,
                geo_close,
                geo_chosen_city,
                geo_city_input,
                key_city_nodes,
                geo_nodes_title,
                city_nodes]
        })
    }
    var geo_choice = new Popup(popup_container, {parent: BX('geo'), focused: true, animation: 'fade'})
    geo_choice.show()

}

BX(
    function () {
        processDomainData(current_domain, chosen_domain, ip_domain)
        BX.bind(BX('geo'), 'click', function () {
                showDomainChoicePopup()
            }
        )
        BX.bind(BX('geo-alt'), 'click', function () {
                showDomainChoicePopup()
            }
        )
    }
)
