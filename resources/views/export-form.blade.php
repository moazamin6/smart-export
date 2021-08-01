@extends('layouts.export')

@push('scripts')
    <style>

    </style>
@endpush


@section('content')

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
    <div class="d-flex m-5 align-content-center">

        <div class="col-8 col-md-4">

            <form action="{{route('export-form-excel')}}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="order-status">Select Order Status</label>
                    </div>
                    <select name="order-status" class="custom-select" id="order-status">
                        <option selected value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="open">Open</option>
                        <option value="success">Success</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="error">Error</option>
                        <option value="failure">Failure</option>
                    </select>
                </div>

                <input class="btn btn-dark" value="Export as Excel" type="submit">
            </form>

        </div>

    </div>

@endsection

@push('scripts')
    <script>


    </script>
@endpush
