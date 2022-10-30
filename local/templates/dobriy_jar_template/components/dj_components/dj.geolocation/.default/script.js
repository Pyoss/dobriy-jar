
class GeoData{
    constructor(domain_name, city_name, domain_id){
        if (domain_id === ''){
            return undefined;
        }
        this.domain_id = domain_id
        this.domain_name = domain_name
        this.city_name = city_name
    }
}

function processDomainData(current_domain, chosen_domain, ip_domain) {
    if (!chosen_domain.domain_id && ip_domain.domain_id !== undefined){
        suggestDomain(ip_domain);
    } else if (chosen_domain.domain_id !== current_domain.domain_id && chosen_domain.domain_id !== undefined){
        suggestDomain(chosen_domain);
    }
}

function setDomain(domain_id){
    BX.setCookie('DOMAIN_ID', domain_id, {expires: 86400, domain: '.dobriy-jar.ru', path:'/'})
    popupManagerDev.hidePopup('geo-conf')
}

function redirectDomain(domain_id, domain_name){
    BX.setCookie('DOMAIN_ID', domain_id, {expires: 86400, domain: '.dobriy-jar.ru', path:'/'})
    document.location = 'https://' + domain_name + window.location.pathname
}

function suggestDomain(domain_obj){
    let geo_conf_text = BX.create('div', {props:{className: 'geo--popup-conf-text'}, text: 'Ваш город - ' + domain_obj.city_name + '?'})
    let geo_conf_buttons = BX.create(
        'div',
        {props:{className: 'geo--buttons'},
        children: [
            BX.create('span',
                {props:
                    {
                        className: 'geo--button confirm'},'text': 'Да',
                        events:{
                            click: function(){redirectDomain(domain_obj.domain_id, domain_obj.domain_name)}
                        }
                }),
            BX.create('span', {props:{className: 'geo--button'}, 'text': 'Другой', events:{
                    click: function(){
                        showDomainChoicePopup()
                    }
                }}),
        ]
    })
    let geo_conf_node = BX.create('div', {props:{className: 'geo--popup-conf', id: 'geo-conf'}, children: [geo_conf_text, geo_conf_buttons]})
    let geo_popup = new Popup(geo_conf_node, {parent: document.getElementById('geo-wrapper')})
    geo_popup.show()
}


function showDomainChoicePopup(){
    if (document.getElementById('geo-conf')){
        popupManagerDev.hidePopup('geo-conf')
    }
    let popup_container = BX('geo-popup');
    if (!popup_container){
        let city_nodes_array = [BX.create('p',
            {props:
                    {className: 'geo--popup-title noselect'},
                text: 'Выбор региона'
            })]
        for (let geoData of domain_array){
            city_nodes_array.push(BX.create('span',
            {props:
                    {className: 'geo--popup-city noselect'},
                text: geoData.city_name,
                events:{click: function (){redirectDomain(geoData.domain_id, geoData.domain_name)}}
            }))}
        popup_container = BX.create('div', {props:{className: 'geo--popup', id: 'geo-popup'}, children: city_nodes_array})
    }
    let geo_choice = new Popup(popup_container, {parent: BX('geo'), focused: true, animation: 'fade'})
    geo_choice.show()

}

BX(
    function() {
        processDomainData(current_domain, chosen_domain, ip_domain)
        BX.bind(BX('geo'), 'click', function (){
            showDomainChoicePopup()}
        )
    }
)
