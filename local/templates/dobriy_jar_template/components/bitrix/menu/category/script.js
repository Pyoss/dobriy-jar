let mobile_sections = document.getElementsByClassName('mobile-section')
let expanded_mob_sections = [];
let selected_section = false;
let menuFadeinTimeout;
let ImageHoverTimeout = null;

function setMenuListeners(){
    let section_names = document.getElementsByClassName('section-menu--section');
    // SET UP LISTENERS FOR EVERY SECTION IN THE TOP MENU (FOR DESKTOP)
    for(let section_name of section_names){
        section_name.addEventListener('mouseenter', function (){
            clearTimeout(menuFadeinTimeout);
            if (!selected_section){
                selected_section = section_name;
                menuFadeinTimeout = setTimeout(showHeaderSection, 500);
            } else {
                hideHeaderMenu();
                selected_section = section_name;
                showHeaderSection(true);
            }
            document.querySelector('.section-menu').addEventListener('mouseleave', hideHeaderMenu);
        })
    }
    // LISTENERS FOR IMAGE HOVERING
    let sections = document.querySelectorAll('.image-hover')

    let image_wrappers = document.querySelectorAll('.menu-hover-image')
    for (let sct of sections){
        sct.addEventListener('mouseenter', function (){
            clearTimeout(ImageHoverTimeout);
            ImageHoverTimeout = setTimeout(function (){
                for (let image_wrapper of image_wrappers){
                    let image_url = new URL(image_wrapper.src)
                    if (image_url.pathname !== sct.dataset.src) {
                        setTimeout(function () {
                            image_wrapper.classList.add('out')
                        }, 100)
                        setTimeout(function () {
                            image_wrapper.src = sct.dataset.src
                        }, 200)
                        image_wrapper.onload = function () {
                            image_wrapper.classList.remove('out')
                        }
                    }
                }
            }, 500);
        })
    }

    // SET UP LISTENERS FOR EVERY SECTION IN THE TOP MENU (FOR MOBILE)
    let mobile_catalog = new MobileCatalogController();
    let menu_sections = document.querySelectorAll('.mobile-section'), i;

    for (i = 0; i < menu_sections.length; ++i) {
        if (menu_sections[i].classList.contains('parent')){
            menu_sections[i].querySelector('.mobile-section__name').addEventListener('click',
                mobile_catalog.navForward.bind(mobile_catalog),
                {passive: false})
        } else {
            menu_sections[i].addEventListener('click', function (event){
                location.href = event.currentTarget.dataset.href;
            });
        }
    }

    let catalog_link = document.querySelector('.open-catalog')

    catalog_link.addEventListener('click',
        function (){
            mobile_catalog.show()
            mobile_catalog.navForward(false, '#msection-0')
        })

    let menu_sections_back = document.querySelectorAll('.mobile-nav-back'), j;
    for (j = 0; j < menu_sections_back.length; ++j) {
        menu_sections_back[j].addEventListener('click', mobile_catalog.navBack.bind(mobile_catalog), {passive: false});
    }

    let swiper = new Swiper();
    swiper.swipe_left_funcs.push(mobile_catalog.navBack.bind(mobile_catalog));

    // BIND FUNCTIONS BY ID TO OPEN AND CLOSE MOBILE MENU
    popupManager.registerPopup(
        mobile_catalog.show.bind(mobile_catalog),
        mobile_catalog.show.bind(mobile_catalog),
        'header-menu'
        )
    ;

    // SET UP LISTENER FOR BURGER IN THE TOP MENU (FOR MOBILE)
    document.getElementById('mobile-catalog-open').addEventListener('click',
        (event)=>{
        popupManager.togglePopup.bind(popupManager)(event)
        }
    ,
        {passive:false})
    mobile_catalog.overlay.addEventListener('click',
        (event) => {
        popupManager.closePopup.bind(popupManager)(event)

        }, {passive:false})


}
function showHeaderSection(instant){
    if (selected_section){
        let section_container = selected_section.querySelector('.subsection-container');
        if (section_container){
            if(instant){
                section_container.style.display = 'flex';
            } else {
                let op = 0.1;  // initial opacity
                section_container.style.opacity = op;
                section_container.style.display = 'flex';
                let timer = setInterval(function () {
                    if (op >= 1){
                        clearInterval(timer);
                    }
                    section_container.style.opacity = op;
                    section_container.style.filter = 'alpha(opacity=' + op * 100 + ")";
                    op += op * 0.3;
                }, 10);
            }
        }
    }
}
function hideHeaderMenu(event){
    if (selected_section){
        let section_container = selected_section.querySelector('.subsection-container');
        if (section_container){
            section_container.style.display = 'none';
        }
    }
    selected_section = false;
}

class MobileCatalogController{
    constructor() {
        this.currentTranslate = 0
        this.wrapper = document.querySelector('.mobile-catalog--wrapper');
        this.overlay = document.querySelector('.mobile-catalog--overlay');
        this.catalogDom = document.querySelector('.mobile-menu');
        this.blockedInteraction = false;
        this.hidden = true;
        this.prevDoms = [];
    }

    show(instant=true) {
        if (this.hidden){
            document.querySelector('body').classList.add('no-scroll');
            document.documentElement.classList.add('no-scroll');
            this.wrapper.classList.add('open');
            this.overlay.classList.add('show');
            this.overlay.classList.add('transparent');
            this.hidden = false;
            this.currentTranslate = 0
            this.currentDom = this.catalogDom;
            this.catalogDom.style.transform = 'translateX(0)';
        } else {
            if(instant){
                document.querySelector('body').classList.remove('no-scroll');
                document.documentElement.classList.remove('no-scroll');
            }
            else {
                setTimeout(function(){
                    document.querySelector('body').classList.remove('no-scroll');
                    document.documentElement.classList.remove('no-scroll');
                }, 500)
            }
            this.wrapper.classList.remove('open')
            this.overlay.classList.remove('show');
            this.overlay.classList.remove('transparent');
            this.currentDom.classList.remove('expanded');
            while(this.prevDoms.length){
                let dm = this.prevDoms.pop();
                dm.classList.remove('open');
                dm.classList.remove('expanded');
            }
            this.hidden = true;
        }

    }

    navForward(event, target){
        target = target ? target : '#' + event.currentTarget.id.replace('name-','')
        this.blockedInteraction = true;
        this.currentTranslate += 100;
        this.catalogDom.style.transform = 'translateX(-' + this.currentTranslate + '%)';
        this.prevDoms.push(this.currentDom);
        this.currentDom = this.currentDom.querySelector(target);
        this.currentDom.classList.add('expanded');
        this.wrapper.scrollTop = 0;
    }

    navBack(event){
        if (!(this.prevDoms.length)){
            return
        }
        this.blockedInteraction = true;
        this.currentTranslate -= 100;

        this.catalogDom.style.transform = 'translateX(-' + this.currentTranslate + '%)';
        this.currentDom.classList.remove('expanded');
        this.currentDom = this.prevDoms.pop();
        this.wrapper.scrollTop = 0;
    }

    hideSwipe(event){
        if (!this.prevDoms.length && !this.hidden){
            this.show(false);
        }
    }
}

setMenuListeners();
