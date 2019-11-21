$(document).ready(function () {
    $(".dropdown-trigger").dropdown()
    $('.tap-target').tapTarget()
    $('.sidenav').sidenav()
    $('select').formSelect()
    $('a[data-toggle="tab"]').click(function (e) {
        if (history.replaceState) {
            history.replaceState(null, null, '#' + $(e.target).attr('href').substr(1))
        } else {
            location.hash = '#' + $(e.target).attr('href').substr(1)
        }

        $(window).trigger('hashchange')
    })
})
