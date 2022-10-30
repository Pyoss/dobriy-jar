
class Slider {

    constructor(slider_id){
        this.wrapper = document.getElementById(slider_id)
        this.items = this.wrapper.querySelector('.slides')
        this.slides = this.items.getElementsByClassName('slide-wrapper')
        this.prev = this.wrapper.querySelector('.prev')
        this.next = this.wrapper.querySelector('.next')
        this.endless = this.wrapper.dataset.endless === '1'
        this.base_width = parseInt(this.wrapper.style.maxWidth);
        this.base_font = parseInt(this.wrapper.style.fontSize);
        this.base_height = parseInt(this.slides[0].style.height);
        this.index = 0

        this.min_slider_width = this.wrapper.dataset.minWidth
        this.threshold = 50
        this.length = this.slides.length
        this.maxSlidesVisible = parseInt(this.wrapper.dataset.slides)
        this.currentSlidesVisible = this.maxSlidesVisible
        this.posX1 = 0
        this.posX2 = 0
        this.allowShift = true
        this.dir = 0

        if (this.endless){
            // Clone last child
            this.lastSlide = this.slides[this.length - 1]
            this.cloneLast = this.lastSlide.cloneNode(true)

            // Clone one or more first slides
            for ( let i = 0; i < this.maxSlidesVisible; i ++){
                let slide_to_append = this.slides[i].cloneNode(true);
                this.items.appendChild(slide_to_append);
            }
            // Clone last slide
            this.items.insertBefore(this.cloneLast, this.slides[0]);
        }

        // Mouse events
        this.items.onmousedown = this.dragStart.bind(this);

        // Touch events
        this.items.addEventListener('touchstart', this.dragStart.bind(this), {passive: true});
        this.items.addEventListener('touchend', this.dragEnd.bind(this), {passive: true});
        this.items.addEventListener('touchmove', this.dragAction.bind(this), {passive: true});

        // Click events
        if (this.prev != null){
            this.prev.addEventListener('click', this.shiftSlideBack.bind(this));
            this.next.addEventListener('click', this.shiftSlideForward.bind(this));
        }

        this.wrapper.classList.add('loaded');

        // Resize events
        window.addEventListener('resize', this.timeResizing.bind(this));
        this.resize();
        if (this.wrapper.dataset.interval !== "0"){
            setInterval(this.shiftSlideForward.bind(this), this.wrapper.dataset.interval * 1000)
        }
    }

    block(){
        this.allowShift = false
    }

    unblock(){
        this.allowShift = true
    }

    timeResizing(){
        clearTimeout(this.resizeTimeout)
        this.resizeTimeout = setTimeout(this.resize.bind(this), 200)
    }

    resize(){
        this.block()
        this.wrapper.classList.remove('loaded')
        let slidesVisible = this.maxSlidesVisible
        while (this.wrapper.offsetWidth / slidesVisible < this.min_slider_width && slidesVisible > 1){
            slidesVisible -= 1;
        }
        this.currentSlidesVisible = slidesVisible

        let percent = this.wrapper.offsetWidth/this.base_width * 100 * this.maxSlidesVisible / slidesVisible
        percent = Math.floor(percent) / 100;

        let slide_width = this.wrapper.offsetWidth / slidesVisible
        let offset = 0

        if (this.endless){
            offset = slide_width
        }
        this.index = 0
        this.items.style.left = (-offset) + 'px'
        for (let slide of this.slides){
            slide.style.width = slide_width + 'px'
            slide.style.height = this.base_height * percent + 'px'
        }
        this.wrapper.style.fontSize = this.base_font * percent + 'px'
        this.update()
    }

    shiftSlideForward(){
        if (this.allowShift){
            this.block()
            this.dir = 1;
            this.shiftSlide();
        }
    }

    shiftSlideBack(){
        if (this.allowShift){
            this.block()
            this.dir = -1;
            this.shiftSlide();
        }
    }

    dragStart (e) {
        if (this.allowShift) {
            this.block()
            e = e || window.event;
            this.posInitial = this.items.offsetLeft;

            if (e.type === 'touchstart') {
                this.posX1 = e.touches[0].clientX;
            } else {
                this.posX1 = e.clientX;
                document.onmouseup = this.dragEnd.bind(this);
                document.onmousemove = this.dragAction.bind(this);
            }
        }
    }

    dragAction (e) {
        e = e || window.event;
        if (this.items.style.left <=0){
            return
        }
        if (e.type === 'touchmove') {
            this.posX2 = this.posX1 - e.touches[0].clientX;
            this.posX1 = e.touches[0].clientX;
        } else {
            this.posX2 = this.posX1 - e.clientX;
            this.posX1 = e.clientX;
        }
        let new_pos = this.items.offsetLeft - this.posX2;
        if (!(!this.endless && new_pos > 0 ||
            !this.endless && -new_pos > (this.length - this.currentSlidesVisible) * this.slides[0].offsetWidth)){
            this.items.style.left = (this.items.offsetLeft - this.posX2) + "px";
        }
    }

    dragEnd (e) {
        this.posFinal = this.items.offsetLeft;
        let posDelta = this.posFinal - this.posInitial;
        if (posDelta < -this.threshold) {
            this.dir = 1;
            this.shiftSlide('drag');
        } else if (posDelta > this.threshold) {
            this.dir = -1;
            this.shiftSlide('drag');
        } else {
            this.items.style.left = (this.posInitial) + "px";
            this.checkIndex()
            if (posDelta === 0){
                let path = e.path || (e.composedPath && e.composedPath());
                let href = path.find(x => x.className === 'slide-wrapper').dataset.href;
                if (href !== undefined){
                    window.location = href;
                }
            }
        }
        document.onmouseup = null;
        document.onmousemove = null;
    }

    shiftSlide(action) {
        this.indexChecking = setTimeout(function (){
            this.allowShift = true
            this.checkIndex();
        }.bind(this), 500);
        this.items.classList.add('shifting');

        if (!action) { this.posInitial = this.items.offsetLeft; }
        if (!this.endless){
            //stops if carousel is not endless
            if ( this.dir === 1 && this.index === this.length - this.currentSlidesVisible
                || this.dir === -1 && this.index === 0){
                this.items.style.left = this.posInitial + "px";
                return;
            }
        }
        if (this.dir === 1) {
            this.items.style.left = (this.posInitial - this.slideSize) + "px";
            this.index++;
        } else if (this.dir === -1) {
            this.items.style.left = (this.posInitial + this.slideSize) + "px";
            this.index--;
        }
    }

    checkIndex (){
        clearTimeout(this.indexChecking)
        this.items.classList.remove('shifting');
        if (this.endless){
            if (this.index === -1) {
                this.items.style.left = -(this.length * this.slideSize) + "px";
                this.index = this.length - 1;
            }

            if (this.index === this.length) {
                this.items.style.left = -(1 * this.slideSize) + "px";
                this.index = 0;
            }
        }
        this.allowShift = true
    }

    update(){
        this.slideSize = this.slides[0].offsetWidth
        this.unblock()
    }
}
