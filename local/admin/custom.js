BX(function(){
    BX.bindDelegate(
        BX('tr_PROPERTY_57'),
        'change',
        {tagName: 'input'},
        changeComp)

    BX.bind(
        document.getElementById('update-guid'),
        'click',
        function (event){
            event.preventDefault()
            event.target.innerText = 'Подождите...'
            let element_id = event.target.dataset.id
            let iblock_id = event.target.dataset.iblockId
            let article = event.target.dataset.article
            BX.ajax.get('/local/classes/integration_1C/rewriteguid.php?ID=' +
                element_id + '&IBLOCK_ID=' + iblock_id+ '&ARTICLE=' + article,
                function (result){
                    location.reload()
                }

            )
        })
    }

)

// установка значений комплектации
let changeComp = function(event){
    let parentRow = event.target.parentNode;
    let parentRowNum = parentRow.querySelector('template').dataset.row
    let item = BX("COMP[n" + parentRowNum + "]").value
    let comment = BX("COMMENT[n" + parentRowNum + "]").value || "empty"
    let group = BX("GROUP[n" + parentRowNum + "]").value
    if (item){
        BX("PROP[57][n" + parentRowNum + "]").value = JSON.stringify({item: item,
            group: group,
            comment: comment})
    } else {
        BX("PROP[57][n" + parentRowNum + "]").value = ''
    }
}