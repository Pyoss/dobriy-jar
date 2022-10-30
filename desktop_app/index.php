<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<div class="block-wrapper">
    <div class="carousel-item">

    </div>
    <div class="carousel-wrapper">
        <div class="carousel">
            <div class="carousel-slide">
            </div>
            <div class="carousel-slide">
            </div>
            <div class="carousel-slide">
            </div>
            <div class="carousel-slide">
            </div>
            <div class="carousel-slide">
            </div>
            <div class="carousel-slide">
            </div>
            <div class="carousel-slide">
            </div>
            <div class="carousel-slide">
            </div>
            <div class="carousel-slide">
            </div>
            <div class="carousel-slide">
            </div>
            <div class="carousel-slide">
            </div>
        </div>
        <div class="carousel-border">
        </div>
    </div>
</div>
<style>
    .carousel-item {
        background-color: pink;
        height: 400px;
        width: 600px;
        margin: auto;
    }

    .carousel-border {
        box-sizing: border-box;
        position: absolute;
        width: 100%;
        top: 0;
        bottom: 0;
        border-left: 30px solid #ffffff82;
        border-right: 30px solid #ffffff82;
    }

    .carousel-wrapper {
        width: 800px;
        margin: auto;
        position: relative;
        overflow: hidden;
    }

    .carousel {
        display: flex;
        position: relative;
        transition: 1s;
        left: 0;
    }

    .carousel-slide {
        box-sizing: border-box;
        border: 1px solid red;
        height: 100px;
        min-width: 100px;
        background-color: green;
    }
</style>
<script>
    function createSlider(){
        return {
            index: 0,
            DOM: document.querySelector('.block-wrapper'),
            slideLength: document.querySelector('.carousel-slide').offsetWidth,
            next: function () {
                this.index += 1
            },
            shiftSlides: function () {
                this.DOM.querySelector('.carousel').style.left = '-' + (this.slideLength * this.index) + "px"
            }
        }
    }

    slider = createSlider()

    slider.next()
    addEventListener()
</script>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>