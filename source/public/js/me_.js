function check(_this) {
    let check = parseInt(_this.attr('check'))
    if (check === 0) {
        $('.pick').prop('checked', true)
        $('#pick-all').attr('check', 1)
        $('#render-fo').append(render())
    } else {
        $('.pick').prop('checked', false)
        $('#pick-all').attr('check', 0)
        $('#render-fo').empty()
    }
}

$(document).ready(function () {
    $('.pick').prop('checked', false)
    $('#pick-all').attr('check', 0)
})

$(document).on('click', '#pick-all', function () {
    let check = parseInt($(this).attr('check'))
    if (check === 0) {
        $('.pick').prop('checked', true)
        $('#pick-all').attr('check', 1)
    } else {
        $('.pick').prop('checked', false)
        $('#pick-all').attr('check', 0)
    }
})
