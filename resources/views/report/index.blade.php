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
                    <span class="badge">{{count($data['purchased'])}}</span> Gekaufte Fahrzeuge
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Bezeichnung</th>
                                <th>Fahrgestellnummer</th>
                                <th>E-Datum</th>
                                <th>E-Preis</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            if (count($data['purchased'])) {
                                $counter = 0;
                                $total = 0;
                                foreach($data['purchased'] as $pCar) {
                                    $total += $pCar->purchase_price;
                                    echo    '<tr>' .
                                                '<td>'.sprintf('%04d', $counter++).'</td>' .
                                                '<td>'.$pCar->id.'</td>' .
                                                '<td>'.$pCar->title.'</td>' .
                                                '<td>'.$pCar->chassis_number.'</td>' .
                                                '<td>'.$pCar->getPurchaseDate().'</td>' .
                                                '<td>'.$pCar->getPurchasePrice().'</td>' .
                                            '</tr>';
                                }
                            } else {
                                echo '<tr class="info"><td colspan="6">Keine Daten verfügbar</td></tr>';
                            }
                            @endphp
                        </tbody>
                    </table>
                    <div class="panel-body" style="text-align:right">
                        <span class="badge">Summe: {{\App\Formatter::currency($total)}}</span>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="badge">{{count($data['selled'])}}</span> Verkaufte Fahrzeuge
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Bezeichnung</th>
                            <th>Fahrgestellnummer</th>
                            <th>E-Datum</th>
                            <th>E-Preis</th>
                            <th>V-Datum</th>
                            <th>V-Preis</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $total = 0;
                            if (count($data['selled'])) {

                            foreach($data['selled'] as $sCar) {
                                $total += $sCar->sale_price;
                                echo    '<tr>' .
                                            '<td>'.sprintf('%04d', $counter++).'</td>' .
                                            '<td>'.$sCar->id.'</td>' .
                                            '<td>'.$sCar->title.'</td>' .
                                            '<td>'.$sCar->chassis_number.'</td>' .
                                            '<td>'.$sCar->purchase_date.'</td>' .
                                            '<td>'.$sCar->purchase_price.'</td>' .
                                            '<td>'.$sCar->sale_date.'</td>' .
                                            '<td>'.$sCar->sale_price.'</td>' .
                                        '</tr>' .
                                        '<tr>'.
                                            '<td></td>'.
                                            '<td colspan="7" style="padding:0">
                                                <table class="table table-condensed" style="margin-bottom:0">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>ID</th>
                                                            <th>Bezeichnung</th>
                                                            <th>Betrag</th>
                                                            <th>Datum</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>';
                                if (count($sCar->invoices)) {
                                    foreach ($sCar->invoices()->orderBy('date')->get() AS $invoice) {
                                    echo '<tr>
                                            <td>'.sprintf('%04d', $counter++).'</td>
                                                            <td>'.$invoice->id.'</td>
                                                            <td>'.$invoice->title.'</td>
                                                            <td>'.$invoice->price.'</td>
                                                            <td>'.$invoice->date.'</td>
                                            </tr>';
                                    }
                                } else {
                                    echo '<tr class="info"><td colspan="5"><i class="fa fa-info-circle"></i> Keine Rechnungen/Quittungen</td></tr>';
                                }

                                                   echo '
                                                    </tbody>
                                                </table>
                                            </td>'.
                                        '</tr>';
                            }
                            } else {
                                echo '<tr class="info"><td colspan="8">Keine Daten verfügbar</td></tr>';
                            }
                        @endphp
                        </tbody>
                    </table>
                    <div class="panel-body" style="text-align:right">
                        <span class="badge">Summe: {{\App\Formatter::currency($total)}}</span>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                    <div class="panel-heading">
                        <span class="badge">{{count($data['expenses'])}}</span> Sonstige Ausgaben
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Bezeichnung</th>
                                <th>Betrag</th>
                                <th>Datum</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php

                                $total = 0;
                                if (count($data['expenses'])) {
                                foreach($data['expenses'] as $expense) {
                                    $total += $expense->price;
                                    echo    '<tr>' .
                                                '<td>'.sprintf('%04d', $counter++).'</td>' .
                                                '<td>'.$expense->id.'</td>' .
                                                '<td>'.$expense->title.'</td>' .
                                                '<td>'.$expense->getPrice().'</td>' .
                                                '<td>'.$expense->getDate().'</td>' .
                                            '</tr>';
                                }
                                } else {
                                    echo '<tr class="info"><td colspan="5">Keine Daten verfügbar</td></tr>';
                                }
                            @endphp
                            </tbody>
                        </table>
                        <div class="panel-body" style="text-align:right">
                            <span class="badge">Summe: {{\App\Formatter::currency($total)}}</span>
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
    });
</script>
@endpush
