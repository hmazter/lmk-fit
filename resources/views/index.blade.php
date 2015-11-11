@extends('layout')

@section('content')
    <div class="row">
        <div class="col-lg-12">

            @if (Session::has('message') && Session::get('message') == 'added')
            <div class="alert alert-success" role="alert">Deltagare tillagd</div>
            @endif
            @if (Session::has('message') && Session::get('message') == 'reload')
            <div class="alert alert-success" role="alert">Omladdning startad</div>
            @endif

            <div class="btn-group" role="group" aria-label="...">
                <a href="/?type=steps" class="btn {{ $type == 'steps' ? 'btn-info' : 'btn-default' }}">Steg</a>
                <a href="/?type=time" class="btn {{ $type == 'time' ? 'btn-info' : 'btn-default' }}">Aktivitetstid</a>
            </div>

            <span class="pull-right">Senaste omladdningen: {{ $last_reload }}</span>
            @if($type == 'steps')
                <h3>Steg per dag och deltagare</h3>
            @elseif($type == 'time')
                <h3>Aktivitetstid per dag och deltagare</h3>
            @endif

            <div class="table-responsive">
                <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Datum</th>
                        @foreach($participants as $participant)
                            <th>
                                {{ $participant->name }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($fitnessData as $date => $amount)
                    <tr>
                        <td>{{ $date }}</td>
                        @foreach($participants as $participant)
                            <td>
                                @if($type == 'steps' && isset($amount[$participant->id]))
                                    {{ number_format($amount[$participant->id], 0, ',', ' ') }} steg
                                @endif

                                @if($type == 'time' && isset($amount[$participant->id]))
                                    {{ number_format($amount[$participant->id] / 60, 0, ',', ' ') }} minuter
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>

            <h3>
                Veckans topplista
                <small>{{ $weekTopDates }}</small>
            </h3>
            <table class="table table-toplist">
                <thead>
                    <tr>
                        <th>Plats</th>
                        <th>Deltagare</th>
                        <th>Summa steg</th>
                    </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                    @foreach($weekTop as $fitnessData)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $fitnessData->participant->name }}</td>
                        <td>
                            @if($type == \LMK\Models\FitnessData::TYPE_STEP)
                                {{ number_format($fitnessData->total_amount, 0, ',', ' ') }} steg
                                <small class="text-muted">{{ number_format($fitnessData->total_amount/7, 0, ',', ' ') }} steg/dag</small>

                            @elseif($type == \LMK\Models\FitnessData::TYPE_TIME)
                                {{ number_format($fitnessData->total_amount / 60, 0, ',', ' ') }} minuter
                                <small class="text-muted">{{ number_format($fitnessData->total_amount/7/60, 0, ',', ' ') }} minuter/dag</small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h3>
                Gårdagens topplista
                <small>{{ $yesterdayTopDates }}</small>
            </h3>
            <table class="table table-toplist">
                <thead>
                    <tr>
                        <th>Plats</th>
                        <th>Deltagare</th>
                        <th>Summa steg</th>
                    </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                    @foreach($yesterdayTop as $fitnessData)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $fitnessData->participant->name }}</td>
                        <td>
                            @if($type == \LMK\Models\FitnessData::TYPE_STEP)
                                {{ number_format($fitnessData->amount, 0, ',', ' ') }} steg
                            @elseif($type == \LMK\Models\FitnessData::TYPE_TIME)
                                {{ number_format($fitnessData->amount/60, 0, ',', ' ') }} minuter
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@stop