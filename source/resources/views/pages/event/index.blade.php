@extends('layouts.app')

@section('css')
    <style>
        #chart {
            max-width: 650px;
            margin: 35px auto;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row col s12 input-field card-panel">
            <div id="chart"></div>
            <p class="center">
                Lượng tin nhắn trong 7 ngày gần nhất
            </p>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script>
        var data = [{{$data[$data['date'][6]]}}, {{$data[$data['date'][5]]}}, {{$data[$data['date'][4]]}},
            {{$data[$data['date'][3]]}}, {{$data[$data['date'][2]]}}, {{$data[$data['date'][1]]}}, {{$data[$data['date'][0]]}}]
        var options = {
            chart: {
                type: 'bar'
            },
            series: [{
                name: 'Số lượng',
                data
            }],
            xaxis: {
                categories: ['{{$data['date'][6]}}', '{{$data['date'][5]}}', '{{$data['date'][4]}}',
                    '{{$data['date'][3]}}', '{{$data['date'][2]}}', '{{$data['date'][1]}}', '{{$data['date'][0]}}']
            }
        }

        var chart = new ApexCharts(document.querySelector("#chart"), options);

        chart.render();

        $(function () {
            window.setInterval(function () {
                let today = '{{$data['date'][0]}}'
                let date = new Date()
                let now = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0')
                if (today !== now) {
                    location.reload()
                }
                let method = 'GET'
                let url = 'message/count/message'
                $.ajax({
                    url,
                    method,
                    success: function (res) {
                        console.info('update chart')
                        data[6] = res['count']
                        chart.updateSeries([{
                            data
                        }])
                    }
                })
            }, 10000)
            $('textarea.materialize-textarea').characterCounter()
        })
    </script>
@endsection
