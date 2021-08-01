@extends('layouts.default')

@push('scripts')
    <style>

    </style>
@endpush


@section('content')

    <div class="d-flex m-5 trigger-wrapper">
        <div class="d-flex flex-column w-100 trigger-list">
            <h5 class="font-weight-bold">Triggers</h5>
            <div class="ml-5">
                <p class="mt-2">Triggers will help you get notified when something goes wrong.</p>
                <a class="btn btn-primary" style="border: 3px solid #000" href="{{route('testTriggers')}}">Test Triggers</a>
            </div>

            <div class="d-flex flex-column triggers ml-3 p-3">
                <table class="table table-borderless">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Days</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>


                    @foreach ($triggers as $trigger)

                        <?php
                        $isAttached = checkIfTriggerAlreadyAttached($trigger->id, Auth::user()->id);
                        $value = '';
                        if (count($attached_trigger)) {
                            $value = $isAttached ? $attached_trigger[$trigger->id]->pivot->days : '';
                        }
                        ?>

                        <tr>
                            <td>{{$trigger->name}}</td>
                            <td>
                                <div class="form-inline">
                                    <input type="number" min="1" id="trigger_days_{{$trigger->id}}"
                                           value="{{$value}}"
                                           style="width: 70px; height: 30px" class="ml-1 form-control mr-2">
                                    <label for="trigger_days_{{$trigger->id}}" class="mr-2">Day (s) </label>

                                    @if($isAttached)
                                        <button id="btn_trigger_update_{{$trigger->id}}" trigger_id="{{$trigger->id}}"
                                                class="btn btn-secondary btn-small">Update
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td>

                                @if($isAttached)
                                    <p class="trigger-active font-weight-bold">{{TRIGGER_STATUS_ACTIVE}}</p>

                                @else
                                    {{TRIGGER_STATUS_INACTIVE}}
                                @endif
                            </td>
                            <td>
                                @if($isAttached)
                                    <button id="btn_trigger_remove_{{$trigger->id}}" trigger_id="{{$trigger->id}}"
                                            class="btn btn-primary mt-2 mb-2">Deactivate
                                    </button>
                                @else
                                    <button id="trigger_add_{{$trigger->id}}" trigger_id="{{$trigger->id}}"
                                            class="btn btn-secondary mt-2">Activate
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @if(checkIfTriggerAlreadyAttached($trigger->id,Auth::user()->id))
                        @endif
                    @endforeach
                    </tbody>
                </table>
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

            $("button[id^='trigger_add_']").on('click', (e) => {

                let el = $(e.target);
                let trigger_id = el.attr('trigger_id');

                let days_id = `#trigger_days_${trigger_id}`;
                let days = $(days_id).val();

                if (days !== '' && days !== '0') {

                    const data = {
                        trigger_id: trigger_id,
                        days: days
                    };
                    axios.post('{{route('add-trigger')}}', data, config)
                        .then((res) => {
                            window.location.replace('{{url('/triggers')}}');
                            // console.log(res)
                        })
                        .catch((error) => {
                            console.log(error)
                        })

                } else {
                    alert('Please enter valid no of days')
                }
                // console.log(trigger_id)
            });

            $("button[id^='btn_trigger_remove_']").on('click', (e) => {

                let el = $(e.target);
                let trigger_id = el.attr('trigger_id');

                axios.delete('{{url('/trigger/remove')}}/' + trigger_id, config)
                    .then((res) => {
                        window.location.replace('{{url('/triggers')}}');
                        // console.log(res)
                    })
                    .catch((error) => {
                        console.log(error)
                    })
                // console.log(trigger_id)
            });

            $("button[id^='btn_trigger_update_']").on('click', (e) => {

                let el = $(e.target);
                let trigger_id = el.attr('trigger_id');

                let days_id = `#trigger_days_${trigger_id}`;
                let days = $(days_id).val();

                if (days !== '' && days !== '0') {

                    axios.put('{{url('/trigger/update')}}/' + trigger_id, {days: days}, config)
                        .then((res) => {
                            window.location.replace('{{url('/triggers')}}');
                            // console.log(res)
                        })
                        .catch((error) => {
                            console.log(error)
                        })

                } else {
                    alert('Please enter valid no of days')
                }

                // console.log(trigger_id)
            });
        });
    </script>
@endpush
