<form class="container" method="POST">
    @csrf
    <input name="type_message" value="message_templates" hidden>
    <input id="bot_message_head_id_template" name="bot_message_head_id" hidden>
    <div class="card-panel">
        <div class="row">
            <div class="col s12">
                <div class="input-field">
                    <h4>Message Templates</h4>
                </div>
                <div class="input-field col s12">
                    <i class="material-icons prefix">search</i>
                    <input type="text" class="autocomplete search-data-message-head"
                           data-type="bot_message_head_id_template">
                    <label>Tìm kiếm tin nhắn <span class="amber-text">BOT</span></label>
                </div>
                <div class="input-field col s12">
                    <select name="template_type" class="template_type">
                        <option value="generic" disabled selected>Chọn kiểu tin nhắn...</option>
                        <option value="generic">Generic</option>
                        <option value="button">Button</option>
                        <option value="media">Media</option>
                    </select>
                    <label>Kiểu tin nhắn</label>
                </div>
                <div class="element-generic display-none">
                    <div class="input-field col s12">
                        <label>Nhập title</label>
                        <input type="text" class="validate" placeholder="Nhập title" name="title">
                    </div>
                    <div class="input-field col s12">
                        <label>Nhập link ảnh</label>
                        <input type="url" class="validate" placeholder="Nhập link ảnh" name="image_url">
                    </div>
                    <div class="input-field col s12">
                        <label>Nhập subtitle</label>
                        <input type="text" class="validate" placeholder="Nhập subtitle" name="subtitle">
                    </div>
                    <div class="input-field col s12">
                        <label>Nhập group message template</label>
                        <input type="number" class="validate" placeholder="Nhập group message template"
                               name="group">
                    </div>
                    <div class="input-field col s12">
                        <h5>Nội dung mặc định khi ấn vào ảnh</h5>
                    </div>
                    <div class="input-field col s12">
                        <label>Redirect khi chạm vào action</label>
                        <input type="url" class="validate" placeholder="Redirect khi chạm vào action"
                               name="default_action_url">
                    </div>
                    <div class="input-field col s12">
                        <select name="messenger_webview_height_ratio">
                            <option value="tall">Màn hình nhỏ</option>
                            <option value="full" selected>Full màn hình</option>
                        </select>
                        <label>Kiểu màn hình trên messager</label>
                    </div>
                </div>
                <div class="element-button display-none">
                    <div class="input-field col s12">
                        <label>Redirect khi chạm vào action</label>
                        <textarea class="validate materialize-textarea" data-length="630"
                                  placeholder="Nội dung gửi người dùng" name="text"></textarea>
                    </div>
                </div>
                <div class="element-media display-none">
                    <div class="input-field col s12 row">
                        <h5>Lấy URL Facebook</h5>
                        <p>Để lấy URL Facebook của hình ảnh hoặc video, hãy làm như sau:</p>
                        <ol>
                            <li>Nhấp vào hình nhỏ của ảnh hoặc video để mở chế độ xem toàn kích thước.</li>
                            <li>Sao chép URL từ thanh địa chỉ trên trình duyệt của bạn.</li>
                        </ol>
                        <h5>URL Facebook sẽ ở định dạng cơ bản sau:</h5>
                        <table class="responsive-table">
                            <thead>
                            <tr style="width: 100px">
                                <th>Loại phương tiện</th>
                                <th>Nguồn phương tiện</th>
                                <th>Định dạng URL</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Video</td>
                                <td>Trang Facebook</td>
                                <td class="orange-text">https://business.facebook.com/&lt;PAGE_NAME&gt;/videos/&lt;NUMERIC_ID&gt;</td>
                            </tr>
                            <tr>
                                <td>Video</td>
                                <td>Tài khoản Facebook</td>
                                <td class="orange-text">https://www.facebook.com/&lt;USERNAME&gt;/videos/&lt;NUMERIC_ID&gt;/
                                </td>
                            </tr>
                            <tr>
                                <td>Hình ảnh</td>
                                <td>Trang Facebook</td>
                                <td class="orange-text">https://business.facebook.com/&lt;PAGE_NAME&gt;/photos/&lt;NUMERIC_ID&gt;</td>
                            </tr>
                            <tr>
                                <td>Hình ảnh</td>
                                <td>Tài khoản Facebook</td>
                                <td class="orange-text">https://www.facebook.com/photo.php?fbid=&lt;NUMERIC_ID&gt;</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="input-field col s12">
                        <label>URL media</label>
                        <input type="url" class="validate" placeholder="URL media" name="url">
                    </div>
                    <div class="input-field col s12">
                        <select name="media_type">
                            <option value="image" selected>Hình ảnh</option>
                            <option value="video">Video</option>
                        </select>
                        <label>Kiểu media</label>
                    </div>
                </div>
                <div class="button">
                    <div class="row">
                        <div class="col s4 button_">
                            <div class="input-field">
                                <h4 class="center">Button 1</h4>
                            </div>
                            <div class="input-field col s12">
                                <select name="button_type[]" class="button_type">
                                    <option value="postback" disabled selected>Chọn loại button...</option>
                                    <option value="phone_number">Phone number</option>
                                    <option value="web_url">Web url</option>
                                    <option value="postback">Postback</option>
                                </select>
                                <label>Chọn loại button</label>
                            </div>
                            <div class="input-field col s12 button_title display-none">
                                <label>Nhập title của button</label>
                                <input type="text" class="validate" placeholder="Nhập title của button"
                                       name="button_title[]">
                            </div>
                            <div class="input-field col s12 web_url display-none">
                                <label>Nhập url</label>
                                <input type="url" class="validate" placeholder="Nhập link"
                                       name="button_url[]">
                            </div>
                            <div class="input-field col s12 button_phone display-none">
                                <label>Số điện thoại</label>
                                <input type="text" class="validate" placeholder="Nhập số điện thoại"
                                       name="payload[]">
                            </div>
                        </div>
                        <div class="col s4 button_">
                            <div class="input-field">
                                <h4 class="center">Button 2</h4>
                            </div>
                            <div class="input-field col s12">
                                <select name="button_type[]" class="button_type">
                                    <option value="postback" disabled selected>Chọn loại button...</option>
                                    <option value="phone_number">Phone number</option>
                                    <option value="web_url">Web url</option>
                                    <option value="postback">Postback</option>
                                </select>
                                <label>Chọn loại button</label>
                            </div>
                            <div class="input-field col s12 button_title display-none">
                                <label>Nhập title của button</label>
                                <input type="text" class="validate" placeholder="Nhập title của button"
                                       name="button_title[]">
                            </div>
                            <div class="input-field col s12 web_url display-none">
                                <label>Nhập url</label>
                                <input type="url" class="validate" placeholder="Nhập link"
                                       name="button_url[]">
                            </div>
                            <div class="input-field col s12 button_phone display-none">
                                <label>Số điện thoại</label>
                                <input type="text" class="validate" placeholder="Nhập số điện thoại"
                                       name="payload[]">
                            </div>
                        </div>
                        <div class="col s4 button_">
                            <div class="input-field">
                                <h4 class="center">Button 3</h4>
                            </div>
                            <div class="input-field col s12">
                                <select name="button_type[]" class="button_type">
                                    <option value="postback" disabled selected>Chọn loại button...</option>
                                    <option value="phone_number">Phone number</option>
                                    <option value="web_url">Web url</option>
                                    <option value="postback">Postback</option>
                                </select>
                                <label>Chọn loại button</label>
                            </div>
                            <div class="input-field col s12 button_title display-none">
                                <label>Nhập title của button</label>
                                <input type="text" class="validate" placeholder="Nhập title của button"
                                       name="button_title[]">
                            </div>
                            <div class="input-field col s12 web_url display-none">
                                <label>Nhập url</label>
                                <input type="url" class="validate" placeholder="Nhập link"
                                       name="button_url[]">
                            </div>
                            <div class="input-field col s12 button_phone display-none">
                                <label>Số điện thoại</label>
                                <input type="text" class="validate" placeholder="Nhập số điện thoại"
                                       name="payload[]">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-field col s12">
                    <select name="type_notify" class="type_notify">
                        <option value="normal" disabled selected>Chọn loại tin nhắn chạy</option>
                        <option value="normal">Normal</option>
                        <option value="timer">Timer</option>
                    </select>
                    <label>Loại tin nhắn</label>
                </div>
                <div class="run_ display-none">
                    @include('components.common.form-date')
                </div>
                <div class="center-align">
                    <button class="btn">Gửi</button>
                </div>
            </div>
        </div>
    </div>
</form>
