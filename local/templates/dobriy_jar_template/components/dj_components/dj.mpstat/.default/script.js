BX(
    function (){
        let response_data;

        BX.bind(BX("test_button"), 'click', function (){
            let mplace = BX("mplc-choice").value;
            let date = BX("start").value;
            let ids = BX("Text1").value.replaceAll('\n', ',');
            let ajax_url = `?ajax=Y&mplc=${mplace}&ids=${ids}&date=${date}`
            console.log(ajax_url);
            BX.ajax({
                url:ajax_url,
                method: "GET",
                onsuccess: function (response){
                    let ajaxHTML = new DOMParser().parseFromString(response, "text/html")
                    response_data = (JSON.parse(ajaxHTML.querySelector("json").innerText))
                    let xlsx_array = [["Название", "Ссылка", "Поставщик",
                        "Цена начала периода", "Цена конца периода",
                        "Комментарии начала периода", "Комментарии конца периода",
                        "Рейтинг начала периода", "Рейтинг конца периода",
                        "Продажи", "Выручка"
                    ]]
                    for(let ITEM of response_data){

                        let SKU = ITEM.SKU
                        if (SKU.code === 500) {
                            continue
                        }
                        let saleData = ITEM.saleData
                        xlsx_array.push([SKU.item.first_name !== undefined ? SKU.item.first_name : SKU.item.full_name,
                            SKU.item.link, SKU.item.brand,
                        saleData.final_price.earliest, saleData.final_price.latest,
                            saleData.comments.earliest,saleData.comments.latest,
                            saleData.rating.earliest,saleData.rating.latest,
                            saleData.sales.value, saleData.income.value])

                    }
                    create_xlsx(xlsx_array)
                }
            })
        })

    }
)

function create_xlsx(data_arrays){
    let wb = XLSX.utils.book_new();
    wb.Props = {
        Title: "SheetJS Tutorial",
        Subject: "Test",
        Author: "Red Stapler",
    };
    wb.SheetNames.push("Sheet");
    wb.Sheets["Sheet"] = XLSX.utils.aoa_to_sheet(data_arrays);
    let type = 'xlsx'
    let wbout = XLSX.write(wb, {bookType:'xlsx', bookSST:true, type: 'binary'});
    let fname = 'test.' + type;
    try {
        saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), fname);
    } catch(e) {
        if(typeof console != 'undefined') console.log(e, wbout);
    }


    function s2ab(s) {
        if(typeof ArrayBuffer !== 'undefined') {
            let buf = new ArrayBuffer(s.length);
            let view = new Uint8Array(buf);
            for (let i=0; i!=s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;
        } else {
            let buf = new Array(s.length);
            for (let i=0; i!=s.length; ++i) buf[i] = s.charCodeAt(i) & 0xFF;
            return buf;
        }
    }
}