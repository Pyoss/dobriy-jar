
function setSearchListeners(){
    let search_button = document.querySelector('.search-icon.mobile-button');
    popupManager.registerPopup(searchOpen,
        searchOpen,
        'search')
    search_button.addEventListener('click',
        popupManager.togglePopup.bind(popupManager), {passive: true})
    }

function searchOpen(){
    let mobile_header = document.querySelector('header .header-center');
    if (mobile_header.classList.contains('searching')){
        mobile_header.classList.remove('searching');
    } else {
        mobile_header.classList.add('searching');
    }
}

BX(
    setSearchListeners
)
