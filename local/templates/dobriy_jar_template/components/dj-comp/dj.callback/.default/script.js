$(document).ready(()=>{
    $('#callback-button').on('click',
        ()=>{
        let phone = $('#callback-input').val()
        if (checkPhone(phone)){

            BX.ajax.post(
                '/ajax/callback_form.php',
                {'phone': phone},
                receive_form
            )
        } else {
            let alertDOM = BX.create(
                'div',
                {
                    props:{
                        className: 'popup-alert',
                        id: 'form-message',
                        innerText: `Введите телефон в правильном формате.`
                    }
                }
            )
            let alert = new PopupAlert(alertDOM, 4000)
            alert.show()
        }
        }
    )
})


function receive_form(response){
    let json = JSON.parse(response)
    if (json['mail_sent']){
        let alertDOM = BX.create(
            'div',
            {
                props:{
                    className: 'popup-alert',
                    id: 'form-message',
                    innerText: `Спасибо!
                                Мы перезвоним Вам на номер ${json['phone']}
                                в ближайшее время.`
                }
            }
        )
        let alert = new PopupAlert(alertDOM, 4000)
        alert.show()
    }
}


BX(function(){
    let callbuttonDOMs = document.querySelectorAll('.callback_button');
    for (let callbackDOM of callbuttonDOMs){
        BX.bind(callbackDOM, 'click', function (){
            document.getElementById('callback').scrollIntoView()
        })
    }
})