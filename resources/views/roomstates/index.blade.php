@extends('template')
@section('content')

<section class="section-padding">
    <div class="jumbotron text-left">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1><span class="grey">Housekeeping</span></h1>
            </div>
            @if ($rooms->isEmpty())
                <p>No rooms need cleaning.</p>
            @else
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Building</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->location->name }}</td>
                        <td>
                            {{ html()->form('PUT', route('roomstate.update', $room->id))->open() }}
                                {{ html()->submit('Mark Clean')->class('btn btn-success') }}
                            {{ html()->form()->close() }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</section>
@stop
