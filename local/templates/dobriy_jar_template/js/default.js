function inViewport( element ){

    // Get the elements position relative to the viewport

    var bb = element.getBoundingClientRect();

    // Check if the element is outside the viewport
    // Then invert the returned value because you want to know the opposite

    return !(bb.top > innerHeight || bb.bottom < 0);

}

var lazyElements = document.querySelectorAll( '.lazyload');

// Listen for the scroll event

document.addEventListener( 'scroll', event => {

    // Check the viewport status

    for (let lazy_element of lazyElements){
        if( inViewport( lazy_element )){
            lazy_element.style.background = 'red';
        }

    }

})