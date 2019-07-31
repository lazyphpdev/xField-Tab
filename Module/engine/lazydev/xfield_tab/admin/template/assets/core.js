'use strict';

class Admin {
    constructor(name) {
        this.mod = name;

        $('select.chosen').chosen({allow_single_deselect: true, no_results_text: 'Ничего не найдено', width: '300px'});

        $('.br-toggle').click(function () {
            let toogleId = $(this).data('id');
            if ($(this).hasClass('on')) {
                $(this).removeClass('on');
                $('#' + toogleId).attr('checked', false);
            } else {
                $(this).addClass('on');
                $('#' + toogleId).attr('checked', 'checked');
            }
        });
    }

    alert(info) {
        if (info.error) {
            Growl.error({
                title: 'Ошибка',
                text: info.text
            });
        } else {
            Growl.info({
                title: 'Успех',
                text: info.text
            });
        }
    }

    ajaxSend(data, action, callback) {
        $.post('engine/lazydev/' + this.mod + '/ajax.php', {
            data: data,
            action: action,
            dle_hash: dle_login_hash
        }, function (info) {
            info = jQuery.parseJSON(info);
            if (info.text) {
                coreAdmin.alert(info);
            }
            if (callback) {
                return callback(info);
            }
        });
        if (!callback) {
            return false;
        }
    }

}