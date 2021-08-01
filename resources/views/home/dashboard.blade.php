@extends('layouts.default')

{{--@section('styles')--}}
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css">--}}

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('date-range-picker/css/datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('footable/css/footable.bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/3.0.1/introjs.min.css"
          integrity="sha512-fsq7ym+bMq7ecs277R2a3QDPxF+JIwNNnkPfj9rIKDUyqrbXDnICZj/cCLcP3bKh3+jsCBnBR7BZJShOmELL0Q=="
          crossorigin="anonymous"/>
    <style>

    </style>
@endpush

{{--@endsection--}}

@section('content')

    <div class="d-flex flex-row ml-2 mr-1 wrapper-1">
        <div class="d-flex progress-bars align-items-center w-75 ml-1 mr-2"
             data-intro="This section shows the accurate percentage of products in each phase(status)"
             data-step="2">
            <div class="d-flex progress-bars-1 flex-column w-50">

                <div class="d-flex flex-column w-100">

                    <div class="d-flex justify-content-between mb-1">
                        <div class="d-flex progress-title font-weight-bold">
                            {{$percentage->delivered}}%
                        </div>
                        <div class="d-flex progress-title font-weight-bold">
                            Delivered
                        </div>
                    </div>


                    <div class="progress">
                        <div class="progress-bar order-delivered" role="progressbar"
                             style="width: {{$percentage->delivered}}%"
                             aria-valuenow="50"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <div class="mt-4"></div>
                <div class="d-flex flex-column w-100">

                    <div class="d-flex justify-content-between mb-1">
                        <div class="d-flex progress-title font-weight-bold">
                            {{$percentage->out_for_delivery}}%
                        </div>
                        <div class="d-flex progress-title font-weight-bold">
                            Out For Delivery
                        </div>
                    </div>


                    <div class="progress">
                        <div class="progress-bar order-out-for-delivery" role="progressbar"
                             style="width: {{$percentage->out_for_delivery}}%"
                             aria-valuenow="50"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <div class="mt-4"></div>
                <div class="d-flex flex-column w-100">

                    <div class="d-flex justify-content-between mb-1">
                        <div class="d-flex progress-title font-weight-bold">
                            {{$percentage->exception}}%
                        </div>
                        <div class="d-flex progress-title font-weight-bold">
                            Exception
                        </div>
                    </div>

                    <div class="progress">
                        <div class="progress-bar order-exception" role="progressbar"
                             style="width: {{$percentage->exception}}%"
                             aria-valuenow="50"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <div class="mt-4"></div>
                <div class="d-flex flex-column w-100">

                    <div class="d-flex justify-content-between mb-1">
                        <div class="d-flex progress-title font-weight-bold">
                            {{$percentage->failed_attempts}}%
                        </div>
                        <div class="d-flex progress-title font-weight-bold">
                            Failed Attempts
                        </div>
                    </div>


                    <div class="progress">
                        <div class="progress-bar order-failed-attempts" role="progressbar"
                             style="width: {{$percentage->failed_attempts}}%"
                             aria-valuenow="50"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex progress-bars-2 flex-column w-50">

                <div class="d-flex flex-column w-100">

                    <div class="d-flex justify-content-between mb-1">
                        <div class="d-flex progress-title font-weight-bold">
                            {{$percentage->transit}}%
                        </div>
                        <div class="d-flex progress-title font-weight-bold">
                            In Transit
                        </div>
                    </div>


                    <div class="progress">
                        <div class="progress-bar order-transit" role="progressbar"
                             style="width: {{$percentage->transit}}%"
                             aria-valuenow="50"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <div class="mt-4"></div>
                <div class="d-flex flex-column w-100">

                    <div class="d-flex justify-content-between mb-1">
                        <div class="d-flex progress-title font-weight-bold">
                            {{$percentage->expired}}%
                        </div>
                        <div class="d-flex progress-title font-weight-bold">
                            Expired
                        </div>
                    </div>


                    <div class="progress">
                        <div class="progress-bar order-expired" role="progressbar"
                             style="width: {{$percentage->expired}}%"
                             aria-valuenow="50"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <div class="mt-4"></div>
                <div class="d-flex flex-column w-100">

                    <div class="d-flex justify-content-between mb-1">
                        <div class="d-flex progress-title font-weight-bold">
                            {{$percentage->info_not_received}}%
                        </div>
                        <div class="d-flex progress-title font-weight-bold">
                            Info Not Received
                        </div>
                    </div>


                    <div class="progress">
                        <div class="progress-bar order-info-not-received" role="progressbar"
                             style="width: {{$percentage->info_not_received}}%"
                             aria-valuenow="50"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <div class="mt-4"></div>
                <div class="d-flex flex-column w-100">

                    <div class="d-flex justify-content-between mb-1">
                        <div class="d-flex progress-title font-weight-bold">
                            {{$percentage->pending}}%
                        </div>
                        <div class="d-flex progress-title font-weight-bold">
                            Pending
                        </div>
                    </div>


                    <div class="progress">
                        <div class="progress-bar order-pending" role="progressbar"
                             style="width: {{$percentage->pending}}%"
                             aria-valuenow="50"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pie-chart w-25"
             data-intro="This chart helps you analyze orders in different phases over the total orders."
             data-step="3">
            <canvas id="pie_chart"></canvas>
        </div>
    </div>

    <div class="d-flex mt-1 flex-row ml-2 mr-1 wrapper-2">
        <div class="calendar w-25"
             data-intro="Here you can filter the orders related data that you want to see." data-step="4">

            @if($filtered_date!==null)
                <div class="input-group mb-3">
                    <input type="text" class="form-control" value="{{$filtered_date}}" aria-describedby="basic-addon1">
                    <div class="input-group-prepend">
                        <button class="btn btn-secondary btn-clear-filter" type="button">X</button>
                    </div>
                </div>
            @endif

            <input type="hidden" data-multiple-dates-separator=" - " data-range="true"
                   id="range-calendar"/>
        </div>

        <div class="d-flex flex-column progress-bars w-75 ml-1 mr-2 p-2 order-wrapper">
            <h5 class="font-weight-bold">Orders</h5>

            <table class="table order-table"
                   data-intro="This panel shows the most recent orders data and also filtered data according to date selection."
                   data-step="5">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Order ID</th>
                    <th>Status</th>
                    <th>Customer</th>
                    <th>Country</th>
                    <th style="width: 300px">Item Names</th>
                    <th>Fulfill Date</th>
                </tr>
                </thead>
                <tbody>


                @php
                    $count=0;
                @endphp

                @foreach($order_items as $item)

                    <tr data-expanded="true">
                        <td>{{++$count}}</td>
                        <td>
                            <a class="font-weight-bold" href="{{$item->order_url}}"
                               target="_blank">{{$item->order_number}}</a>
                        </td>
                        <td>
                            <span class="badge {{getOrderStatusClass($item->status)}} p-2">
                                {{getOrderStatusValue($item->status)}}
                            </span>
                        </td>
                        <td>{{$item->customer_name}}</td>
                        <td>{{$item->country}}</td>
                        <td>{{$item->item_name}}</td>
                        <td>{{formatDate($item->fulfill_at)}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')

    {{--    datepicker docs http://t1m0n.name/air-datepicker/docs/--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="{{asset('date-range-picker/js/datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('date-range-picker/js/localize/datepicker.en.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.31/moment-timezone.min.js"></script>
    <script type="text/javascript" charset="utf8"
            src="{{asset('footable/js/footable.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/3.0.1/intro.min.js"
            integrity="sha512-Y3bwrs/uUQhiNsD26Mpr5YvfG18EY0J+aNxYI7ZQPJlM9H+lElGpuh/JURVJR/NBE+p1JZ+sVE773Un4zQcagg=="
            crossorigin="anonymous"></script>

    <script>

        $(document).ready(() => {

            let myChart = document.getElementById('pie_chart').getContext('2d');

            let percentage ={!! json_encode($percentage) !!};
            let labels = [
                'Delivered',
                'Exception',
                'Out For Delivery',
                'Failed Attempt',
                'In Transit',
                'Expired',
                'Info Not Received',
                'Pending'
            ];
            Chart.defaults.global.defaultFontFamily = 'Montserrat';
            Chart.defaults.global.defaultFontSize = 18;
            Chart.defaults.global.defaultFontColor = '#000000';
            let massPopChart = new Chart(myChart, {

                type: 'doughnut', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
                data: {

                    labels: labels,
                    datasets: [{
                        data: [
                            percentage.delivered,
                            percentage.exception,
                            percentage.out_for_delivery,
                            percentage.failed_attempts,
                            percentage.transit,
                            percentage.expired,
                            percentage.info_not_received,
                            percentage.pending,
                        ],
                        //backgroundColor:'green',
                        backgroundColor: [
                            '#F89406',
                            '#4AAF05',
                            '#9B59B6',
                            '#0677B5',
                            '#EC3C31',
                            '#616B71',
                            '#E96228',
                            '#662D91',
                        ],
                        borderWidth: 0,
                        borderColor: '#777',
                        hoverBorderWidth: 0,
                        hoverBorderColor: 'transparent'
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Tracker',
                        fontSize: 20,
                        fontWeight: 'bold'
                    },
                    legend: {
                        display: false,
                        position: 'right',
                        labels: {
                            fontColor: '#000'
                        }
                    },
                    layout: {
                        padding: {
                            left: 0,
                            right: 0,
                            bottom: 0,
                            top: 0
                        }
                    },
                    maintainAspectRatio: false,
                    responsive: true,
                    tooltips: {
                        enabled: true,
                        callbacks: {
                            label: function (tooltipItem, data) {

                                let label = data.labels[tooltipItem.index];
                                let val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                return label + ': (' + val + '%)';
                            }
                        }
                    }
                }
            });
            const refresh_date ={!! json_encode($refresh_date) !!};

            let minDate = new Date(refresh_date);
            let maxDate = new Date();

            $('#range-calendar').datepicker({

                minDate: minDate,
                maxDate: maxDate,
                inline: true,
                language: 'en',
                dateFormat: 'yyyy/mm/dd',
                onSelect: (formattedDate, date, inst) => {

                    if (date.length === 2) {

                        let dateFormat = 'YYYY-DD-MM';
                        let startTimestamp, endTimestamp;

                        startTimestamp = moment(date[0]).format(dateFormat);
                        endTimestamp = moment(date[1]).format(dateFormat);

                        let params = `/${startTimestamp}/${endTimestamp}`;
                        window.location.replace('{{url('/filter')}}' + params);
                    }
                }
            });

            $('.order-table').footable();

            $('.btn-clear-filter').on('click', () => {

                window.location.replace('{{url('/')}}');
            })
        })

        let config = {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }

        let is_step_by_step_config_completed = {!! json_encode($is_step_by_step_config_completed) !!};
        if (!is_step_by_step_config_completed) {
            introJs()
                .setOption('exitOnOverlayClick', false)
                .setOption('exitOnEsc', false)
                .onchange((el) => {
                    let step = $(el).data('step');
                    // Final Intro Step
                    if (step === 5) {

                    }
                })
                .oncomplete(function () {
                    axios.post('{{route('config-save')}}', [], config)
                        .then((res) => {
                            // console.log(res)
                            $('#dashboard_intro').modal('show')
                        })
                        .catch((error) => {
                            console.log(error)
                        })
                })
                .onexit(function () {

                })
                .start();


        }
    </script>
@endpush
