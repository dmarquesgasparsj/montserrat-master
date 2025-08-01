@extends('template')
@section('content')

    <section class="section-padding">
        <div class="jumbotron text-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>
                        <span class="grey">Activity Index</span>
                        <span class="grey">({{$activities->total()}} records)</span>
                        @can('update-activity')
                            <span class="create">
                                <a href={{ action([\App\Http\Controllers\ActivityController::class, 'create']) }}>{{ html()->img(asset('images/create.png'), 'Add Activity')->attribute('title', "Add Activity")->class('btn btn-primary') }}</a>
                            </span>
                         @endCan
                    </h1>
                    <span>{{ $activities->links() }}</span>
                </div>
                @if ($activities->isEmpty())
                    <p>It is a brand new world, there are no activities!</p>
                @else
                <table class="table table-bordered table-striped table-hover table-responsive"><caption><h2>Activities</h2></caption>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Contact Name</th>
                            <th>Contacted by</th>
                            <th>Type</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($activities as $activity)
                        <tr>

                            <td style="width:17%"><a href="activity/{{ $activity->id}}">{{ date('M d, Y g:i A', strtotime($activity->touched_at)) }}</a></td>
                            <td style="width:17%">{!! $activity->targets_full_name_link ?? __('messages.unknown_contacts') !!} </td>
                            <td style="width:17%">{!! $activity->assignees_full_name_link ?? __('messages.unknown_assignees') !!} </td>
                            <td style="width:5%">{{ $activity->activity_type_label }}</td>
                            <td style="width:44%">{{ $activity->details }}</td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>
                {{ $activities->links() }}

                @endif
            </div>
        </div>
    </section>
@stop
