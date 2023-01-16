
function formatNumber(number){
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ")
}

function changeTab(tabId){
	const tabSwitcher = document.getElementsByClassName('tab-title');
	for (let tabTitle of tabSwitcher){
        tabTitle.classList.remove('active');
		BX(tabTitle.id.replace('-title', '')).classList.remove('active');
	}
	BX(tabId).classList.add('active');
    BX(tabId.replace('-title', '')).classList.add('active');
}

function mobileOpenTab(event){
    BX.toggleClass(event.target.parentNode, 'mobile-active')
}

function changeMainImage(image){
    const img_container = document.getElementById('gallery-image-container');
    const main_img = document.getElementsByClassName('product-image')[0];
    main_img.src = image.children[0].dataset.src;
}

function scrollGallery(index_shift){
    const gallery_container = document.getElementById('gallery-image-container')
    let index = parseInt(gallery_container.dataset.index);

    const length = gallery_container.children.length;
    index += index_shift;
    if (index > length-4){
        index = 0;
    }
    else if (index < 0){
        index = length-4;
    }
    gallery_container.style.transform = 'translateY(-' + (index * 96) + 'px)';
    gallery_container.dataset.index = index;
}

function setDetailListeners(){
    const gallery_container = BX('gallery-image-container');
    if (gallery_container) {
        const gallery_up = BX('gallery-arrow-up')
        const gallery_down = BX('gallery-arrow-down')
        // Click events
        if (gallery_up){
            gallery_up.addEventListener('click', function () {
                scrollGallery(-1)
            });
        }
        if (gallery_down){
            gallery_down.addEventListener('click', function () {
                scrollGallery(1)
            });
        }

        // Gallery events
        for (let i = 0; i < gallery_container.children.length; i++) {
            gallery_container.children[i].addEventListener('click', function () {
                changeMainImage(gallery_container.children[i])
            });
        }
    }

    // Mobile listeners
    for (const mobileTab of document.querySelectorAll(".mobile-tab")) {
        BX.bind(mobileTab, 'click', mobileOpenTab)
    }
}


function bindShowImage(src){
   let image_container = BX.create(
       'div', {
           props: {
               className:'img-overlay',
               id: 'image-popup',
           }
       }
   )
    let image_popup = new Popup(image_container, {focused: true, animation: 'fade', parent: BX('detail-image-container')})
    image_popup.showSrc = function (event){
       image_popup.popupDOM.innerHTML = `<img src='${event.target.src}'>`
        image_popup.show()
    }
    for (let element of document.querySelectorAll('.fullscreen-option')){

        BX.bind(element, 'click', BX.proxy(image_popup.showSrc, image_popup))
    }
}


function bindShowContacts(){
    let popup_container = BX.create(
        'div', {
            props: {
                className:'contact-overlay',
                id: 'contact-popup',
            },
            html: '<div class="contact-popup__text">' +
                'Чтобы оставить отзыв свяжитесь с нами по почте<br>' +
                '<a href="mailto: info@dobriy-jar.ru">info@dobriy-jar.ru</a> ' +
                '</div>' +
                '<div class="contact-popup__messengers">' +
                '<a class="contact-popup__messengers-tg" href="http://t.me/dobriyjar"></a> ' +
                '<a class="contact-popup__messengers-whatsapp" href="https://wa.me/79645036043?text=Здравствуйте%2C+хочу+оставить+отзыв"></a> ' +
                '</div>'
        }
    )
    let contact_popup = new Popup(popup_container, {focused: true, animation: 'fade', parent: BX('detail-image-container')})

    for (let element of document.querySelectorAll('.contact-popup')){

        BX.bind(element, 'click', BX.proxy(contact_popup.show, contact_popup))
    }
}

setDetailListeners()
bindShowImage()
bindShowContacts()



