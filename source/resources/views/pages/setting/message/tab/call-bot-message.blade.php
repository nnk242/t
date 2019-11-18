<form class="container" action="{{ route('setting.store-message-head') }}" method="POST">
    @csrf
    <div class="card-panel">
        <div class="row">
            <div class="col s12">
                <div class="input-field">
                    <h4>Ví dụ:</h4>
                    <p>User gửi tin nhắn đến với cú pháp cố định: <span class="green-text">ABC</span></p>
                    <p>User gửi tin nhắn đến với cú pháp linh hoạt hơn: <span
                            class="green-text">!'{value}'</span></p>
                </div>
                <div class="input-field">
                    <input placeholder="Nhập tin nhắn nhận từ người dùng" type="text" class="validate"
                           name="text">
                </div>
                <div class="center-align">
                    <button class="btn">Gửi</button>
                </div>
            </div>
        </div>
    </div>
</form>
