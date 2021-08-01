@include('home.dashboard_intro')
<header>
    <div>
        <img style="margin:25px;" width="110px" src="{{asset('images/logo.png')}}" alt="">

        <span class="header-buttons">
{{--            <a class="btn btn-primary" href="{{route('all-billing')}}">All Test Billing</a>--}}
{{--            <a class="btn btn-primary" href="{{route('billing-test')}}">Billing Test</a>--}}
{{--            <a class="btn btn-primary" href="{{route('support')}}">Support</a>--}}
            <a class="btn btn-primary" id="manual_modal" href="javascript:void(0)">Manual</a>
            <a class="btn btn-primary" target="_top" href="mailto:support@orderstalker.com">Support</a>
            <a class="btn btn-secondary" target="_blank" href="https://orderstalker.com/#faqs">FAQs</a>
        </span>
    </div>
</header>
@include('layouts.inc.navbar')

@push('scripts')

    <script>

        $(document).ready(() => {

            $('#manual_modal').on('click', () => {
                $('#dashboard_intro').modal('show')
            })
        })
    </script>
@endpush
