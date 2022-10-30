
function JSSearchFilter()
{
    this.filter_url = null;
    this.cacheKey = '';
    this.cache = [];
    this.url_params = {};
}
JSSearchFilter.prototype.click = function()
{
    this.reload();
}
JSSearchFilter.prototype.reload = function()
{
    this.cacheKey = '|';
    this.constructUrl();
    document.getElementById('catalog-loading-overlay').style.display = 'block';
    if (this.cache[this.cacheKey])
    {
        this.postHandler(this.cache[this.cacheKey], true);
    }
    else
    {
        this.postHandler(this.filter_url)
    }
};

JSSearchFilter.prototype.postHandler = function (url, fromCache)
{
    let httpRequest = new XMLHttpRequest();
    if (!httpRequest) {
        alert('Giving up :( Cannot create an XMLHTTP instance');
        return false;
    }
    httpRequest.onreadystatechange = workResponse;
    httpRequest.open('GET', url);
    httpRequest.send();
    function workResponse() {
        if (httpRequest.readyState === XMLHttpRequest.DONE) {
            if (httpRequest.status === 200) {
                const parser = new DOMParser();
                const filtered_content = parser.parseFromString(httpRequest.responseText,
                    "text/html").querySelector('.catalog-products-container');

                if (filtered_content.querySelector('.product-element') === null){
                    filtered_content.innerHTML = 'К сожалению по выбранному фильтру найти товары не удалось.'
                }
                document.querySelector('.catalog-products-container').innerHTML = filtered_content.innerHTML;
                document.getElementById('catalog-loading-overlay').style.display = 'none'

            } else {
                alert('There was a problem with the request.');
            }
        }
    }

    if (!fromCache && this.cacheKey !== '')
    {
        this.cache[this.cacheKey] = url;
    }
    this.cacheKey = '';
};

JSSearchFilter.prototype.constructUrl = function (){
    this.filter_url = new URL(window.location.href)
    if (this.url_params.price_sort){
        this.filter_url.searchParams.append('PRICE_SORT', this.url_params.price_sort)
        this.cacheKey += '&' + 'sort_price:' + this.url_params.price_sort;
    }
    let section_sort = document.querySelector('.found-section.active')
    if (section_sort && section_sort.dataset.sectionId){
        this.filter_url.searchParams.append('SECTION_ID', section_sort.dataset.sectionId)
        this.cacheKey += '&' + 'section_id:' + section_sort.dataset.sectionId;
    }
    this.filter_url.searchParams.append('reload', '1')
}

function setSectionSearchListeners(){
    const section_names = document.getElementsByClassName('found-section');
    for (let section_name of section_names){
        section_name.addEventListener('click', sortBySection, {passive:true})
    }
}

sortBySection = function(event){
    const section_names = document.getElementsByClassName('found-section');
    for (let section_name of section_names){
        if (section_name !== event.target && section_name !== event.target.parentNode){
            section_name.classList.remove('active');
        } else {
            section_name.classList.add('active');
        }
    }
    smartFilter.reload();
}

function removeParam(key, sourceURL) {
    let rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}

setSectionSearchListeners()