@extends('layouts.export')

@push('scripts')
    <style>

        .btn-dark {

            width: 200px !important;
            margin-bottom: 10px;
        }
    </style>
@endpush


@section('content')

    <div class="d-flex m-5 align-content-center">

        <div class="col-8 col-md-4">

            <form action="{{route('export-form-excel')}}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="order-status">Select Order Status</label>
                    </div>
                    <select name="order_status" class="custom-select" id="order-status">
                        <option selected value="any">Any</option>
                        <option value="shipped">Fulfilled</option>
                        <option value="unshipped">Unfulfilled</option>
                    </select>
                </div>

                <input class="btn btn-dark" name="btn_export" value="{{EXPORT_BTN_LABEL_TCS}}" type="submit">
                <br>
                <input class="btn btn-dark" name="btn_export" value="{{EXPORT_BTN_LABEL_LCS}}" type="submit">
            </form>

        </div>

    </div>
    <div class="d-flex flex-column mt-5 ml-5 flex-row col-8 col-md-4">
        <h3>Order Statuses</h3>

        <ul class="list-group" style="width: 850px">
            <li class="list-group-item">
                <strong>pending</strong>: The fulfillment is pending.
            </li>
            <li class="list-group-item">
                <strong>open</strong>: The fulfillment has been acknowledged by the service and
                is in processing.
            </li>
            <li class="list-group-item">
                <strong>success</strong>: The fulfillment was successful.
            </li>
            <li class="list-group-item">
                <strong>cancelled</strong>: The fulfillment was cancelled.
            </li>
            <li class="list-group-item">
                <strong>error</strong>: There was an error with the fulfillment request.
            </li>
            <li class="list-group-item">
                <strong>failure</strong>: The fulfillment request failed.
            </li>
        </ul>
    </div>


@endsection

@push('scripts')
    <script>


    </script>
@endpush
