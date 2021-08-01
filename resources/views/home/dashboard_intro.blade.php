@push('styles')
    <style>
        #dashboard_intro p {
            text-align: justify;
            text-justify: inter-word;
        }
    </style>
@endpush

<div class="modal fade" id="dashboard_intro" tabindex="-1" role="dialog" aria-labelledby="dashboard_intro_label" aria-hidden="true">
    <div class="modal-dialog d-flex flex-column" role="document">
        <div class="d-flex modal-content align-self-center" style="width: 1000px">
            <div class="d-flex" style="background: black; color: #cccccc">
                <img src="{{asset('images/orderstalker_man.png')}}" alt="">

                <div class="d-flex flex-column mt-4 ml-4 mr-5">
                    <p style="text-align: center;font-size: 30px;">
                        <u>OrderStalker Manual</u>
                    </p>
                    <p>
                        On first time installation, the application will get your first 7 days data and will save it
                        into
                        the database and will record data for today’s onward.
                    </p>
                    <hr>
                    <p>
                        OrderStalker will contain your maximum 30 days data and will automatically truncate all the
                        entries
                        other than 30 days range starting from your day of installation.
                    </p>
                    <hr>
                    <p>
                        The application will automatically get updated after every 12th UTC hours, meaning twice a day
                        no
                        matter whichever time zone you are from.
                    </p>
                    <hr>
                    <p>
                        Orderstalker will charge $29.95 on every 30th day of the month and will charge $10 on every 500+
                        orders.
                    </p>

                    <p>
                        Have an awesome time with tracking and growing
                    </p>
                    <p style="text-align: center" class="mt-5">
                        <u>
                            <b>
                                QA Team | <a href="https://orderstalker.com/" style="color: white" target="_blank">OrderStalker</a>
                            </b>
                        </u>
                    </p>
                </div>
            </div>


            {{--            <div class="modal-footer">--}}
            {{--                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
            {{--            </div>--}}
        </div>
    </div>
</div>

@push('scripts')
    <script>

        $(document).ready(() => {

        });

    </script>
@endpush

