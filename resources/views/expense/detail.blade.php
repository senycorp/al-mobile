@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="badge label-primary">#{{$expense->id}}</span> {{$expense->title}}
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-data">
                                <tbody>
                                    <tr>
                                        <td>Bezeichnung</td>
                                        <td>{{$expense->title}}</td>
                                    </tr>
                                    <tr>
                                        <td>Betrag</td>
                                        <td>{{$expense->getPrice()}}</td>
                                    </tr>
                                    <tr>
                                        <td>Datum</td>
                                        <td>{{$expense->getDate()}}</td>
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
                                    <td>{{$expense->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Aktualisiert am</td>
                                    <td>{{$expense->updated_at}}</td>
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
                            <a class="btn btn-block btn-danger" href="{{route('expense_delete', ['id' => $expense->id])}}"><i class="fa fa-trash"></i> Rechnung löschen</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Rechnung/Quittung aktualisieren</div>

                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="{{ route('expense_update', ['id' => $expense->id]) }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label for="title" class="col-md-4 control-label">Bezeichnung</label>

                                    <div class="col-md-6">
                                        <input id="title" type="text" class="form-control" name="title" value="{{ $expense->title }}" required autofocus>

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
                                        <input id="price" type="number" class="form-control" name="price" value="{{ $expense->price }}" required autofocus>

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
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(function() {
        $('#date').datepicker({
            language: 'de',
            @if ($expense->car && $expense->car->purchase_date) minDate:  new Date('{{$expense->car->purchase_date}}') @endif,
            @if ($expense->car && $expense->car->sale_date) maxDate: new Date('{{$expense->car->sale_date}}') @else maxDate: new Date() @endif,
        });
    });
</script>
@endpush