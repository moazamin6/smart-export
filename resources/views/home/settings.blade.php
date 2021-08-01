@extends('layouts.default')

@push('scripts')

    <style>

    </style>
@endpush

@section('content')
    @include('layouts.inc.flash_messages')
    <div class="d-flex flex-column p-5 setting-page">

        <div class="d-flex w-70 flex-column align-self-center">
            <div class="d-flex flex-column">
                <p class="font-weight-bold">Settings</p>
                <h3 class="font-weight-bold">Integrations</h3>
            </div>


            <div class="d-flex">
                <div class="box-shadow d-flex flex-column card w-100 slack-card mr-3">
                    <div class="d-flex justify-content-between">
                        <img src="{{asset('images/icons/slack.png')}}" alt="">
                        <div class="d-flex mt-3 mr-3">
                            <div class="green-dot mr-2 mt-2"></div>
                            <p>Connected</p>
                        </div>
                    </div>

                    <div class="d-flex flex-column ml-5">

                        <form action="javascript:void(0)" method="post">
                            @csrf
                            <div class="d-flex">

                                <p class="w-20 font-weight-bold align-self-start">Channel Name: &nbsp;</p>
                                <input
                                    id="channel-name"
                                    class="form-control align-self-start form-rounded @error('slack_channel') is-invalid @enderror"
                                    name="slack_channel" type="text"
                                    value="{{$slack_channel!==null?$slack_channel->value:''}}">

                            </div>

                            <div class="d-flex">
                                <p class="w-20 font-weight-bold align-self-start">Webhook URL: &nbsp;</p>
                                <p class="">
                                    <input
                                        id="webhook-url"
                                        class="form-control align-self-start form-rounded @error('slack_webhook') is-invalid @enderror"
                                        name="slack_webhook" type="text"
                                        value="{{$slack_webhook!==null?$slack_webhook->value:''}}">
                                </p>
                                @error('slack_webhook')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="d-flex">
                                <p class="w-30 align-self-start"></p>
                                <button id="slack-update" class="btn btn-secondary mb-4">
                                    Update Slack
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{--            <div class="box-shadow d-flex flex-column card w-100 slack-card ml-3">--}}
                {{--                <div class="d-flex justify-content-between">--}}
                {{--                    <img class="ml-3" src="{{asset('images/icons/card.png')}}" alt="">--}}
                {{--                    <div class="d-flex mt-3 mr-3">--}}
                {{--                        <div class="green-dot mr-2 mt-2"></div>--}}
                {{--                        <p>Connected</p>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="d-flex flex-column ml-5">--}}

                {{--                    <form action="{{route('setting-save')}}" method="post">--}}
                {{--                        @csrf--}}
                {{--                        <div class="d-flex">--}}

                {{--                            <p class=" w-30 font-weight-bold align-self-start">Name: &nbsp;</p>--}}
                {{--                            <p class="align-self-start">{{$card_holder_name}}</p>--}}

                {{--                        </div>--}}
                {{--                        <div class="d-flex">--}}
                {{--                            <p class="w-30 font-weight-bold align-self-start">Card Number: &nbsp;</p>--}}
                {{--                            <p class="align-self-start">--}}
                {{--                                <span class="black-dot"></span>--}}
                {{--                                <span class="black-dot"></span>--}}
                {{--                                <span class="black-dot"></span>--}}
                {{--                                <span class="black-dot"></span>--}}
                {{--                                &nbsp;--}}
                {{--                                <span class="black-dot"></span>--}}
                {{--                                <span class="black-dot"></span>--}}
                {{--                                <span class="black-dot"></span>--}}
                {{--                                <span class="black-dot"></span>--}}
                {{--                                &nbsp;--}}
                {{--                                <span class="black-dot"></span>--}}
                {{--                                <span class="black-dot"></span>--}}
                {{--                                <span class="black-dot"></span>--}}
                {{--                                <span class="black-dot"></span>--}}
                {{--                                &nbsp;--}}
                {{--                                {{$card_last4}}--}}
                {{--                            </p>--}}
                {{--                        </div>--}}
                {{--                        <div class="d-flex">--}}
                {{--                            <p class="w-30 align-self-start"></p>--}}
                {{--                            <button type="button" class="btn btn-primary" data-toggle="modal"--}}
                {{--                                    data-target="#payment_update">Update--}}
                {{--                            </button>--}}
                {{--                            @include('payment.update_modal')--}}
                {{--                        </div>--}}
                {{--                    </form>--}}
                {{--                </div>--}}
                {{--            </div>--}}
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>

        $(document).ready(() => {
            let current_shop_name = $('#current-shop-name').val();

            $('#slack-update').click(function () {

                let config = {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }

                let channel_name = $('#channel-name').val();
                let webhook_url = $('#webhook-url').val();
                let url_pattern = new RegExp("^(?:http(s)?:\\/\\/)?[\\w.-]+(?:\\.[\\w\\.-]+)+[\\w\\-\\._~:/?#[\\]@!\\$&'\\(\\)\\*\\+,;=.]+$");
                const data = {
                    slack_channel: channel_name,
                    slack_webhook: webhook_url,
                    slack_message: `Slack is updated under ${current_shop_name}. Have fun with OrderStalker`
                };
                if (channel_name.trim() !== '' && webhook_url.trim() !== '') {

                    if (url_pattern.test(webhook_url)) {

                        axios.post('{{route('setting-save')}}', data, config)
                            .then((res) => {
                                let status = res.data.status;
                                if (status) {
                                    alertify.confirm("Webhook verified. Did you receive message?(Y/N)",
                                        () => {

                                            alertify.success('Great');
                                            window.location.replace('{{url('/settings')}}');
                                        },
                                        () => {
                                            alertify.alert('Please update your webhook URL and try again!')
                                                .set({title: "Slack"});
                                        })
                                        .set({title: "Slack"}).set({labels: {ok: 'Yes', cancel: 'No'}});
                                } else {
                                    alertify.alert('Your webhook URL is not accessible please check your URL and try again!')
                                        .set({title: "Slack"});
                                }

                                // console.log(res.data.status)
                            })
                            .catch((error) => {
                                console.log(error)
                            })
                    } else {

                        alertify.alert('Please enter valid url.')
                            .set({title: "Slack"});
                    }


                } else {

                    alertify.alert('Please fill out all fields.')
                        .set({title: "Slack"});
                }

            })
        });

    </script>
@endpush
