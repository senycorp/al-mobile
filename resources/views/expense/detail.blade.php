@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="badge label-primary">#{{$expense->id}}</span> {{$expense->getTitle()}}
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-data">
                                <tbody>
                                    <tr>
                                        <td>Bezeichnung</td>
                                        <td>{{$expense->getTitle()}}</td>
                                    </tr>
                                    <tr>
                                        <td>Betrag</td>
                                        <td>{{$expense->getPrice()}}</td>
                                    </tr>
                                    <tr>
                                        <td>Datum</td>
                                        <td>{{$expense->getDate()}}</td>
                                    </tr>
                                    <tr>
                                        <td>§25a</td>
                                        <td>{{($expense->is25())}}</td>
                                    </tr>
                                    <tr>
                                        <td>Bankkonto</td>
                                        <td>{{($expense->isAccount())}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Beschreibung</div>
                        <div class="panel-body">
                            {{$expense->description}}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Informationen
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-data">
                                <tbody>
                                <tr>
                                    <td>Erstellt von</td>
                                    <td><i class="fa fa-user-circle"></i> {{$expense->creator->name}}</td>
                                </tr>
                                <tr>
                                    <td>Erstellt am</td>
                                    <td>{{\App\Formatter::date($expense->created_at)}}</td>
                                </tr>
                                <tr>
                                    <td>Aktualisiert am</td>
                                    <td>{{\App\Formatter::date($expense->updated_at)}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="panel-body">
                            @if ($expense->hasConflict())
                                <div class="alert alert-danger" role="alert">
                                    <span class="sr-only">Error:</span>
                                    <h4><i class="fa fa-warning"></i> Bitte korrigieren</h4>
                                        Datum der Quittung muss zwischen dem <b>{{$expense->car->getPurchaseDate()}}</b> und <b>{{$expense->car->getSaleDate()}}</b> liegen. Deshalb ist
                                    <b>{{$expense->getDate()}}</b> kein valides Datum.
                                </div>
                                <hr/>
                            @endif
                            @if($expense->car_id)
                            <a class="btn btn-block btn-primary" href="{{route('car_detail', ['id' => $expense->car_id])}}"><i class="fa fa-car"></i> Zum Auto</a>
                            @endif

                            @if (!$expense->purchase_invoice)
                            <a class="btn btn-block btn-danger" href="{{route('expense_delete', ['id' => $expense->id])}}"><i class="fa fa-trash"></i> Rechnung löschen</a>
                            @endif

                            @if ($expense->price > 0)
                                @if ($expense->sale_invoice)
                                    <a class="btn btn-block btn-primary" href="{{route('car_invoice', ['id' => $expense->car_id])}}"><i class="fa fa-file-text-o"></i> Rechnung erstellen</a>
                                @else
                                    <a class="btn btn-block btn-primary" href="{{route('expense_invoice', ['id' => $expense->id])}}"><i class="fa fa-file-text-o"></i> Rechnung erstellen</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                @if ($expense->purchase_invoice || $expense->sale_invoice )
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h4><i class="fa fa-info"></i> Information</h4>
                            Diese Rechnung kann nicht bearbeitet werden, da es ein Einkaufs/Verkaufs-Beleg für das Fahrzeug <strong>{{$expense->car->title}}</strong> ist.
                        </div>
                    </div>
                @else
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Rechnung/Quittung aktualisieren</div>

                            <div class="panel-body">
                                <form class="form-horizontal" role="form" method="POST" action="{{ route('expense_update', ['id' => $expense->id]) }}">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        <label for="title" class="col-md-4 control-label">Bezeichnung</label>

                                        <div class="col-md-6">
                                            <input id="title" type="text" class="form-control" name="title" value="{{$expense->title ? $expense->getTitle() : $expense->invoice_type_id}}" required autofocus>

                                            @if ($errors->has('title'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('in_out') ? ' has-error' : '' }}">
                                        <label for="tax" class="col-md-4 control-label">Einnahme/Ausgabe</label>
                                        <div class="checkbox col-md-6">
                                            <label>
                                                <input type="radio" name="in_out" @if($expense->price >= 0)checked="checked" @endif value="in"> Einnahme
                                            </label>
                                            <label>
                                                <input type="radio" name="in_out" @if($expense->price < 0)checked="checked" @endif value="out"> Ausgabe
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                                        <label for="price" class="col-md-4 control-label">Betrag</label>

                                        <div class="col-md-6">
                                            <input id="price" step="0.01" min="0" type="number" class="form-control" name="price" value="{{ abs($expense->price) }}" required autofocus>

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
                                            <input id="date"
                                                   type="text"
                                                   class="form-control"
                                                   name="date"
                                                   value="{{ $expense->getDate() }}"
                                                   readonly
                                                   required autofocus>

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
                                            <textarea id="description" class="form-control" name="description">{{ $expense->description }}</textarea>

                                            @if ($errors->has('description'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="glyphicon glyphicon-plus-sign"></i> Aktualisieren
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
        $('#date').datepicker({
            language: 'de',
            @if ($expense->car && $expense->car->purchase_date) minDate:  new Date('{{$expense->car->purchase_date}}'), @endif
            @if ($expense->car && $expense->car->sale_date) maxDate: new Date('{{$expense->car->sale_date}}') @else maxDate: new Date() @endif
        });

        $('#title').selectize({
            persist: false,
            maxItems: 1,
            searchField: ['title'],
            options: {!! collect(DB::select('select id, title, tax from invoice_types'))->prepend(['id' => $expense->title,'title' => $expense->title,'tax' => 0, ])->toJson() !!},
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