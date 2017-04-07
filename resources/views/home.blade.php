@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Hallo {{Auth::user()->name}}</div>

                <div class="panel-body">
                    Willkommen bei der Buchhaltung von Al-Mobile
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Datenbestand</div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>Autos</td>
                                    <td>{{\App\Car::count()}}</td>
                                </tr>
                                <tr>
                                    <td>Aufwändungen</td>
                                    <td>{{\App\Invoice::count()}}</td>
                                </tr>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
