@extends('layout')

@section('content')
    <div class="row">
        <div class="col-lg-12">

            @if (Session::has('message'))
            <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
            @endif

            <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Deltagare</th>
                    <th>Snitt steg/dag</th>
                    <th>Ladda om data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($participants as $participant)
                <tr>
                    <td width="45"><img src="{{ $participant->picture }}" class="img-circle" width="40"></td>
                    <td>{{ $participant->name }}</td>
                    <td>
                        @if($participant->day_count > 0)
                            {{ number_format($participant->total_steps / $participant->day_count, 0, ',', ' ') }} steg / dag
                        @else
                            0 steg / dag
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('reload', [$participant->id, 'today']) }}" class="btn btn-default">Dagens</a>
                        <a href="{{ route('reload', [$participant->id, 'yesterday']) }}" class="btn btn-default">Gårdagen</a>
                        <a href="{{ route('reload', [$participant->id, 'week']) }}" class="btn btn-default">Senaste veckans</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
@stop