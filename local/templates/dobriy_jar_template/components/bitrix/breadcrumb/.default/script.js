function BreadcrumbsEditor(){
    this.crumbsDOM = BX("bx-breadcrumb");

    this.length = function (){
        return this.crumbsDOM.children.length - 1
    }

    this.addBreadcrumbElement = function(title, link, limit){
        let index = this.length()
        let lastElement = this.crumbsDOM.lastChild.previousElementSibling;
        if (index > limit){
            lastElement.remove();
        }
        let breadcrumb_element = document.createElement('div');
        breadcrumb_element.classList = "bx-breadcrumb-item"
        breadcrumb_element.id = `bx_breadcrumb_${index}`
        breadcrumb_element.itemprop = "itemListElement"
        breadcrumb_element.itemscope = ""
        breadcrumb_element.itemtype = "http://schema.org/ListItem"
        breadcrumb_element.innerHTML =
            `            <span class="divider">/</span>` +
            `            <a href=${link} title="${title}" itemprop="item" class="dj_link">` +
            `                <span itemprop="name">${title}</span>` +
            '            </a>' +
            `           <meta itemprop="position" content="${index}">`
        BX.insertAfter(breadcrumb_element, this.crumbsDOM.lastChild.previousElementSibling)
    }

}
