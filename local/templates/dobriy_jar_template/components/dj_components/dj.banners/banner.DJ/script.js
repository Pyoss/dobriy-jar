

class SliderDev{
    constructor(slider_id, slide_n, ratio = 0.3, mob_ratio = 0.5, endless = true) {
        this.ratio = ratio
        this.mob_ratio = mob_ratio
        this.DOM = BX(slider_id)
        console.log(this.DOM)
        this.wrapper = this.DOM.querySelector('.wrapper')
        this.container = this.DOM.querySelector('.slides')
        this.bullets = this.DOM.querySelector('.bullets-wrapper')
        this.mobile_size = 980
        this.cur_index = 0
        this.slide_v = slide_n
        this.slide_n = this.container.children.length
        this.interval = 7
        this.pressed = false
        this.endless = endless

        this.startX = 0;
        this.startOffset = 0;
        this.dragX = 0;

        this.resize()
        BX.bind(window, 'resize', BX.proxy(this.resize, this))

        if (this.slide_n === 1){
            this.bindClicks()
            return
        }
        if (this.endless){
            this.endless_offset = 0
            this.cloneNodes();
        }
        this.updateBullets()
        this.bindEvents()
        this.scrollTimeout = null
        this.queueScroll(this.interval)
    }

    queueScroll(timeout){
        this.scrollTimeout = setTimeout(this.next.bind(this), timeout * 1000)
    }

    threshold(){
        return this.container.children[0].offsetWidth / 10
    }

    cloneNodes(){
        let first_slides = []
        let last_slides = []
        for (let i = 0; i <= this.slide_v && i < this.container.children.length; i++){
            first_slides.unshift(this.container.children[i].cloneNode(true))
            last_slides.unshift(this.container.children[this.slide_n - 1 - i].cloneNode(true))
        }

        for (let i = 0; i <= this.slide_v && i < this.container.children.length; i++){
            BX.insertBefore(last_slides.pop(), this.container.firstChild)
            BX.insertAfter(first_slides.pop(), this.container.lastChild)
            this.cur_index ++
            this.endless_offset ++
        }
        this.shiftSlidesInst()
    }

    resize() {
        this.slide_width = this.wrapper.offsetWidth / this.slide_v * 100 / 100
        if (this.slide_width > 1460) this.slide_width = 1460
        for (let slide of this.container.children){
            let slide_ratio = 0.3
            if (window.innerWidth < this.mobile_size){
                slide.querySelector('.slide-background').src = slide.querySelector('.slide-background').dataset.mobileSrc
                slide_ratio = this.mob_ratio
            } else {
                slide.querySelector('.slide-background').src = slide.querySelector('.slide-background').dataset.desktopSrc
                slide_ratio = this.ratio
            }
            slide.style.width = this.slide_width + 'px'
            slide.style.height = this.slide_width * slide_ratio + 'px'
        }
        this.shiftSlidesInst()
    }

    mousedown(){
       return BX.proxy( function(e){
           if (this.pressed || this.blocked){
               return
           }
           this.press(e)
           e.myX = e.pageX || e.touches[0].clientX
           this.startX = e.myX - this.container.offsetLeft;
           this.startOffset = this.container.offsetLeft;
       }, this)
    }

    mouseleave(){
        return BX.proxy( function(e){
        if(!this.pressed || this.blocked){
            return
        }
        this.free(e)
        this.update(e)
    }, this)
    }

    mousemove(){
        return BX.proxy( function(e){
            if(!this.pressed || this.blocked){
                return
            }
            e.preventDefault();
            e.myX = e.pageX || e.touches[0].clientX
            if (e.myX - this.startX < 0 && e.myX - this.startX > - (this.container.children.length - 1) * this.container.children[0].offsetWidth){
                this.container.style.left = `${e.myX - this.startX}px`
            }
        }, this)
    }

    bindClicks() {

        this.wrapper.addEventListener("click", function (){

            window.location = this.container.children[this.cur_index].dataset.href
        }.bind(this))
    }

    bindEvents() {
        this.wrapper.addEventListener("mousedown", this.mousedown())
        this.wrapper.addEventListener("touchstart", this.mousedown(), {passive: true})
        this.wrapper.addEventListener("mouseleave", this.mouseleave())
        this.wrapper.addEventListener("mouseup", this.mouseleave())
        this.wrapper.addEventListener("touchend", this.mouseleave())
        this.wrapper.addEventListener("mousemove",this.mousemove())
        this.wrapper.addEventListener("touchmove",this.mousemove())
        if (this.bullets){
            for (let bullet of this.bullets.children){
                bullet.addEventListener('click', function (){
                    clearTimeout(this.scrollTimeout)
                    this.cur_index = parseInt(bullet.dataset.index)  - 1 + this.endless_offset
                    this.checkBoundaries()
                    this.updateBullets()
                    this.shiftSlides()
                    this.queueScroll(this.interval)

                }.bind(this))
            }
        }

    }

    free(e){
        BX.toggleClass(this.container, 'dragged');
        this.pressed = false
        this.queueScroll(this.interval)
    }

    press(){
        BX.toggleClass(this.container, 'dragged');
        this.pressed = true
        clearTimeout(this.scrollTimeout)
    }

    next() {
        clearTimeout(this.scrollTimeout)
        this.cur_index += 1
        this.checkBoundaries()
        this.updateBullets()
        this.shiftSlides()
        this.queueScroll(this.interval)
    }

    prev() {
        clearTimeout(this.scrollTimeout)
        this.cur_index -= 1
        this.checkBoundaries()
        this.updateBullets()
        this.shiftSlides()
        this.queueScroll(this.interval)
    }

    update(e) {
        let impulse = this.startOffset - this.container.offsetLeft;
        let index_mod
        if (Math.abs(impulse) > this.threshold()){
            if (impulse > 0){
                index_mod = Math.ceil(impulse / this.container.children[0].offsetWidth)
            } else {
                index_mod = Math.floor(impulse / this.container.children[0].offsetWidth)
            }
            this.cur_index += index_mod
        } else {
            window.location = this.container.children[this.cur_index].dataset.href
        }
        this.checkBoundaries()
        this.updateBullets()
        this.shiftSlides()
    }

    checkBoundaries(){
        if (!this.endless){
            if (this.cur_index < 0){
                this.cur_index = 0
            } else if (this.cur_index > this.container.children.length - 1){
                this.cur_index = this.container.children.length - 1
            }
        }
    }

    updateBullets(){
        if (this.bullets){
            console.log(this.container.children[this.cur_index])
            let current_index = parseInt(this.container.children[this.cur_index].dataset.index)
            for (let i = 1; i <= this.slide_n; i ++){
                if (i === current_index){
                    this.bullets.children[i-1].classList.add('active')
                } else {
                    this.bullets.children[i-1].classList.remove('active')
                }
            }
        }
    }

    shiftSlides(){
        let slider_move = setInterval(function (){
            let requiredOffset = - this.cur_index * this.slide_width
            let interval_px = requiredOffset < this.container.offsetLeft ? -100 : 100;
            if (!this.pressed && this.container.offsetLeft !== requiredOffset) {
                let distance = this.container.offsetLeft - requiredOffset
                this.container.style.left = (Math.abs(distance) < Math.abs(interval_px) ?
                    this.container.offsetLeft - distance : this.container.offsetLeft + interval_px ) + 'px'
            } else {
                clearInterval(slider_move)
                this.adjustSlides()
            }
        }.bind(this), 10)
    }

    shiftSlidesInst(){
        this.blocked = true
        this.container.classList.add('dragged')
        this.container.style.left = - this.cur_index * this.slide_width + 'px'
        console.log('Номер текущего слайда - ' + this.cur_index)
        setTimeout(function(){
            this.container.classList.remove('dragged')
            this.blocked = false
        }.bind(this), 50)
    }

    adjustSlides(){
        if (this.cur_index < this.endless_offset){
            this.cur_index += this.slide_n
        } else if (this.cur_index > this.slide_n + this.endless_offset){
            this.cur_index -= this.slide_n
        }
        this.shiftSlidesInst()
    }
}

