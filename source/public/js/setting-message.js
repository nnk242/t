$(document).ready(function () {
    $('.content_type').on('change', function () {
        switch ($(this).val()) {
            case 'text':
                $(this).closest('.parent-content').find('.content-text').removeClass('display-none')
                break
            case 'user_phone_number':
                $(this).closest('.parent-content').find('.content-text').addClass('display-none')
                break
            case 'user_email':
                $(this).closest('.parent-content').find('.content-text').addClass('display-none')
                break
        }
    })

    $('.number').on('change', function () {
        var num = parseInt($(this).val())
        var div = $('.content-quick-replies > div')
        for (var i = 0; i < div.length; i++) {
            if (i <= num) {
                ($('.parent-content:nth-child(' + i + ')')).removeClass('display-none')
            } else {
                ($('.parent-content:nth-child(' + i + ')')).addClass('display-none')
            }
        }
    })

    $('textarea.materialize-textarea, input.validate').characterCounter()
    $('.modal').modal()
    $('.tabs').tabs()
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    })

    $('.delete-call-bot-message').on('click', function () {
        let id = $(this).attr('data-id')
        let text = $(this).attr('data-text')
        $('#modal-body-notify').empty()
        $('#modal-body-notify').append('Bạn chắc chắn muốn xóa <span class="red-text">' + text + '</span>?')
        $('#call-bot-message-modal').find('form').attr('action', '/setting/message-head' + '/' + id)
    })

    $('.delete-text-message').on('click', function () {
        let id = $(this).attr('data-id')
        let text = $(this).attr('data-text')
        $('#modal-body-notify-text-message').empty()
        $('#modal-body-notify-text-message').append('Bạn chắc chắn muốn xóa <span class="red-text">' + text + '</span>?')
        $('#text-messages').find('form').attr('action', '/setting/message/' + id)
    })

    $('.type_notify').on('change', function () {
        switch ($(this).val()) {
            case 'timer':
                $('.run_').removeClass('display-none')
                break
            default:
                $('.run_').addClass('display-none')
                break
        }
    })

    $('.button_type').on('change', function () {
        let button_ = $(this).closest('.button_')
        switch ($(this).val()) {
            case 'postback':
                button_.find('.button_title').removeClass('display-none')
                button_.find('.web_url').addClass('display-none')
                button_.find('.button_phone').addClass('display-none')
                break
            case 'web_url':
                button_.find('.button_title').removeClass('display-none')
                button_.find('.web_url').removeClass('display-none')
                button_.find('.button_phone').addClass('display-none')
                break
            default:
                button_.find('.button_title').removeClass('display-none')
                button_.find('.web_url').addClass('display-none')
                button_.find('.button_phone').removeClass('display-none')
                break
        }
    })

    $('.template_type').on('change', function () {
        switch ($(this).val()) {
            case'generic':
                $('.element-generic').removeClass('display-none')
                $('.element-button').addClass('display-none')
                $('.element-media').addClass('display-none')
                break
            case 'button':
                $('.element-generic').addClass('display-none')
                $('.element-button').removeClass('display-none')
                $('.element-media').addClass('display-none')
                break
            case 'media':
                $('.element-generic').addClass('display-none')
                $('.element-button').addClass('display-none')
                $('.element-media').removeClass('display-none')
                break

        }
    })

    $('#type-head').on('change', function () {
        switch ($(this).val()) {
            case 'event':
                $('#run-event').removeClass('display-none')
                break
            default:
                $('#run-event').addClass('display-none')
                break
        }
    })

    let attr_search = $('input.search-success, input.search-error-begin-time-active, input.search-error-end-time-active, input.search-error-time-open, input.search-error-giftcode')

    $(attr_search).devbridgeAutocomplete({
        serviceUrl: "/message/search/data",
        type: 'GET',
        onSelect: function (suggestion) {
            if ($(this).attr('data-type') === 'search-success') {
                $('#input-success-id').attr('value', suggestion.data)
            }
            if ($(this).attr('data-type') === 'search-error-giftcode') {
                $('#input-error-gift').attr('value', suggestion.data)
            }
            if ($(this).attr('data-type') === 'search-error-time-open') {
                $('#input-error-time-open').attr('value', suggestion.data)
            }
            if ($(this).attr('data-type') === 'search-error-begin-time-active') {
                $('#input-error-begin-time-active').attr('value', suggestion.data)
            }
            if ($(this).attr('data-type') === 'search-error-end-time-active') {
                $('#input-error-end-time-active').attr('value', suggestion.data)
            }
        },
        showNoSuggestionNotice: true,
        noSuggestionNotice: 'Không tìm thấy dữ liệu nào...',
    })

    $('input.search-data-message-head').devbridgeAutocomplete({
        serviceUrl: "/setting/message-head",
        type: 'GET',
        onSelect: function (suggestion) {
            if ($(this).attr('data-type') === 'search-data-message-head') {
                $('#bot_message_head_id_text_messages').attr('value', suggestion.data)
            }
            if ($(this).attr('data-type') === 'bot_message_head_id_attachment') {
                $('#bot_message_head_id_attachment').attr('value', suggestion.data)
            }
            if ($(this).attr('data-type') === 'bot_message_head_id_template') {
                $('#bot_message_head_id_template').attr('value', suggestion.data)
            }
            if ($(this).attr('data-type') === 'bot_message_head_id_quick_reply') {
                $('#bot_message_head_id_quick_reply').attr('value', suggestion.data)
            }
        },
        showNoSuggestionNotice: true,
        noSuggestionNotice: 'Không tìm thấy dữ liệu nào...',
    })
})
