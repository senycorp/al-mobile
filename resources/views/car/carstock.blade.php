@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Auto erstellen</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('car_create') }}">
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

                        <div class="form-group{{ $errors->has('mobile_id') ? ' has-error' : '' }}">
                            <label for="mobile_id" class="col-md-4 control-label">Mobile-ID</label>

                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <h4><i class="fa fa-info-circle"></i> Information</h4>
                                    Die Mobile-ID kann dazu genutzt werden vorab existierende Inserate abzugreifen und zu verwenden. Dazu muss lediglich die ID des
                                    Inserats angegeben werden:
                                    <br/>
                                    <code>http://suchen.mobile.de/fahrzeuge/details.html?id={INSERAT_ID}</code>
                                </div>
                                <div class="input-group">
                                    <input id="mobile_id" type="text" class="form-control" name="mobile_id" value="{{ old('mobile_id') }}" autofocus>
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="button" id="loadMobile">
                                            <span id="loader" style="display:none;"><i class="fa fa-spin fa-spinner"></i></span>&nbsp;
                                            Lade Daten</button>
                                    </span>
                                </div>
                                @if ($errors->has('mobile_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mobile_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('chassis_number') ? ' has-error' : '' }}">
                            <label for="chassis_number" class="col-md-4 control-label">Fahrgestellnummer</label>

                            <div class="col-md-6">
                                <input id="chassis_number" type="text" class="form-control" name="chassis_number" value="{{ old('chassis_number') }}" required autofocus>

                                @if ($errors->has('chassis_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('chassis_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('purchase_date') ? ' has-error' : '' }}">
                            <label for="purchase_price" class="col-md-4 control-label">Einkaufsdatum</label>

                            <div class="col-md-6">
                                <input id="purchase_date" type="text" class="form-control" name="purchase_date" value="{{ old('purchase_date') }}" readonly required autofocus>

                                @if ($errors->has('purchase_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('purchase_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('purchase_price') ? ' has-error' : '' }}">
                            <label for="purchase_price" class="col-md-4 control-label">Einkaufspreis</label>

                            <div class="col-md-6">
                                <input id="purchase_price" type="number" class="form-control" name="purchase_price" value="{{ old('purchase_price') }}" required autofocus>

                                @if ($errors->has('purchase_price'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('purchase_price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('tax') ? ' has-error' : '' }}">
                            <label for="tax" class="col-md-4 control-label">Besteuerung</label>
                            <div class="checkbox col-md-6">
                                <label>
                                    <input type="radio" name="tax" checked="checked" value="1"> §25a
                                </label>
                                <label>
                                    <input type="radio" name="tax" value="0"> 19% MwSt.
                                </label>
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
        </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Bestand</div>
                <div class="panel-body" style="overflow-x: hidden">
                    <div class="table-responsive">
                        <table class="table table-striped vertical" id="car_table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bezeichnung</th>
                                <th>Fahrgestellnummer</th>
                                <th>E-Datum</th>
                                <th>E-Preis</th>
                                <th>Aktionen</th>
                            </tr>
                            </thead>
                            <tfoot>
                                <td></td>
                                <td class="no-search"></td>
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
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(function() {
        $('#car_table').DataTable({
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
            ajax: '{!! route('car_data_stock') !!}',
            columns: [
                { data: 'id', name: 'id', width: '25px' },
                { data: 'title', name: 'title' },
                { data: 'chassis_number', name: 'chassis_number' },
                { data: 'purchase_date', name: 'purchase_date' },
                { data: 'purchase_price', name: 'purchase_price' },
                {
                    data: 'id',
                    name: 'id',
                    render: function() {
                        return '<a href="/car/'+arguments[2]['id']+'" class="btn btn-primary"><i class="fa fa-arrow-right"></i></a>';
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

        $('#purchase_date').datepicker({
            language: 'de',
            maxDate: new Date(),
        });

        $('#loadMobile').click(function(){
            $('#loader').show();
            if ($('#mobile_id').val()) {
                axios.get('/mobile/' + $('#mobile_id').val()).then(function (response) {
                    $('#title').val(response.data.title);
                    $('#loader').hide();
                }).catch(function() {
                    $('#loader').hide();
                    alert("Die ID liefert keine Ergebnisse.");
                })
            } else {
                $('#loader').hide();
                alert('Es wurde keine ID angegeben.');
            }
        })
    });
</script>
@endpush
