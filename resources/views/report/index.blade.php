@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" id="report_panel">
                <div class="panel-heading">Report erstellen</div>

                <div class="panel-body">
                    <form class="form-inline" method="POST" action="{{route('report_create')}}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="from_date">Von&nbsp;</label>
                            <input type="text" class="form-control" id="from_date" name="from_date" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="to_date">&nbsp;bis&nbsp;</label>
                            <input type="text" class="form-control" id="to_date" name="to_date" readonly required>
                        </div>
                        <button type="submit" class="btn btn-primary">Report erstellen</button>
                        <div class="form-group">
                            <label for="quarter"></label>
                            <select class="form-control" id="quarter" name="quarter">
                                <option value=""></option>
                                <option value="{{date('Y')}}-01-01;{{date('Y')}}-03-31">1. Quartal</option>
                                <option value="{{date('Y')}}-04-01;{{date('Y')}}-06-30">2. Quartal</option>
                                <option value="{{date('Y')}}-07-01;{{date('Y')}}-09-30">3. Quartal</option>
                                <option value="{{date('Y')}}-10-01;{{date('Y')}}-12-31">4. Quartal</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            @if (isset($data))
            <div class="panel panel-default" id="print_panel">
                <div class="panel-heading">
                    Drucken
                </div>
                <div class="panel-body">
                    <div class="alert alert-info">
                        <h4><i class="fa fa-info-circle"></i> Informationen</h4>
                        Aufgrund verschiedener Umstände ist es derzeit nicht möglich den von den Browsern mitgelieferten PDF-Printer vorauszuwählen. Diese Druckfunktionalität zielt
                        jedoch genau darauf ab. Bitte treffen Sie die Auswahl deshalb manuell und speichern Sie die erstellte PDF am gewünschten Zielort.
                    </div>
                    <button class="btn btn-block btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Drucken</button>
                </div>
            </div>
            <div class="panel panel-default" id="export_panel">
                <div class="panel-heading">
                    Export
                </div>
                <div class="panel-body">
                    <a href="" class="btn btn-block btn-default">CSV</a>
                    <a href="" class="btn btn-block btn-default">PDF</a>
                    <a href="" class="btn btn-block btn-default">Datev</a>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Informationen
                </div>
                <div class="panel-body">
                    Dieser Report wurde am <b>{{(new \Jenssegers\Date\Date())->format('d.m.Y H:i:s')}}</b> von <b>{{\Illuminate\Support\Facades\Auth::user()->name}}</b> erstellt und umfasst
                    den Datenbestand von dem <b>{{\App\Formatter::date(request('from_date'))}}</b> bis zum <b>{{\App\Formatter::date(request('to_date'))}}</b>.
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Autos
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bezeichnung</th>
                            <th>Fahrgestellnummer</th>
                            <th>EK-Datum</th>
                            <th>EK-Preis</th>
                            <th>VK-Datum</th>
                            <th>VK-Preis</th>
                            <th>Gewinn</th>
                            <th>Mehrwertsteuer</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $ids = [];

                            foreach($data['expenses'] as $expense) {
                                if ($expense->car_id && !in_array($expense->car_id, $ids)) {
                                    echo '<tr>
                                <td>'.$expense->car->id.'</td>
                                <td>'.$expense->car->title.'</td>
                                <td>'.$expense->car->chassis_number.'</td>
                                <td>'.$expense->car->getPurchaseDate().'</td>
                                <td>'.$expense->car->getPurchasePrice().'</td>
                                <td>'.$expense->car->getSaleDate().'</td>
                                <td>'.$expense->car->getSalePrice().'</td>
                                <td>'.(($expense->car->sale_date) ? \App\Formatter::indicatedCurrency($expense->car->sale_price - $expense->car->purchase_price) : null).'</td>
                                <td>'.(
                                        ($expense->car->sale_date && ($expense->car->sale_price - $expense->car->purchase_price) > 0) ?
                                            ($expense->car->tax) ?
                                                \App\Formatter::indicatedCurrency($expense->car->sale_price - $expense->car->purchase_price - (($expense->car->sale_price - $expense->car->purchase_price)/1.19)) :
                                                \App\Formatter::indicatedCurrency($expense->car->sale_price - (($expense->car->sale_price)/1.19))
                                            : null).'</td>
                            </tr>';
                                }

                                $ids[] =$expense->car_id;
                            }
                        @endphp
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                    <div class="panel-heading">
                        <span class="badge">{{count($data['expenses'])}}</span> Rechnungen/Quittungen
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>FG</th>
                                <th>Bezeichnung</th>
                                <th>Betrag</th>
                                <th></th>
                                <th>Datum</th>
                                <th>Kassenstand</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>###</td>
                                    <td>###</td>
                                    <td>###</td>
                                    <td>Kassenstand: Übertrag</td>
                                    <td>{!! \App\Formatter::indicatedCurrency($data['cashBefore']) !!}</td>
                                    <td></td>
                                    <td>bis aussschließlich {{request('from_date')}}</td>
                                    <td>{!! \App\Formatter::indicatedCurrency($data['cashBefore']) !!}</td>
                                </tr>
                            @php
                                $cashBefore = $data['cashBefore'];
                                if (count($data['expenses'])) {
                                    $counter = 0;
                                    foreach($data['expenses'] as $expense) {
                                        echo    '<tr '.(($expense->hasConflict()) ? 'class="danger"' : null).'>' .
                                                    '<td>'.sprintf('%04d', $counter++).'</td>' .
                                                    '<td><a href="'.route('expense_detail', ['id' => $expense->id]).'" target="_blank">'.$expense->id.'</a></td>' .
                                                    '<td>'.(($expense->car_id) ? $expense->car->chassis_number : null).'</td>' .
                                                    '<td>'.$expense->getTitle(). (($expense->account) ? '<br/> (Buchung vom Bankkonto)' : null).'</td>' .
                                                    '<td>'.$expense->getIndicatedPrice() . '</td>' .
                                                    '<td>'.($expense->sale_invoice || $expense->purchase_invoice ?  $expense->car->getTaxIdentifier()  : $expense->getTaxIdentifier()).'</td>'.
                                                    '<td>'.$expense->getDate().'</td>' .
                                                    '<td>'.(\App\Formatter::indicatedCurrency((($expense->account) ? $cashBefore : $cashBefore + ($expense->price)))).'</td>' .
                                                '</tr>';

                                            if (!$expense->account)
                                                $cashBefore = $cashBefore + ($expense->price);
                                    }
                                } else {
                                    echo '<tr class="info"><td colspan="8">Keine Daten verfügbar</td></tr>';
                                }
                            @endphp
                            </tbody>
                        </table>
                        <div class="panel-body" style="text-align:right">
                            <span class="badge">Kassenstand: {{\App\Formatter::currency($cashBefore)}}</span>
                        </div>
                    </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(function() {
        $('#from_date').datepicker({
            language: 'de',
            onSelect: function(fd, d, picker) {
                $('#to_date').data('datepicker').update('minDate', d);
            }
        });

        $('#to_date').datepicker({
            language: 'de'
        });

        $('#quarter').change(function() {
            var value = $(this).val();

            if (value) {
                var dates = value.split(';');

                $('#from_date').datepicker().data('datepicker').selectDate(new Date(dates[0]));
                $('#to_date').datepicker().data('datepicker').selectDate(new Date(dates[1]));
            } else {
                $('#from_date').datepicker().data('datepicker').selectDate(new Date());
                $('#to_date').datepicker().data('datepicker').selectDate(new Date());
            }

        });

        $('#quarter').trigger('change');

        $('#from_date').datepicker().data('datepicker').selectDate(new Date('1990-12-12'));
    });
</script>
@endpush
