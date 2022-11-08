class PopupManager {
    constructor() {
        this.openedPopup = null;
        this.popupObjs = {};
        this.blockedInteraction = false;
    }

    registerPopup(open_func, close_func, id) {
        this.popupObjs[id] = {
            'open': open_func,
            'close': close_func,
            'id': id
        };
    }

    closeCurrentPopup() {
        this.openedPopup.close();
        this.openedPopup = null;
    }

    openPopup(popup_id) {
        this.openedPopup = this.popupObjs[popup_id];
        this.openedPopup.open();
    }


    togglePopup(event) {
        if (this.blockedInteraction) {
            return false;
        }
        console.log(event);
        const popup_id = event.target.dataset.popupName;
        this.blockedInteraction = true;
        if (this.openedPopup == null) {
            this.openPopup(popup_id);
            setTimeout(function () {
                popupManager.blockedInteraction = false;
            }, 1000);
            return true;
        } else if (this.openedPopup.id === popup_id) {
            this.closeCurrentPopup();
            setTimeout(function () {
                popupManager.blockedInteraction = false;
            }, 500);
            return true;
        } else {
            this.closeCurrentPopup();
            setTimeout(function () {
                popupManager.openPopup(popup_id);
            }, 300)
            setTimeout(function () {
                popupManager.blockedInteraction = false;
            }, 500);
            return true;
        }
    }

    closePopup(event) {
        if (this.blockedInteraction) {
            return true;
        }
        if (this.openedPopup.id === event.target.dataset.popupName) {
            this.blockedInteraction = true;
            setTimeout(function () {
                popupManager.blockedInteraction = false;
                popupManager.openPopup.close();
            }, 3000)
        }
    }
}

class MobileMenuController {

    hideMobileHeader() {
        this.headerDOM.style.visibility = 'hidden'
    }

    showMobileHeader() {
        this.headerDOM.style.visibility = 'visible'
    }

    constructor() {
        this.lastKnownScrollPosition = 0;
        this.Ydistance_counter = 0;
        this.Ydistance_limit = 200;
        this.Ytop_limit = 0;
        this.headerShown = false;
        this.headerDOM = document.querySelector('header .header-center');
    }

    onScroll(event) {
        if (this.Ytop_limit > window.scrollY || window.scrollY > this.lastKnownScrollPosition) {
            this.Ydistance_counter = 0;
            if (this.headerShown) {
                this.unfixMobileHeader();
            }
        } else {
            this.Ydistance_counter = this.Ydistance_counter - window.scrollY + this.lastKnownScrollPosition

            if (this.Ydistance_counter > this.Ydistance_limit && !this.headerShown) {
                this.fixMobileHeader();
            }
        }
        this.lastKnownScrollPosition = window.scrollY;
    }

    unfixMobileHeader() {
        this.headerDOM.classList.remove('top-fixed');
        this.headerShown = false;
    }

    fixMobileHeader() {
        this.headerDOM.classList.add('top-fixed');
        this.headerShown = true;
    }

}

class Swiper {
    constructor() {
        document.addEventListener('touchstart', this.handleTouchStart.bind(this), false);
        document.addEventListener('touchmove', this.handleTouchMove.bind(this), false);

        this.xDown = null
        this.yDown = null
        this.swipe_left_funcs = []
        this.swipe_up_funcs = []
        this.swipe_down_funcs = []
    }

    getTouches(evt) {
        return evt.touches
    }

    handleTouchStart(evt) {
        const firstTouch = this.getTouches(evt)[0];
        this.xDown = firstTouch.clientX;
        this.yDown = firstTouch.clientY;
    }

    handleTouchMove(evt) {
        if (!this.xDown || !this.yDown) {
            return;
        }

        let xUp = evt.touches[0].clientX;
        let yUp = evt.touches[0].clientY;

        let xDiff = this.xDown - xUp;
        let yDiff = this.yDown - yUp;

        if (Math.abs(xDiff) > Math.abs(yDiff)) {/*most significant*/
            if (xDiff > 0) {
                /* right swipe */
            } else {
                for (let swipe_func of this.swipe_left_funcs) {
                    swipe_func();
                }
            }
        } else {
            if (yDiff > 0) {
                for (let swipe_func of this.swipe_down_funcs) {
                    swipe_func();
                }
            } else {
                for (let swipe_func of this.swipe_up_funcs) {
                    swipe_func();
                }
            }
        }
        /* reset values */
        this.xDown = null;
        this.yDown = null;
    }
}

var mobileMenu = new MobileMenuController();
var popupManager = new PopupManager()
document.addEventListener('scroll', mobileMenu.onScroll.bind(mobileMenu), {passive: false});

/*------------------------------------------------------------------------*/
let popup_class = 'popup' // Класс для работы с попапами
let popup_toggle_class = 'popup-toggle' // активация скрытого попапа
let popup_fixed_class = 'popup-fixed' // попап с данным классом находится по центру страницы
let animationTimeoutIn = 100
let animationTimeoutOut = 300

class PopupManagerDev {
    /***
     * Класс содержит в себе информацию о текущих открытых всплывающих окнах
     * и оверлее. Все действия, касающиеся добавления и модификации самих окон
     * должны быть реализованы в классе Popup
     */

    constructor() {
        this.focusedPopup = null;
        this.open_popups = {};
        this.overlay = null;
    }

    ifPopupOpened(popup_id){
        if(!BX(popup_id)){
            return false
        } else {
            return this.open_popups.hasOwnProperty(popup_id);
        }
    }

    hidePopup(id) {
        if (this.ifPopupOpened(id)) {
            this.open_popups[id].hide()
        }
    }

    setOverlay(popup) {
        if (this.overlay === null) {
            let overlayDOM = BX.create('div',
                {
                    props: {className: 'popup-overlay'},
                    events: {
                        click: function (event) {
                            if (event.target === overlayDOM){
                                popupManagerDev.focusedPopup.hide()
                            }
                        }
                    }
                })

            if (popup.animation !== 'instant') {
                overlayDOM.classList.add('animation-fade')
                setTimeout(
                    BX.delegate(function () {
                        overlayDOM.classList.remove('animation-fade')
                    }, this),
                    animationTimeoutIn)
            }
            BX.append(overlayDOM, document.body)
            this.overlay = overlayDOM
        }
    }

    hideOverlay(popup) {
        let remove_overlay = function (){
            BX.remove(this.overlay)
            this.overlay = null
        }

        if (this.overlay) {
            if (popup.animation !== 'instant') {
                this.overlay.classList.add('animation-fade')
                setTimeout(BX.delegate(remove_overlay, this), animationTimeoutOut)
            } else {
                remove_overlay()
            }
        }
    }

    __show(popup) {
        if (popup.focused) {
            popupManagerDev.focusedPopup = popup;
            document.body.style.overflow = "hidden"
        }
        popupManagerDev.open_popups[popup.id] = popup;
    }

    __hide(popup) {
        if (popup.focused && popupManagerDev.overlay !== null) {
            popupManagerDev.focusedPopup = null;
            popupManagerDev.hideOverlay(popup)
            document.body.style.overflow = "visible"
        }
        delete popupManagerDev.open_popups[popup.id]
    }
}

var popupManagerDev = new PopupManagerDev()

//TODO: Зарефакторить класс и поставить блокировку модификации на время анимации
class Popup {
    /***
     * Работа основывается на css файле popup_ctrl.css
     * @param popupDOM
     * @param params {{focused: boolean}, {temp: boolean}}
     * focused - создание оверлея
     * temp - удаление попапа со страницы после деактивации
     */
    constructor(popupDOM, params = {parent: document.body, focused: false, temp: false, animation: 'instant'}) {
        if (!popupDOM.id) {
            throw 'popupDOM is required to have an ID'
        }
        this.id = popupDOM.id
        this.popupDOM = popupDOM
        this.parent = params.parent
        this.temp = !!params.temp;
        this.focused = !!params.focused;
        this.animation = params.animation;
        this.resize_bind = 0;
        if (!BX.hasClass(this.popupDOM, popup_class)) {
            this.popupDOM.classList.add(popup_class)
        }
    }

    animateIn() {
        let end_animation = function(){
            this.popupDOM.classList.remove('animation-' + this.animation)
        }

        if (this.animation !== 'instant') {
            this.popupDOM.classList.add('animation-' + this.animation)
            this.animationQueue = setTimeout(BX.delegate(end_animation, this), animationTimeoutIn)
        }
    }

    animateOut() {
        if (this.animation !== 'instant') {
            this.popupDOM.classList.add('animation-' + this.animation)
        }
    }

    set_popup_events() {
        let dom_list = this.popupDOM.querySelectorAll('.popup_close')
        for (let closingDOM of dom_list) {
            BX.bind(closingDOM, 'click', BX.delegate(this.hide, this))
        }
    }

    calcMaxHeight() {
        let height = isNaN(window.innerHeight) ? window.clientHeight : window.innerHeight;
        return height - 150;
    }

    show() {
        this.popupDOM.classList.add(popup_toggle_class)
        if (this.focused) {
            popupManagerDev.setOverlay(this);
            this.popupDOM.classList.add(popup_fixed_class)
        }
        if (!BX(this.id)) {
            this.popupDOM.style.maxHeight = this.calcMaxHeight() + 'px'
            let parentElement = this.focused ? popupManagerDev.overlay : this.parent
            parentElement.insertBefore(this.popupDOM, parentElement.firstChild)
        }
        BX.bind(window, 'resize', BX.proxy(function(){
            this.popupDOM.style.maxHeight = this.calcMaxHeight() + 'px'
        }, this))
        popupManagerDev.__show(this)
        this.animateIn()
    }

    hide() {
        BX.unbind(window, 'resize', BX.proxy(function(){
            this.popupDOM.style.maxHeight = this.calcMaxHeight() + 'px'
        }, this))
        let close = BX.delegate(function () {
                this.popupDOM.classList.remove(popup_toggle_class)
                if (this.temp) {
                    BX.remove(this.popupDOM)
                }
            }
            , this)
        popupManagerDev.__hide(this)
        this.animateOut()
        setTimeout(BX.delegate(close, this), animationTimeoutIn)
    }
}

class PopupAlert extends Popup{

    constructor(alertDOM, view_timeout){
        super(alertDOM, {parent: document.body, focused: true, temp: true, animation: 'fade'})
        this.view_timeout = view_timeout
    }

    show(){
        super.show()
        setTimeout(BX.delegate(this.hide, this), this.view_timeout)
    }
}

let callNode = BX('call-mobile');
BX.bind(callNode, 'click', function(){
        let callCopy = BX('call').cloneNode(true)
        callCopy.id = 'callPopup'
        let callFormPopup = new Popup(callCopy, {focused:true, temp: true, animation: 'fade'})
        callFormPopup.show()
})