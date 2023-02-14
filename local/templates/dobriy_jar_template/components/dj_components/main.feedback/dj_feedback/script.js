
class FeedbackForm{
    constructor (template_params){
        this.template_params = template_params;
        this.form_submitted = false;
    }

    send_mail(e){
        e = e || window.event;
        e.preventDefault();
        if (this.form_submitted){
            return
        }
        let values_array = [];
        for (let i = 0; i < e.target.length; i ++ ){
            if (e.target[i].nodeName === 'INPUT'){
                values_array[e.target[i].name] = e.target[i].value;
            }
        }
        this.form_submitted = true
        BX.ajax.post(
            '/ajax/callback_form.php',
            values_array,
            BX.delegate(this.receive_form, this)
        )
        let form = e.path[0]
        form.reset();
        console.log(e.path[1].id)
        if (e.path[1].id === 'mfeedback-alert'){
            popupManagerDev.hidePopup('mfeedback-alert')
        }
    }

    receive_form(response){
        let json = JSON.parse(response)
        if (json['mail_sent']){
            let alertDOM = BX.create(
                'div',
                {
                    props:{
                        className: 'popup-alert',
                        id: 'form-message',
                        innerText: `Спасибо, ${json['name']}!
                                    Мы перезвоним Вам на номер ${json['phone']}
                                    в ближайшее время.`
                    }
                }
            )
            let alert = new PopupAlert(alertDOM, 4000)
            alert.show()
        }
    }
}
