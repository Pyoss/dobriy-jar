
// класс для управления кнопками переключения предложения, работает как в каталоге, так и на странице элемента
class SkuSetter{
    constructor() {
        // при инициации класс расставляет все необходимые листенеры
        // ДО ИНИЦИАЦИИ КНОПКИ ПЕРЕКЛЮЧЕНИЯ ПРЕДЛОЖЕНИЙ РАБОТАТЬ НЕ БУДЕТ
        this.setOffersListener();
    }

    /**
     * Sets listeners on offer buttons, initiates on class construction
     */
    setOffersListener(){
        BX.bindDelegate(document.querySelector("main"),
            'click',
            {className: 'sku-prop'},
            BX.delegate(this.changeOffer, this)
        )
    }

    /**
     * Change offer DOM to display new chosen parameters
     * @param event
     */
    changeOffer(event){
        if (event.target.classList.contains('inactive')){
            return;
        }
        // Собираем данные с текущей кнопки
        const product_id = event.target.dataset.productId; // ID товара
        const new_prop_code = event.target.dataset.propcode; // ID свойства
        const new_prop_id = event.target.dataset.propid; // ID значения свойства

        // TODO: Можно попробовать избавиться от лишних полей. Если нет - удалить запись

        // Находим текущий элемент
        const productDOM = document.querySelector(`div[data-product-id='${product_id}']`);
        let offerController = new Offer(productDOM)
        offerController.updateCurrentKeys(new_prop_code ,new_prop_id)
        offerController.deactivateOffers()
        offerController.updateHtml()
    }

}

class Offer{
    constructor(productDOM) {
        this.productDOM = productDOM
        this.offerMap = JSON.parse(productDOM.querySelector('json').innerHTML);
        this.offerSku = this.productDOM.querySelector('.offer-sku');
        this.currentKeys = {}
    }

    getByKey(keysObj){
        let key_string = ''
        for (const [key, value] of Object.entries(keysObj)) {
            key_string += key + '_' + value + ':'
        }
        return this.offerMap[key_string]
    }

    updateCurrentKeys(new_prop_code, new_prop_id){

        // Находим раздел предложений текущего элемента

        for (const skuRow of this.offerSku.children){
            let currentElement = skuRow.getElementsByClassName("current")[0];
            // Записываем в массив arKey текущие выделенные параметры

            if (skuRow.dataset.propcode === new_prop_code){
                currentElement.classList.remove('current');
                const newCurrentElement = skuRow.querySelector('span[data-propid="' + new_prop_id +'"]');
                newCurrentElement.classList.add('current')
                this.currentKeys[new_prop_code] = new_prop_id
            } else {
                this.currentKeys[currentElement.dataset.propcode] = currentElement.dataset.propid
            }
        }
    }

    /**
     * deactivates all sku buttons with invalid json codes
     * @param event - click
     * @param newOffer - found offer
     * @param productDOM - DOM of the found product
     */
    deactivateOffers(){

        for (const skuRow of this.offerSku.children){
            for(const skuProp of skuRow.querySelectorAll('span')){
                let this_code = {... this.currentKeys}
                this_code[skuProp.dataset.propcode] = skuProp.dataset.propid
                if (this.getByKey(this_code) === undefined){
                    skuProp.classList.add('inactive')
                } else {
                    skuProp.classList.remove('inactive')
                }
            }
        }
    }

    updateHtml(){
        /* ------------------------------------------- Установка новых цен ----------------------------------------- */
        let offerData = this.getByKey(this.currentKeys)
        const discount = offerData['RATIO_DISCOUNT'];
        const detail_picture = offerData['DETAIL_PICTURE']
        const preview_picture = offerData['PREVIEW_PICTURE']

        let name = this.productDOM.getElementsByClassName('product-name')[0]
        name.innerHTML = offerData['NAME'];
        name.id = 'name-' + offerData['ID'];
        let article = this.productDOM.querySelector('.article').lastChild
        article.innerHTML = offerData['ARTNUMBER'];
        article.id = 'article-' + offerData['ID'];
        let price = this.productDOM.getElementsByClassName('price')[0]
        price.innerHTML = offerData['PRINT_PRICE'];
        price.id = 'price-' + offerData['ID'];
        let type = this.productDOM.getElementsByClassName('product-type')[0]
        type.id = 'type-' + offerData['ID'];
        if (discount !== 0){
            this.productDOM.getElementsByClassName('base-price')[0].innerHTML = offerData['BASE_PRICE'];
        }

        /* Если скидка */
        if (!(discount == 0)){
            this.productDOM.getElementsByClassName('base-price')[0].classList.remove('hidden');
        } else {
            this.productDOM.getElementsByClassName('base-price')[0].classList.add('hidden');
        }
        this.productDOM.querySelector('.basket-add').dataset.productId = offerData['ID'];

        /* --------------------------------- Заменяем или удаляем картинки, связанные с предложением ----------------------------- */
        const images = offerData['GALLERY'];


        let first_image_src = images[0];
        const gallery_container = document.getElementById('gallery-image-container');

        // ------------ Изменяем состав галлереи и добавляем новые картинки при наличии предложений ----------------
        // TODO: Найти решение что делать если у одного из предложений отсутствуют доп картинки
        if (gallery_container){
            while(gallery_container.children[0] !== undefined
            && gallery_container.children[0].querySelector('img').dataset.source === 'offer'){
                gallery_container.removeChild(gallery_container.children[0])
            }
            images.reverse()
            for (let offer_image_src of images){
                let new_gallery_itemDOM = document.createElement('div')
                let new_image_dom = document.createElement('img')
                new_image_dom.classList.add('loading')
                new_image_dom.onload = function (){
                    new_image_dom.classList.remove('loading')
                }
                new_image_dom.src = offer_image_src['SRC']
                new_image_dom.dataset.src = offer_image_src['SRC']
                new_image_dom.dataset.source = 'offer'
                new_gallery_itemDOM.className = 'gallery-preview'
                new_gallery_itemDOM.insertBefore(new_image_dom, null)
                gallery_container.insertBefore(new_gallery_itemDOM, gallery_container.children[0])
                new_gallery_itemDOM.addEventListener('click', function () {
                    changeMainImage(new_gallery_itemDOM)
                });
            }
        }

        let main_img = this.productDOM.getElementsByClassName('product-image')[0];
        if (main_img === undefined){
            main_img = this.productDOM.getElementsByClassName('product-element--image')[0];
        }
        main_img.classList.add('loading')
        main_img.src = first_image_src['SRC'];
        main_img.onload = function (){
            main_img.classList.remove('loading')
        }

        // ------------ Замена кнопки покупки и артикула ----------------
        const buttonDOM = this.productDOM.querySelector('.basket-add')
        buttonDOM.dataset.productId = offerData['ID']
    }
}

var sku_setter = new SkuSetter();