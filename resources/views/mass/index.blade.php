@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Auto erstellen</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('mass_car') }}">
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
                                    <code>http://suchen.mobile.de/<br/>fahrzeuge/details.html?id={INSERAT_ID}</code>
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
                                <input id="purchase_price" step="0.01" type="number" class="form-control" name="purchase_price" value="{{ old('purchase_price') }}" required autofocus>

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
                                    <i class="glyphicon glyphicon-plus-sign"></i> Erstellen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Rechnung erstellen</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" id="expenseForm" method="POST" action="{{ route('mass_invoice') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('expense_title') ? ' has-error' : '' }}">
                            <label for="expense_title" class="col-md-4 control-label">Bezeichnung</label>

                            <div class="col-md-6">
                                <input id="expense_title" type="text" class="form-control" name="expense_title" value="{{ old('expense_title') }}" required autofocus>

                                @if ($errors->has('expense_title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('expense_title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('expense_car') ? ' has-error' : '' }}">
                            <label for="expense_car" class="col-md-4 control-label">Auto</label>

                            <div class="col-md-6">
                                <select id="expense_car" class="form-control" name="expense_car">
                                    <option value=""><i>-</i></option>
                                    @foreach (\App\Car::all() as $car)
                                        <option value="{{$car->id}}">{{$car->title}} [{{$car->chassis_number}}]</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('expense_car'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('expense_car') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('expense_in_out') ? ' has-error' : '' }}">
                            <label for="tax" class="col-md-4 control-label">Einnahme/Ausgabe</label>
                            <div class="checkbox col-md-6">
                                <label>
                                    <input type="radio" name="expense_in_out" checked="checked" value="in"> Einnahme
                                </label>
                                <label>
                                    <input type="radio" name="expense_in_out" value="out"> Ausgabe
                                </label>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('expense_price') ? ' has-error' : '' }}">
                            <label for="expense_price" class="col-md-4 control-label">Betrag</label>

                            <div class="col-md-6">
                                <input id="expense_price" type="number" step="0.01" min="0" class="form-control" name="expense_price" value="{{ old('expense_price') }}" required autofocus>

                                @if ($errors->has('expense_price'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('expense_price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('expense_date') ? ' has-error' : '' }}">
                            <label for="expense_date" class="col-md-4 control-label">Datum</label>

                            <div class="col-md-6">
                                <input id="expense_date" type="text" class="form-control" name="expense_date" value="{{ old('expense_date') }}" onchange="$(this).val('')" required autofocus>

                                @if ($errors->has('expense_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('expense_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('expense_tax') ? ' has-error' : '' }}">
                            <label for="expense_tax" class="col-md-4 control-label">Besteuerung</label>
                            <div class="checkbox col-md-6">
                                <label>
                                    <input type="radio" name="expense_tax" id="expense_p25" checked="checked" value="1"> §25a
                                </label>
                                <label>
                                    <input type="radio" name="expense_tax" id="expense_p19" value="0"> 19% MwSt.
                                </label>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('expense_account') ? ' has-error' : '' }}">
                            <label for="expense_account" class="col-md-4 control-label">Bankkonto</label>
                            <div class="checkbox col-md-6">
                                <label>
                                    <input type="radio" name="expense_account" checked="checked" value="1"> Ja
                                </label>
                                <label>
                                    <input type="radio" name="expense_account" value="0" checked="checked"> Nein
                                </label>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('expense_description') ? ' has-error' : '' }}">
                            <label for="expense_description" class="col-md-4 control-label">Beschreibung</label>

                            <div class="col-md-6">
                                <textarea id="expense_description" class="form-control" name="expense_description" value="{{ old('expense_description') }}"></textarea>

                                @if ($errors->has('expense_description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('expense_description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="glyphicon glyphicon-plus-sign"></i> Rechnung erstellen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(function() {
        $('#expense_date').datepicker({
            language: 'de',
            maxDate: new Date(),
        });

        $('#expense_car').selectize({});

        $('#expense_title').selectize({
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
                        $('#expense_p25, #expense_p19').removeAttr('checked', '')
                        if (option['tax'] == 1) {
                            $('#expense_p25').attr('checked', 'checked')
                        } else {
                            $('#expense_p19').attr('checked', 'checked')
                        }

                        return;
                    }
                }
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
