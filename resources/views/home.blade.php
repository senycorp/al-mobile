@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="alert alert-danger">
                <h4><i class="fa fa-warning"></i> Wichtige Informationen</h4>
                Diese Applikation befindet sich in aktiver Entwicklung und dient lediglich zu Demonstrationszwecken. Von einem produktivem Einsatz sollte daher dringlichst
                abgesehen werden.
                <hr/>
                <a href="https://github.com/senycorp/al-mobile"><i class="fa fa-github"></i> GitHub</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Hallo {{Auth::user()->name}}</div>

                <div class="panel-body">
                    Willkommen bei der Buchhaltung von Al-Mobile
                </div>
            </div>
        </div>
        <div class="col-md-8 col-md-offset-2">
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
            <div class="panel panel-default">
                <div class="panel-heading">
                    Jahresbilanz
                </div>
                <div class="panel-body">
                    <canvas id="myChart" width="100" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(function () {
        // For a pie chart
        var myPieChart = new Chart(document.getElementById('myChart'),{
            type: 'pie',
            data: {
                labels: [
                    "Autokauf",
                    "Ausgaben (Autobezogen)",
                    "Sonstige Ausgaben"
                ],
                datasets: [
                    {
                        data: [
                            //{{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE());')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE());')[0]->t}},
                            {{DB::select('SELECT sum(price) AS t FROM invoices WHERE car_id IS NOT NULL AND YEAR(date) = YEAR(CURDATE());')[0]->t}},
                            {{DB::select('SELECT sum(price) AS t FROM invoices WHERE car_id IS NULL AND YEAR(date) = YEAR(CURDATE());')[0]->t}},
                        ],
                        backgroundColor: [
                            "#FF6384",
                            "#36A2EB",
                            "#FFCE56"
                        ],
                        hoverBackgroundColor: [
                            "#FF6384",
                            "#36A2EB",
                            "#FFCE56"
                        ]
                    }]
            },
            options: {
                responsive: true
            }
        });
    })
</script>
@endpush

