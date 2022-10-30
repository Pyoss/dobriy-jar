let slider = document.getElementById('slider'),
    sliderItems = document.getElementById('slides'),
    prev = document.getElementById('prev'),
    next = document.getElementById('next');

function slide(wrapper, items, prev, next) {
    let posX1 = 0,
        posX2 = 0,
        posInitial,
        posFinal,
        threshold = 100,
        slides = items.getElementsByClassName('slide'),
        slidesLength = slides.length,
        slidesVisible = wrapper.dataset.slides,
        slideSize = items.getElementsByClassName('slide')[0].offsetWidth,
        lastSlide = slides[slidesLength - 1],
        cloneLast = lastSlide.cloneNode(true),
        index = 0,
        allowShift = true,
        endless = (wrapper.dataset.endless == 1);
        console.log(endless)

    if (endless){
        // Clone one or more first slides
        for ( let i = 0; i < slidesVisible; i ++){
            let slide_to_append = slides[i].cloneNode(true);
            items.appendChild(slide_to_append);
        }
        // Clone last slide
        items.insertBefore(cloneLast, slides[0]);
        wrapper.classList.add('loaded');
    }

    // Mouse events
    items.onmousedown = dragStart;

    // Touch events
    items.addEventListener('touchstart', dragStart);
    items.addEventListener('touchend', dragEnd);
    items.addEventListener('touchmove', dragAction);

    // Click events
    prev.addEventListener('click', function () { shiftSlide(-1) });
    next.addEventListener('click', function () { shiftSlide(1) });

    // Transition events
    items.addEventListener('transitionend', checkIndex);

    function dragStart (e) {
        e = e || window.event;
        e.preventDefault();
        posInitial = items.offsetLeft;

        if (e.type === 'touchstart') {
            posX1 = e.touches[0].clientX;
        } else {
            posX1 = e.clientX;
            document.onmouseup = dragEnd;
            document.onmousemove = dragAction;
        }
    }

    function dragAction (e) {
        e = e || window.event;
        if (items.style.left <=0){
            return
        }
        if (e.type === 'touchmove') {
            posX2 = posX1 - e.touches[0].clientX;
            posX1 = e.touches[0].clientX;
        } else {
            posX2 = posX1 - e.clientX;
            posX1 = e.clientX;
        }
        let new_pos = items.offsetLeft - posX2;
        if (!(!endless && new_pos > 0 ||
            !endless && -new_pos > (slidesLength - slidesVisible) * slides[0].offsetWidth)){
            items.style.left = (items.offsetLeft - posX2) + "px";
        }
    }

    function dragEnd (e) {
        posFinal = items.offsetLeft;
        if (posFinal - posInitial < -threshold) {
            shiftSlide(1, 'drag');
        } else if (posFinal - posInitial > threshold) {
            shiftSlide(-1, 'drag');
        } else {
            items.style.left = (posInitial) + "px";
        }

        document.onmouseup = null;
        document.onmousemove = null;
    }

    function shiftSlide(dir, action) {
        items.classList.add('shifting');

        if (allowShift) {
            allowShift = false;

            if (!action) { posInitial = items.offsetLeft; }
            if (!endless){
                //stops if carousel is not endless
                if ( dir === 1 && index === slides.length - slidesVisible || dir === -1 && index === 0){
                    items.style.left = posInitial + "px";
                    return checkIndex();
                }
            }
            if (dir === 1) {
                items.style.left = (posInitial - slideSize) + "px";
                index++;
            } else if (dir === -1) {
                items.style.left = (posInitial + slideSize) + "px";
                index--;
            }
        }
    }

    function checkIndex (){
        items.classList.remove('shifting');
        if (endless){
            if (index === -1) {
                items.style.left = -(slidesLength * slideSize) + "px";
                index = slidesLength - 1;
            }

            if (index === slidesLength) {
                items.style.left = -(1 * slideSize) + "px";
                index = 0;
            }
        }
        allowShift = true;
    }
}

slide(slider, sliderItems, prev, next);