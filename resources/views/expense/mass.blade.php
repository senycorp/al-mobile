@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @if (isset($data))
        <div class="col-md-12">
            <div class="alert alert-info">
                <h4><i class="fa fa-info-circle"></i> Informationen</h4>
                Es wurden insgesamt {{count($data)}} Rechnung(en) erstellt:

                @if (count($data))
                    <ul>
                        @foreach($data as $invoice)
                            <li><a href="{{route('expense_detail', ['id' => $invoice->id])}}">{{$invoice->getTitle()}}</a></li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        @endif
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Massenerstellung</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" id="expenseForm" method="POST" action="{{ route('expense_create') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-4 control-label">Bezeichnung</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" required autofocus>

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                            <label for="price" class="col-md-4 control-label">Betrag</label>

                            <div class="col-md-6">
                                <input id="price" type="number" class="form-control" name="price" value="{{ old('price') }}" required autofocus>

                                @if ($errors->has('price'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                            <label for="date" class="col-md-4 control-label">Datum</label>

                            <div class="col-md-6">
                                <input id="date" type="text" class="form-control" name="date" value="{{ old('date') }}" readonly required autofocus>

                                @if ($errors->has('date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('tax') ? ' has-error' : '' }}">
                            <label for="tax" class="col-md-4 control-label">Besteuerung</label>
                            <div class="checkbox col-md-6">
                                <label>
                                    <input type="radio" name="tax" id="p25" checked="checked" value="1"> §25a
                                </label>
                                <label>
                                    <input type="radio" name="tax" id="p19" value="0"> 19% MwSt.
                                </label>
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

                        <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-4 control-label">Beschreibung</label>

                            <div class="col-md-6">
                                <textarea id="description" class="form-control" name="description" value="{{ old('description') }}"></textarea>

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="button" class="btn btn-success btn-block" id="addExpense">
                                    <i class="glyphicon glyphicon-plus-sign"></i> Hinzufügen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Rechnungen/Quittungen</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped vertical" id="car_table">
                            <thead>
                            <tr>
                                <th>Bezeichnung</th>
                                <th>Betrag</th>
                                <th>Datum</th>
                                <th>Besteuerung</th>
                                <th>Bankkonto</th>
                                <th>Beschreibung</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tfoot>
                                <td class="no-search"></td>
                                <td class="no-search"></td>
                                <td class="no-search"></td>
                                <td class="no-search"></td>
                                <td class="no-search"></td>
                                <td class="no-search"></td>
                                <td class="no-search"></td>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <form id="dataForm" method="POST" action="{{route('expense_mass_create')}}">{{ csrf_field() }}</form>
            <button class="btn btn-block btn-primary" id="createInvoice"><i class="fa fa-plus-circle"></i> Rechnungen erstellen</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(function() {

        $('#addExpense').click(function() {
            var data={
                title: '',
                date: '2012-12-12',
                price: 0.0,
                tax: 0,
                account:0,
                description: '',
            };

            $.each($('#expenseForm').serializeArray(), function() {data[this.name] = this.value});

            expenseTable.row.add(data).draw();

            $('#expenseForm')[0].reset();
            $('#title')[0].selectize.clear()
        });

        var expenseTable = $('#car_table').DataTable({
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
            columns: [
                { data: 'title', name: 'title', render: function (value) {
                    if ($.isNumeric(value)) {
                        return $('#title')[0].selectize.options[parseInt(value)].title;
                    }

                    return value;
                }},
                { data: 'price', name: 'price', render: function(value) {
                    return indicatedCurrency(value);
                } },
                { data: 'date', name: 'date' , render: function(value) {
                    return (new Date(value)).toLocaleDateString('de');
                }},
                { data: 'tax', name: 'tax' , render: function(value) {
                    return (parseInt(value)) ? '§25a' : '19%';
                }},
                { data: 'account', name: 'account' , render: function(value) {
                    return (parseInt(value)) ? 'Ja' : 'Nein';
                }},
                { data: 'description', name: 'description' , render: function(value) {
                    return value;
                }},
                {
                    data: 'title',
                    name: 'title',
                    render: function() {
                        return '<a href="javascript:void(0)" class="btn btn-danger delete_invoice"><i class="fa fa-trash"></i></a>';
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

        $('#createInvoice').click(function() {
            $('#dataForm').append('<textarea name="data" style="display:none">'+JSON.stringify($('#car_table').dataTable().fnGetData())+'</textarea>');

            $('#dataForm').submit()
        });

        $('#car_table tbody').on( 'click', 'a.delete_invoice', function () {
            expenseTable
                .row( $(this).parents('tr') )
                .remove()
                .draw();
        } );

        $('#date').datepicker({
            language: 'de',
            maxDate: new Date(),
        });

        $('#title').selectize({
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
    });
</script>
@endpush
