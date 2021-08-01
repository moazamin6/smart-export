@extends('layouts.default')

@push('scripts')

    <style>

    </style>
@endpush

@section('content')


    <div class="d-flex flex-column">
        <div class="d-flex p-2 justify-content-center">
            <h1 class="font-weight-bold">Slack Integration Instructions</h1>
        </div>
        <div class="d-flex p-1 justify-content-center">

            <div class="card text-white bg-success mb-3 mr-4" style="max-width: 18rem;">
                <div class="card-header">Step 1</div>
                <div class="card-body">
                    <p class="card-text">
                        Click the Get Started Button below. Login to Your Slack account and Create new Slack App
                    </p>
                </div>
            </div>

            <div class="card text-white bg-success mb-3 mr-4" style="max-width: 18rem;">
                <div class="card-header">Step 2</div>
                <div class="card-body">
                    <p class="card-text">
                        Give name "OrderStalker" and select your workspace you wanted to get notifications
                    </p>
                </div>
            </div>

            <div class="card text-white bg-success mb-3 mr-4" style="max-width: 18rem;">
                <div class="card-header">Step 3</div>
                <div class="card-body">
                    <p class="card-text">
                        On right side click "Incoming Webhooks" and activate it
                    </p>
                </div>
            </div>

            <div class="card text-white bg-success mb-3 mr-4" style="max-width: 18rem;">
                <div class="card-header">Step 4</div>
                <div class="card-body">
                    <p class="card-text">
                        Scroll down to click "Add New Webhook to Workspace" and copy the webhook URL and paste in the
                        field below
                    </p>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column justify-content-center">
            <iframe style="height: 250px; width: 650px" class="align-self-center"
                    src="https://www.youtube.com/embed/fEnfYI5Hm_A">
            </iframe>
            <a href="https://api.slack.com/apps" target="_blank" class="btn btn-primary align-self-center mt-3"
               style="width: 200px">
                Get Started
            </a>
        </div>
        <div class="d-flex flex-column p-5 setting-page">
            <div class="d-flex flex-column card w-100 slack-card">
                <div class="d-flex ml-5 mt-3">

                    <div class="w-25 d-flex align-items-center justify-content-center">
                        <img width="150px" src="{{asset('images/slack_logo.png')}}" alt="">
                    </div>
                    <div class="vertical-line mr-5"></div>
                    <form class="w-75 ml-5" action="javascript:void(0)" method="post">

                        @csrf
                        <div>
                            <h1 class="font-weight-bold">Slack Integration</h1>
                        </div>
                        <br>
                        <div class="d-flex">
                            <p class="w-20 font-weight-bold align-self-start">Channel Name: &nbsp;</p>
                            <p class="">
                                <input
                                    id="channel-name"
                                    class="form-control align-self-start form-rounded @error('slack_channel') is-invalid @enderror"
                                    name="slack_channel" type="text"
                                    value="">
                            @error('slack_channel')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="d-flex">
                            <p class="w-20 font-weight-bold align-self-start">Channel Webhook URL: &nbsp;</p>

                            <div class="d-flex flex-row">
                                <input
                                    id="webhook-url"
                                    class="mr-2 form-control align-self-start form-rounded @error('slack_webhook') is-invalid @enderror"
                                    name="slack_webhook" type="text"
                                    value="">
                                @error('slack_webhook')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <button id="test-button" class="btn btn-secondary mb-4">
                                    Test Slack
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>

        $(document).ready(() => {
            let current_shop_name = $('#current-shop-name').val();
            $('#test-button').click(function () {

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
                    slack_message: `Slack is connected under ${current_shop_name}. Have fun with OrderStalker.`
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
                                            $('.overlay').show();
                                            axios.get('{{route('fetch-data')}}', config)
                                                .then((res) => {
                                                    window.location.replace('{{url('/install/video')}}');
                                                })
                                                .catch((error) => {
                                                    console.log(error)
                                                })
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
