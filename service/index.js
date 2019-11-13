const app = require('express')()
const http = require('http').createServer(app)
const io = require('socket.io')(http)

app.get('/', function (req, res) {
    res.send('App')
});

http.listen(3000, function () {
    console.log('listening on *:3000')
})

io.on('connection', function (socket) {
    console.log('a user connected')
    socket.on('data', function (msg) {
        const authorization = socket.request.headers.authorization
        serverHttpIO.emit('message_1086408651532297', msg)
        console.log(msg)
        if (key == authorization) {
            console.log(msg['page_id'])
            if (msg['webhook'] == 'messager') {
                serverHttpIO.emit('message_' + msg['page_id'], msg)
            }
            // serverHttpIO.emit('message_1086408651532297', msg)

            // io.emit('message_' + msg['page_id'], msg)
        } else {
            // serverHttpIO.emit('message_1086408651532297', msg)

            console.log('authorization is error')
        }
    })
    console.log(socket)
})
