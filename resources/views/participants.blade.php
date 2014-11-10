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
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($participants as $participant)
                <tr>
                    <td width="45"><img src="{{ $participant->picture }}" class="img-circle" width="40"></td>
                    <td>{{ $participant->name }}</td>
                    <td>
                        <a href="/participant/reload/{{$participant->id}}/today" class="btn btn-default">Ladda om idag</a>
                        <a href="/participant/reload/{{$participant->id}}/week" class="btn btn-default">Ladda om vecka</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
@stop