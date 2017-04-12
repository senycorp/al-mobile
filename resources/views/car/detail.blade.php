@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="badge label-primary">#{{$car->id}}</span> {{$car->title}} <span class="label label-primary">{{$car->chassis_number}}</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-data">
                                <tbody>
                                <tr>
                                    <td>Bezeichnung</td>
                                    <td>{{$car->title}}</td>
                                </tr>
                                @if ($car->mobile_id)
                                    <tr>
                                        <td>Mobile-ID</td>
                                        <td>{{$car->mobile_id}}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>Fahrgestellnummer</td>
                                    <td>{{$car->chassis_number}}</td>
                                </tr>
                                <tr>
                                    <td>Einkaufsdatum</td>
                                    <td>{{$car->getPurchaseDate()}}</td>
                                </tr>
                                <tr>
                                    <td>Einkaufspreis</td>
                                    <td>{{$car->getPurchasePrice()}}</td>
                                </tr>
                                <tr>
                                    <td>Einkaufsbeleg</td>
                                    <td><a href="{{route('expense_detail' , ['id' => $car->getPurchaseInvoice()->id])}}">Einkaufsbeleg öffnen</a></td>
                                </tr>
                                <tr>
                                    <td>Besteuerung</td>
                                    <td>{{($car->getTaxIdentifier())}}</td>
                                </tr>
                                @if ($car->sale_date)
                                    <tr>
                                        <td>Verkaufsdatum</td>
                                        <td>{{$car->getSaleDate()}}</td>
                                    </tr>
                                    <tr>
                                        <td>Verkaufspreis</td>
                                        <td>{{$car->getSalePrice()}}</td>
                                    </tr>
                                    <tr>
                                        <td>Verkaufsbeleg</td>
                                        <td><a href="{{route('expense_detail' , ['id' => $car->getSaleInvoice()->id])}}">Verkaufsbeleg öffnen</a></td>
                                    </tr>
                                    <tr>
                                        <td>Verkauft nach</td>
                                        <td>{{\App\Formatter::dateDifference($car->purchase_date, $car->sale_date)}}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Bilanz
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-data">
                                <caption>Ausgaben</caption>
                                <tbody>
                                <tr>
                                    <td>Einkaufspreis</td>
                                    <td>{{$car->getPurchasePrice()}}</td>
                                </tr>
                                <tr>
                                    <td>Gesamtaufwand</td>
                                    <td>
                                        {{$car->getTotalExpense()}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Gesamtkosten</td>
                                    <td>{{$car->getTotalCosts()}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <hr/>
                            <table class="table table-striped table-data">
                                <caption>Einnahmen</caption>
                                <tbody>
                                <tr>
                                    <td>Verkaufspreis</td>
                                    <td>
                                        @if ($car->sale_price)
                                            {{$car->getSalePrice()}}
                                        @else
                                            <span class="label label-info">Verkauf steht aus</span>
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-body">
                            <span>mit Aufwendungen: {{$car->getCostsWithExpenses()}}</span>
                            <span class="pull-right">ohne Aufwendungen: {{$car->getCostsWithoutExpenses()}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Informationen @if ($car->mobile_id)<span class="pull-right" id="loader"><i class="fa fa-spinner fa-spin"></i> Lade Inserat...@endif</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-data" id="information_table">
                                <tbody>
                                <tr>
                                    <td>Erstellt von</td>
                                    <td><i class="fa fa-user-circle"></i> {{$car->creator->name}}</td>
                                </tr>
                                <tr>
                                    <td>Erstellt am</td>
                                    <td>{{\App\Formatter::date($car->created_at)}}</td>
                                </tr>
                                <tr>
                                    <td>Aktualisiert am</td>
                                    <td>{{\App\Formatter::date($car->updated_at)}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="panel-body">
                                <div class="alert alert-danger" role="alert">
                                    <span class="sr-only">Error:</span>
                                    <h4><i class="fa fa-warning"></i> Probleme bei <b>{{count($car->getConflicts())}}</b> Rechnungen</h4>

                                    <ul>
                                        @foreach ($car->getConflicts() as $conflict)
                                            <li><a href="{{route('expense_detail', ['id' => $conflict->id])}}">{{ $conflict->title }} {{$conflict->date}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <hr/>
                            <a href="{{route('car_delete', ['id' => $car->id])}}" class="btn btn-block btn-danger"><i class="fa fa-trash"></i> Auto löschen</a>
                            @if ($car->sale_date)
                                <a href="{{route('car_unsell', ['id' => $car->id])}}" class="btn btn-block btn-danger"><i class="fa fa-ban"></i> Verkauf entfernen</a>
                            @endif
                            @if ($car->mobile_id)
                                <a href="http://suchen.mobile.de/fahrzeuge/details.html?id={{$car->mobile_id}}" class="btn btn-block btn-primary"><i class="fa fa-legal"></i> Mobile.de</a>
                            @endif
                            @if ($car->isSelled())
                                <a href="{{route('car_invoice', ['id' => $car->id])}}" class="btn btn-block btn-primary"><i class="fa fa-file-text-o"></i> Rechnung erstellen</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Rechnung/Quittung hinzufügen
                        </div>

                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST"
                                  action="{{ route('car_create_invoice', ['id' => $car->id]) }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('invoice_title') ? ' has-error' : '' }}">
                                    <label for="invoice_title" class="col-md-4 control-label">Bezeichnung</label>

                                    <div class="col-md-6">
                                        <input id="invoice_title" type="text" class="form-control" name="invoice_title"
                                               value="{{ old('invoice_title') }}" required autofocus>

                                        @if ($errors->has('invoice_title'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('invoice_title') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('invoice_price') ? ' has-error' : '' }}">
                                    <label for="invoice_price" class="col-md-4 control-label">Betrag</label>

                                    <div class="col-md-6">
                                        <input id="invoice_price" type="number" class="form-control" name="invoice_price"
                                               value="{{ old('invoice_price') }}" required autofocus>

                                        @if ($errors->has('invoice_price'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('invoice_price') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('invoice_date') ? ' has-error' : '' }}">
                                    <label for="invoice_date" class="col-md-4 control-label">Datum</label>

                                    <div class="col-md-6">
                                        <input id="invoice_date" type="text" class="form-control" name="invoice_date" readonly
                                               value="{{ old('invoice_date') }}" required autofocus>

                                        @if ($errors->has('invoice_date'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('invoice_date') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('invoice_tax') ? ' has-error' : '' }}">
                                    <label for="invoice_tax" class="col-md-4 control-label">Besteuerung</label>
                                    <div class="checkbox col-md-6">
                                        <label>
                                            <input type="radio" id="p25" name="invoice_tax" checked="checked" value="1"> §25a
                                        </label>
                                        <label>
                                            <input type="radio" id="p19" name="invoice_tax" value="0"> 19% MwSt.
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('invoice_account') ? ' has-error' : '' }}">
                                    <label for="tax" class="col-md-4 control-label">Bankkonto</label>
                                    <div class="checkbox col-md-6">
                                        <label>
                                            <input type="radio" name="invoice_account" checked="checked" value="1"> Ja
                                        </label>
                                        <label>
                                            <input type="radio" name="invoice_account" value="0" checked="checked"> Nein
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('invoice_description') ? ' has-error' : '' }}">
                                    <label for="invoice_description" class="col-md-4 control-label">Beschreibung</label>

                                    <div class="col-md-6">
                                        <textarea id="invoice_description" name="invoice_description" class="form-control">{{old('invoice_description')}}</textarea>

                                        @if ($errors->has('invoice_description'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('invoice_description') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="glyphicon glyphicon-plus-sign"></i> Erstellen
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Rechnungen
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped vertical" id="car_invoice_table">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Bezeichnung</th>
                                        <th>Betrag</th>
                                        <th>Datum</th>
                                        <th>Aktionen</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <td></td>
                                    <td></td>
                                    <td class="no-search"></td>
                                    <td class="no-search"></td>
                                    <td class="no-search"></td>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if (!$car->sale_date)
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Auto verkaufen
                            </div>

                            <div class="panel-body">
                                <form class="form-horizontal" role="form" method="POST"
                                      action="{{ route('car_sell', ['id' => $car->id]) }}">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('sale_date') ? ' has-error' : '' }}">
                                        <label for="sale_date" class="col-md-4 control-label">Datum</label>

                                        <div class="col-md-6">
                                            <input id="sale_date" type="text" class="form-control" name="sale_date"
                                                   value="{{ old('sale_date') }}" readonly required autofocus>

                                            @if ($errors->has('sale_date'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('sale_date') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('sale_price') ? ' has-error' : '' }}">
                                        <label for="sale_price" class="col-md-4 control-label">Preis</label>

                                        <div class="col-md-6">
                                            <input id="sale_price" type="number" class="form-control" name="sale_price"
                                                   value="{{ old('sale_price') }}" required autofocus>

                                            @if ($errors->has('sale_price'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('sale_price') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('account') ? ' has-error' : '' }}">
                                        <label for="tax" class="col-md-4 control-label">Bankkonto</label>
                                        <div class="checkbox col-md-6">
                                            <label>
                                                <input type="radio" name="account" checked="checked" value="1"> Ja
                                            </label>
                                            <label>
                                                <input type="radio" name="account" value="0" checked="checked"> Nein
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="glyphicon glyphicon-plus-sign"></i> Verkaufen
                                            </button>
                                        </div>
                                    </div>
                                </form>
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
        $('#car_invoice_table').DataTable({
            processing: true,
            serverSide: true,
            //responsive: true,
            fixedHeader: true,
            stateSave: true,
            language: {
                "sEmptyTable":      "Keine Daten in der Tabelle vorhanden",
                "sInfo":            "_START_ bis _END_ von _TOTAL_ Einträgen",
                "sInfoEmpty":       "0 bis 0 von 0 Einträgen",
                "sInfoFiltered":    "(gefiltert von _MAX_ Einträgen)",
                "sInfoPostFix":     "",
                "sInfoThousands":   ".",
                "sLengthMenu":      "_MENU_ Einträge anzeigen",
                "sLoadingRecords":  "Wird geladen...",
                "sProcessing":      "Bitte warten...",
                "sSearch":          "Suchen",
                "sZeroRecords":     "Keine Einträge vorhanden.",
                "oPaginate": {
                    "sFirst":       "Erste",
                    "sPrevious":    "Zurück",
                    "sNext":        "Nächste",
                    "sLast":        "Letzte"
                },
                "oAria": {
                    "sSortAscending":  ": aktivieren, um Spalte aufsteigend zu sortieren",
                    "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
                }
            },
            ajax: '{!! route('car_invoice_data', ['id' => $car->id]) !!}',
            columns: [
                { data: 'id', name: 'invoices.id', width: '25px' },
                { data: 'title', name: 'title', render: function() {
                    return (arguments[2]['title']) ? arguments[2]['title'] : arguments[2]['ititle']
                }},
                { data: 'price', name: 'price', render: function(value) {
                    return indicatedCurrency(value)
                } },
                { data: 'date', name: 'date', render: function(value) {
                    return date(value);
                } },
                {
                    data: 'id',
                    name: 'id',
                    render: function() {
                        return '<a href="/invoice/'+arguments[2]['id']+'/delete" class="btn btn-danger"><i class="fa fa-trash"></i></a> &nbsp;'+
                            '<a href="/expense/'+arguments[2]['id']+'" class="btn btn-primary"><i class="fa fa-arrow-right"></i></a>';

                    },
                    orderable: false,
                    searchable: false
                }
            ],
            dom: "<'row'<'col-sm-6'lB><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                'colvis','copy', 'excel', 'pdf', 'csv'
            ],
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    if (!$(column.footer()).empty().hasClass('no-search')) {
                        $('<input class="form-control input-sm" style="width: 100%" />').appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                column.search(val ? val : '', true, false).draw();
                            });
                    }
                });
            }
        });

        $('#invoice_date').datepicker({
            language: 'de',
            minDate: new Date('{{$car->purchase_date}}'),
            //maxDate: @if ($car->sale_date) new Date('{{$car->sale_date}}') @else new Date() @endif,
        });

        $('#sale_date').datepicker({
            language: 'de',
            minDate: new Date('{{$car->purchase_date}}'),
            maxDate: new Date(),
        });

        $('#invoice_title').selectize({
            persist: false,
            maxItems: 1,
            searchField: ['title'],
            options: {!! collect(DB::select('select id, title, tax from invoice_types'))->toJson() !!},
            valueField: 'id',
            labelField: 'title',
            create: function(input) {
                return {
                    id: input,
                    title: input
                }
            },
            onChange: function(value) {
                var propertyNames = Object.getOwnPropertyNames(this.options);

                for (var i = 0 ; i < propertyNames.length; i++) {
                    var option = this.options[propertyNames[i]];
                    if (option['id'] == value) {
                        $('#p25, #p19').removeAttr('checked', '')
                        if (option['tax'] == 1) {
                            $('#p25').attr('checked', 'checked')
                        } else {
                            $('#p19').attr('checked', 'checked')
                        }

                        return;
                    }
                }
            }
        });

        @if ($car->mobile_id)
        axios.get('{{route('car_auction_data', ['id' => $car->id])}}').then(function(response) {
            $('#loader').hide();
            $('#information_table tbody').prepend(
                '<tr><td colspan="2" style="padding:0;"><img src="'+response.data.image+'" style="width:100%"/></td></tr>' +
                '<tr><td>Bezeichnung</td><td>'+response.data.title+'</td></tr>' +
                '<tr><td>Preis</td><td>'+response.data.brutto_price+'</td></tr>'
            )
        })
        @endif
    });
</script>
@endpush