

(function() {
	'use strict';
	window.JCCatalogSectionComponent = function(params) {
		this.formPosting = false;
		this.siteId = params.siteId || '';
		this.ajaxId = params.ajaxId || '';
		this.template = params.template || '';
		this.componentPath = params.componentPath || '';
		this.parameters = params.parameters || '';

		if (params.navParams)
		{
			this.navParams = {
				NavNum: params.navParams.NavNum || 1,
				NavPageNomer: parseInt(params.navParams.NavPageNomer) || 1,
				NavPageCount: parseInt(params.navParams.NavPageCount) || 1
			};
		}

		this.bigData = params.bigData || {enabled: false};
		this.container = document.querySelector('[data-entity="' + params.container + '"]');
		this.showMoreButton = null;
		this.showMoreButtonMessage = null;

	};

	window.JCCatalogSectionComponent.prototype =
	{
		checkButton: function()
		{
			if (this.showMoreButton)
			{
				if (this.navParams.NavPageNomer == this.navParams.NavPageCount)
				{
					BX.remove(this.showMoreButton);
				}
				else
				{
					this.container.appendChild(this.showMoreButton);
				}
			}
		},

		processPagination: function(paginationHtml)
		{
			if (!paginationHtml)
				return;

			var pagination = document.querySelectorAll('[data-pagination-num="' + this.navParams.NavNum + '"]');
			for (var k in pagination)
			{
				if (pagination.hasOwnProperty(k))
				{
					pagination[k].innerHTML = paginationHtml;
				}
			}
		},

	};
})();

BX(function () {
	const column_view = document.getElementById('column-view'),
		row_view = document.getElementById('row-view'),
		price_asc = document.getElementById('sort-price-ascend'),
		price_desc = document.getElementById('sort-price-descend'),
		bx_pagination_doms = document.querySelectorAll('.bx-pagination');

	let nav_switch_func = function (event) {
		const page_action = event.target.dataset.pageNav;
		let page_length = event.target.parentNode.parentNode.childElementCount - 2
		switch (page_action) {
			case 'prev':
				if (parseInt(smartFilter.url_params.page) > 0) {
					smartFilter.url_params.page = parseInt(smartFilter.url_params.page) - 1
				}
				break
			case 'next':
				if (parseInt(smartFilter.url_params.page) < page_length) {
					smartFilter.url_params.page = parseInt(smartFilter.url_params.page) + 1
				}
				break
			default:
				smartFilter.url_params.page = page_action
		}
		smartFilter.click(false);
		document.querySelector('.catalog--wrapper').scrollIntoView({behavior: 'smooth'})
	}

	for (let bx_pag_dom of bx_pagination_doms) {
		BX.bindDelegate(
			bx_pag_dom,
			'click',
			{
				tagName: 'LI',
				className: 'active'
			},
			nav_switch_func
		)
	}

	column_view.addEventListener('click',
		function () {
			document.querySelector('.catalog-products-container').classList.remove('row-view');
			document.querySelector('.catalog-products-container').classList.add('column-view');
			row_view.classList.remove('active');
			column_view.classList.add('active');
			BX.setCookie('VIEW_MODE', 'column-view', {expires: 86400, path: '/'})
		})

	row_view.addEventListener('click',
		function () {
			document.querySelector('.catalog-products-container').classList.remove('column-view');
			document.querySelector('.catalog-products-container').classList.add('row-view');
			column_view.classList.remove('active');
			row_view.classList.add('active');
			BX.setCookie('VIEW_MODE', 'row-view', {expires: 86400, path: '/'})
		})

	price_asc.addEventListener('click',
		function () {
			if (price_asc.classList.contains('active')) {
				price_asc.classList.remove('active');
				smartFilter.url_params.price_sort = null
			} else {
				price_asc.classList.add('active');
				price_desc.classList.remove('active');
				smartFilter.url_params.price_sort = 'asc'
			}
			smartFilter.click(false);
		})

	price_desc.addEventListener('click',
		function () {
			if (price_desc.classList.contains('active')) {
				price_desc.classList.remove('active');
				smartFilter.url_params.price_sort = null
			} else {
				price_desc.classList.add('active');
				price_asc.classList.remove('active');
				smartFilter.url_params.price_sort = 'desc'
			}
			smartFilter.click(false);
		})

})
function formatNumber(number){
	return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ")
}
