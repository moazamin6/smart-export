@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">

        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-cube text-danger icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Overall Revenue</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">$65,650</h3>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-receipt text-warning icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Current Month Revenue</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">3455</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-store text-success icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stores Connected</p>

                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0"
                                        id="connected_stores">{{count($connectedStores)}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="mdi mdi-trackpad text-info icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Total Tracking</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{count($trackings)}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Tracking By Stores</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Store</th>
                                    <th>No of Tracking</th>
                                    <th>Created at</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($connectedStores as $index => $store)
                                    <tr>
                                        <td class="font-weight-medium">
                                            {{++$index}}
                                        </td>
                                        <td>
                                            {{$store->name}}
                                        </td>
                                        <td>
                                            {{count($store->trackings)}}
                                        </td>
                                        <td>
                                            {{formatDate($store->created_at,'Y-m-d')}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

        $(document).ready(() => {
            let config = {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }

            const interval = setInterval(function () {
                axios.get('{{route('connected-stores')}}', {}, config)
                    .then((res) => {
                        let count = res.data.stores.length;

                        $('#connected_stores').html(count)
                        // console.log(count)
                    })

                    .catch((error) => {
                        console.log(error)
                    })
            }, 10000);

            // clearInterval(interval);


        });
    </script>
@endpush
