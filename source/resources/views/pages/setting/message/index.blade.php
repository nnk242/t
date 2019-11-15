@extends('layouts.app')

@section('content')
    <div class="section no-pad-bot">
        <div class="row">
            <div class="col l3 m4 s12">
                <ul class="collapsible">
                    <li class="collection-item">
                        <a class="collapsible-header" href="{{ route('setting.persistent-menu.index') }}"><i
                                class="material-icons">art_track</i>Persistent menu</a>
                    </li>
                    <li class="collection-item">
                        <a class="collapsible-header"><i class="material-icons">place</i>Second</a>
                    </li>
                    <li class="collection-item">
                        <a class="collapsible-header"><i class="material-icons">whatshot</i>Third</a>
                    </li>
                </ul>
            </div>
            <div class="col l9 m8 s12">
        </div>
    </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // $('.collapsible').collapsible()
            $('.tabs').tabs()
            $('.type').on('change', function () {
                let type = $(this).val()
                let closest_pa = $(this).closest('.closest-pa')
                switch (type) {
                    case 'block':
                        closest_pa.find('.right-submenu').css({'visibility': 'hidden'})
                        closest_pa.find('.title').show()
                        closest_pa.find('.url').hide()
                        closest_pa.find('.prioritize').show()
                        break
                    case 'url':
                        closest_pa.find('.right-submenu').css({'visibility': 'hidden'})
                        closest_pa.find('.title').show()
                        closest_pa.find('.url').show()
                        closest_pa.find('.prioritize').show()
                        break
                    case 'submenu':
                        closest_pa.find('.right-submenu').css({'visibility': 'visible'})
                        closest_pa.find('.title').show()
                        closest_pa.find('.url').hide()
                        closest_pa.find('.prioritize').show()
                        break
                    default:
                        closest_pa.find('.right-submenu').css('visibility', 'hidden')
                        closest_pa.find('.title').hide()
                        closest_pa.find('.url').hide()
                        closest_pa.find('.prioritize').hide()
                        break
                }
            })

            $('.action-menu').on('click', function () {
                $(this).closest('.menu').append(render())
            })
        })
    </script>
    <script>
        function htmlInput(hide_sub = false, id = 0) {
            var btn_sub_menu = !hide_sub ? '<button type="button" class="btn mt-2 mb-2" onclick="btnSelected(this, ' + '\'submenu\'' + ')"\n' +
                '                                            data-selected-button="false" data-type="nested">SubMenu\n' +
                '                                    </button>\n' : ''
            var html = '<input class="form-control" id="value_title" onkeypress="enterShowValue(event, this, ' + id + ')"\n' +
                '                                       placeholder="Nhập giá trị hiển thị">\n' +
                '                                <div class="form-group mt-4 text-center">\n' +
                '                                    <button type="button" class="btn btn-behance mt-2 mb-2" onclick="btnSelected(this, ' + '\'block\'' + ')"\n' +
                '                                            data-selected-button="true" data-type="postback">\n' +
                '                                        Block\n' +
                '                                    </button>\n' +
                '                                    <button type="button" class="btn mt-2 mb-2" onclick="btnSelected(this, ' + '\'url\'' + ')"\n' +
                '                                            data-selected-button="false" data-type="web_url">URL\n' +
                '                                    </button>\n' + btn_sub_menu +
                '                                </div>\n' +
                '<input type="url" class="form-control d-none" id="value_url"\n' +
                'onkeypress="enterShowValue(event, this, ' + id + ')" placeholder="Nhập đường dẫn">'
            return html
        }

        function htmlPostback(message, id) {
            var html = '<div class="form-control" data-id="' + id + '">\n' +
                '       <span class="text-dark font-weight-bold">' + message + '</span>\n' +
                '       <div class="position-relative">\n' +
                '           <a href="##$$$" onclick="remove(\'' + id + '\', \'' + message + '\')" class="mdi mdi-comment-remove mdi-18px text-danger position-absolute custom-remove-item"></a>\n' +
                '       </div>\n' +
                '   </div>'
            return html
        }

        function htmlWebUrl(message, id, url) {
            var html = '<div class="form-control" data-id="' + id + '">\n' +
                '   <span class="text-dark font-weight-bold">' + message + '</span>\n' +
                '   <div class="position-relative w-100 text-center">\n' +
                '       <a href="###" class="text-secondary position-absolute" style="bottom: 0">' + url + '</a>\n' +
                '       <a href="##$$$" onclick="remove(\'' + id + '\', \'' + message + '\')" class="mdi mdi-comment-remove mdi-18px text-danger position-absolute custom-remove-item"></a>' +
                '   </div>\n' +
                '</div>'
            return html
        }

        function htmlNested(message, id, level_menu) {
            var lvl_new = parseInt(level_menu) + 1
            var html =
                '<div class="form-control" data-id="' + id + '">\n' +
                '   <span class="text-dark font-weight-bold">' + message + '</span>\n' +
                '   <div class="position-relative">\n' +
                '       <div class="position-absolute" style="right: 0; bottom: 0;">\n' +
                '           <a href="#changeSubmenu" onclick="changeSubMenu(' + level_menu + ', ' + id + ')" data-show-sub-menu-parent="false">Thay đổi item sub menu<span class="mdi mdi-chevron-right mdi-24px position-relative" style="bottom: -5px"></span></a>\n' +
                '       </div>\n' +
                '       <a href="##$$$" class="mdi mdi-comment-remove mdi-18px text-danger position-absolute custom-remove-item" onclick="remove(\'' + id + '\', \'' + message + '\')"></a>\n' +
                '   </div>\n' +
                '   <div class="w-100 position-absolute custom-element-child d-none" id="key-child-' + level_menu + '-' + id + '" data-show-child-' + level_menu + '="false">\n' +
                '       <div class="form-control">\n' +
                '           <a href="####" onclick="backMenu(' + level_menu + ', ' + id + ')"><span class="mdi mdi-chevron-left"></span>Quay lại</a>\n' +
                '           <span class="text-center">' + message + '</span>\n' +
                '       </div>\n' +
                '       <div id="data-child-' + level_menu + '-' + id + '"></div>\n' +
                '       <button type="button" class="btn btn-gradient-info btn-lg btn-block" onclick="btnAddItem(\'child-' + level_menu + '\', ' + id + ')">\n' +
                '           <i class="mdi mdi-menu float-left"></i>Thêm item\n' +
                '       </button>\n' +
                '       <div class="form-control d-none" id="add-item-child-' + level_menu + '-' + id + '" data-show-add-item="false" data-lvl-menu="' + lvl_new + '" data-id="' + id + '"></div>\n' +
                '   </div>\n' +
                '</div>'
            return html
        }

        function changeSubMenu(child, key) {
            switch (child) {
                case 1:
                    if ($('#key-child-1-' + key).attr('data-show-child-1') == false || $('#key-child-1-' + key).attr('data-show-child-1') == 'false') {
                        $('#key-child-1-' + key).addClass('d-block')
                        $('#key-child-1-' + key).removeClass('d-none')

                        $("[data-show-child-1='" + true + "']").removeClass('d-block')
                        $("[data-show-child-1='" + true + "']").addClass('d-none')

                        $("[data-show-child-1='" + true + "']").attr('data-show-child-1', false)

                        $('#key-child-1-' + key).attr('data-show-child-1', true)
                    }
                    break
                case 2:
                    if ($('#key-child-2-' + key).attr('data-show-child-2') == false || $('#key-child-2-' + key).attr('data-show-child-2') == 'false') {
                        $('#key-child-2-' + key).addClass('d-block')
                        $('#key-child-2-' + key).removeClass('d-none')

                        $("[data-show-child-2='" + true + "']").removeClass('d-block')
                        $("[data-show-child-2='" + true + "']").addClass('d-none')

                        $("[data-show-child-2='" + true + "']").attr('data-show-child-2', false)

                        $('#key-child-2-' + key).attr('data-show-child-2', true)
                    }
                    break
            }
        }

        function backMenu(child, key) {
            $('#key-child-' + child + '-' + key).removeClass('d-block')
            $('#key-child-' + child + '-' + key).addClass('d-none')
            if (child == 1) {
                $('#key-child-' + child + '-' + key).attr('data-show-child-1', false)
            }
            $('#key-child-2-' + key).attr('data-show-child-2', false)
        }

        function btnAddItem(val, id) {
            switch (val) {
                case 'parent':
                    if ($('#add-item').attr('data-show-add-item') == false || $('#add-item').attr('data-show-add-item') == 'false') {
                        $("[data-show-add-item='" + true + "']").empty()
                        $("[data-show-add-item='" + true + "']").addClass('d-none')
                        $("[data-show-add-item='" + true + "']").removeClass('d-block')
                        $("[data-show-add-item='" + true + "']").attr('data-show-add-item', false)

                        $('#add-item').attr('data-show-add-item', true)
                        $('#add-item').removeClass('d-none')
                        $('#add-item').addClass('d-block')
                        $('#add-item').append(htmlInput())
                    } else {
                        $('#add-item').attr('data-show-add-item', false)
                        $('#add-item').addClass('d-none')
                        $('#add-item').removeClass('d-block')
                        $('#add-item').empty()
                    }
                    break
                case 'child-1':
                    if ($('#add-item-child-1-' + id).attr('data-show-add-item') == false || $('#add-item-child-1-' + id).attr('data-show-add-item') == 'false') {
                        $("[data-show-add-item='" + true + "']").empty()
                        $("[data-show-add-item='" + true + "']").addClass('d-none')
                        $("[data-show-add-item='" + true + "']").removeClass('d-block')
                        $("[data-show-add-item='" + true + "']").attr('data-show-add-item', false)

                        $('#add-item-child-1-' + id).attr('data-show-add-item', true)
                        $('#add-item-child-1-' + id).removeClass('d-none')
                        $('#add-item-child-1-' + id).addClass('d-block')
                        $('#add-item-child-1-' + id).append(htmlInput(0, id))
                    } else {
                        $('#add-item-child-1-' + id).attr('data-show-add-item', false)
                        $('#add-item-child-1-' + id).addClass('d-none')
                        $('#add-item-child-1-' + id).removeClass('d-block')
                        $('#add-item-child-1-' + id).empty()
                    }
                    break
                case 'child-2':
                    if ($('#add-item-child-2-' + id).attr('data-show-add-item') == false || $('#add-item-child-2-' + id).attr('data-show-add-item') == 'false') {
                        $("[data-show-add-item='" + true + "']").empty()
                        $("[data-show-add-item='" + true + "']").addClass('d-none')
                        $("[data-show-add-item='" + true + "']").removeClass('d-block')
                        $("[data-show-add-item='" + true + "']").attr('data-show-add-item', false)

                        $('#add-item-child-2-' + id).attr('data-show-add-item', true)
                        $('#add-item-child-2-' + id).removeClass('d-none')
                        $('#add-item-child-2-' + id).addClass('d-block')
                        $('#add-item-child-2-' + id).append(htmlInput(2, id))
                    } else {
                        $('#add-item-child-2-' + id).attr('data-show-add-item', false)
                        $('#add-item-child-2-' + id).addClass('d-none')
                        $('#add-item-child-2-' + id).removeClass('d-block')
                        $('#add-item-child-2-' + id).empty()
                    }
                    break

            }
        }

        function remove(id, title) {
            iziToast.show({
                timeout: 10000,
                transitionIn: 'flipInX',
                transitionOut: 'flipOutX',
                displayMode: 1,
                theme: 'dark',
                icon: 'icon-person',
                title: title,
                message: 'Bạn có chắc chắn muốn xóa?',
                position: 'center', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
                progressBarColor: 'rgb(0, 255, 184)',
                buttons: [
                    ['<button>Xác nhận</button>', function (instance, toast) {
                        $.ajax({
                            method: 'POST',
                            url: 'persistent-menu/' + id,
                            data: {
                                id,
                                "_method": 'DELETE',
                                "_token": '{{csrf_token()}}',
                            },
                            success: function (response) {
                                console.log(response)
                                $("[data-id='" + id + "']").remove()
                                notifySuccess('Xóa đổi thành công!')
                            },
                            error: function (error) {
                                notifyError('Có lỗi xảy ra với server!')
                            }
                        })
                        instance.hide({
                            transitionOut: 'fadeOutUp',
                            onClosing: function (instance, toast, closedBy) {
                            }
                        }, toast, 'buttonName')
                    }, true], // true to focus
                    ['<button>Hủy</button>', function (instance, toast) {
                        instance.hide({
                            transitionOut: 'fadeOutUp',
                            onClosing: function (instance, toast, closedBy) {
                            }
                        }, toast, 'buttonName')
                    }]
                ],
                onOpening: function (instance, toast) {
                },
                onClosing: function (instance, toast, closedBy) {
                }
            })
            console.log(id)
        }

        function btnSelected(_this, val) {
            switch (val) {
                case 'block':
                    if (_this.getAttribute('data-selected-button') == false || _this.getAttribute('data-selected-button') == 'false') {
                        $("[data-selected-button='" + true + "']").removeClass('btn-behance')
                        $("[data-selected-button='" + true + "']").attr('data-selected-button', false)
                        _this.setAttribute('data-selected-button', true)
                        $('#value_url').addClass('d-none')
                        $('#value_url').removeClass('d-block')
                        _this.classList.add('btn-behance')
                    }
                    // console.log(_this.getAttribute('data-selected-button'))
                    break
                case 'url':
                    if (_this.getAttribute('data-selected-button') == false || _this.getAttribute('data-selected-button') == 'false') {
                        $("[data-selected-button='" + true + "']").removeClass('btn-behance')
                        $("[data-selected-button='" + true + "']").attr('data-selected-button', false)
                        _this.setAttribute('data-selected-button', true)
                        $('#value_url').addClass('d-block')
                        $('#value_url').removeClass('d-none')
                        _this.classList.add('btn-behance')
                    }
                    break
                case 'submenu':
                    $("[data-selected-button='" + true + "']").removeClass('btn-behance')
                    $("[data-selected-button='" + true + "']").attr('data-selected-button', false)
                    _this.setAttribute('data-selected-button', true)
                    $('#value_url').addClass('d-none')
                    $('#value_url').removeClass('d-block')
                    _this.classList.add('btn-behance')
                    break
                default:
            }
            console.log(val)
        }

        function enterShowValue(event, _this, id) {
            if (event.keyCode == 13) {
                var type = $("[data-selected-button='" + true + "']").attr('data-type')

                var title = $('#value_title').val()

                var level_menu = $("[data-show-add-item='" + true + "']").attr('data-lvl-menu')

                var ok = false

                var data = {}

                if (title) {
                    if (type == 'web_url') {
                        var url = $('#value_url').val()
                        if (url) {
                            data = {
                                type,
                                title,
                                url,
                                level_menu,
                                _token: '{{csrf_token()}}'
                            }
                            ok = true
                        } else {
                            notifyError('Bạn chưa nhập đường dẫn')
                        }
                    } else {
                        data = {
                            type,
                            title,
                            level_menu,
                            _token: '{{csrf_token()}}'
                        }
                        ok = true
                    }
                } else {
                    notifyError('Bạn chưa nhập Giá trị hiển thị')
                }
                if (parseInt(level_menu) != 1) {
                    data = Object.assign(data, {persistent_id: id})
                }
                console.log(ok)
                if (ok) {
                    $.ajax({
                        method: 'POST',
                        data, success: function (response) {
                            if (response['status'] == true) {
                                var type = response['data']['type']
                                var parent_id = response['data']['persistent_id']
                                var level_menu = response['data']['level_menu']
                                var message = response['data']['title']
                                var id = response['data']['id']
                                switch (type) {
                                    case "postback":
                                        if (parseInt(level_menu) == 1) {
                                            $('#data-parent').append(htmlPostback(message, id))
                                            // $('#key-child-1-' + parent_id).append()
                                        }
                                        if (parseInt(level_menu) == 2) {
                                            $("#data-child-1-" + parent_id).append(htmlPostback(message, id))
                                        }

                                        if (parseInt(level_menu) == 3) {
                                            $("#data-child-2-" + parent_id).append(htmlPostback(message, id))
                                        }

                                        break
                                    case 'web_url':
                                        var url = response['data']['url']
                                        if (parseInt(level_menu) == 1) {
                                            $('#data-parent').append(htmlWebUrl(message, id, url))
                                            // $('#key-child-1-' + parent_id).append()
                                        }
                                        if (parseInt(level_menu) == 2) {
                                            $("#data-child-1-" + parent_id).append(htmlWebUrl(message, id, url))
                                        }

                                        if (parseInt(level_menu) == 3) {
                                            $("#data-child-2-" + parent_id).append(htmlWebUrl(message, id, url))
                                        }
                                        break
                                    case 'nested':
                                        if (parseInt(level_menu) == 1) {
                                            $('#data-parent').append(htmlNested(message, id, level_menu))
                                        }
                                        if (parseInt(level_menu) == 2) {
                                            $("#data-child-1-" + parent_id).append(htmlNested(message, id, level_menu))
                                        }
                                        break
                                    default:
                                }

                                $('#value_title').val('')
                                $('#value_url').val('')
                                notifySuccess('Lưu thay đổi thành công!')
                            } else {
                                notifyError('Persistent menu ở mức level 1 là 3 còn lại là 5')
                            }

                        }, error: function () {
                            notifyError('Có lỗi xảy ra với server')
                        }
                    })
                }
            }
        }

        $(document).ready(function () {
            $('#select_show').addClass('d-none')
            $('#select_show').removeClass('d-block')
            $('#selected_type').change(function () {
                switch ($(this).val()) {
                    case '0':
                        $('#select_show').addClass('d-none')
                        $('#select_show').removeClass('d-block')
                        break
                    default:
                        $('#select_show').addClass('d-block')
                        $('#select_show').removeClass('d-none')
                }
            })
        })

    </script>
@endsection
