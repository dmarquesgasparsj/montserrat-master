@extends('template')
@section('content')

    <section class="section-padding">
        <div class="jumbotron text-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>
                        <span class="grey">Touchpoint Index</span>
                        <span class="grey">({{$touchpoints->total()}} records)</span>
                        @can('update-touchpoint')
                            <span class="create">
                                <a href={{ action([\App\Http\Controllers\TouchpointController::class, 'create']) }}>{{ html()->img(asset('images/create.png'), 'Add Touchpoint')->attribute('title', "Add Touchpoint")->class('btn btn-primary') }}</a>
                            </span>
                            <span class="create">
                                <a href={{ action([\App\Http\Controllers\TouchpointController::class, 'add_group'],0) }}>{{ html()->img(asset('images/group_add.png'), 'Add Group Touchpoint')->attribute('title', "Add Group Touchpoint")->class('btn btn-primary') }}</a>
                            </span>
                    </h1>@endCan
                    <span>{{ $touchpoints->links() }}</span>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <select class="custom-select" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                            <option value="">Filter by staff member ...</option>
                            <option value="{{url('touchpoint')}}">All touchpoints</option>
                            @foreach($staff as $member_id => $member_name)
                                <option value="{{url('touchpoint/type/'.$member_id)}}">{{ $member_name }}</option>
                            @endForeach
                        </select>
                    </div>
                </div>

                @if ($touchpoints->isEmpty())
                    <p>{{ __('messages.no_touchpoints_message') }}</p>
                @else
                <table class="table table-bordered table-striped table-hover"><caption><h2>Touchpoints</h2></caption>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>{{ __('messages.contact_name') }}</th>
                            <th>{{ __('messages.contacted_by') }}</th>
                            <th>Type</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($touchpoints as $touchpoint)
                        <tr>

                            <td style="width:17%"><a href={{ URL('touchpoint/' . $touchpoint->id) }} ">{{ date('M d, Y g:i A', strtotime($touchpoint->touched_at)) }}</a></td>
                            <td style="width:17%">{!! $touchpoint->person->contact_link_full_name ?? __('messages.unknown_contact') !!} </td>
                            <td style="width:17%">{!! $touchpoint->staff->contact_link_full_name ?? __('messages.unknown_staff_member') !!} </td>
                            <td style="width:5%">{{ $touchpoint->type }}</td>
                            <td style="width:44%">{{ $touchpoint->notes }}</td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>
                {{ $touchpoints->links() }}

                @endif
            </div>
        </div>
    </section>
@stop
