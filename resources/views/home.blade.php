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
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Jahresbilanz - Ausgaben
                </div>
                <div class="panel-body">
                    <canvas id="chart_out" width="100" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Jahresbilanz - Einnahmen und Ausgaben
                </div>
                <div class="panel-body">
                    <canvas id="chart_in" width="100" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Letztes Jahr - Einnahmen und Ausgaben
                </div>
                <div class="panel-body">
                    <canvas id="chart_in_last_year" width="100" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Entwicklung - Einnahmen und Ausgaben
                </div>
                <div class="panel-body">
                    <canvas id="chart_development_in" width="100" height="100"></canvas>
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
        var chart_out = new Chart(document.getElementById('chart_out'),{
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

        var chart_in_out = new Chart(document.getElementById('chart_in'),{
            type: 'pie',
            data: {
                labels: [
                    "Autokauf",
                    "Autoverkauf",
                ],
                datasets: [
                    {
                        data: [
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE());')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE());')[0]->t}},
                        ],
                        backgroundColor: [
                            "#FF6384",
                            "#36A2EB",
                        ],
                        hoverBackgroundColor: [
                            "#FF6384",
                            "#36A2EB",
                        ]
                    }]
            },
            options: {
                responsive: true
            }
        });

        var chart_in_out_lastyear = new Chart(document.getElementById('chart_in_last_year'),{
            type: 'pie',
            data: {
                labels: [
                    "Autokauf",
                    "Autoverkauf",
                ],
                datasets: [
                    {
                        data: [
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE())-1;')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE())-1;')[0]->t}},
                        ],
                        backgroundColor: [
                            "#FF6384",
                            "#36A2EB",
                        ],
                        hoverBackgroundColor: [
                            "#FF6384",
                            "#36A2EB",
                        ]
                    }]
            },
            options: {
                responsive: true
            }
        });

        var chart_development_in = new Chart(document.getElementById('chart_development_in'),{
            type: 'line',
            data: {
                labels: ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"],
                datasets: [
                    {
                        label: "Einnahmen",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(75,192,192,0.4)",
                        borderColor: "rgba(75,192,192,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(75,192,192,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(75,192,192,1)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: [
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE()) AND MONTH(sale_date) = 1;')[0]->t}},
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE()) AND MONTH(sale_date) = 2;')[0]->t}},
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE()) AND MONTH(sale_date) = 3;')[0]->t}},
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE()) AND MONTH(sale_date) = 4;')[0]->t}},
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE()) AND MONTH(sale_date) = 5;')[0]->t}},
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE()) AND MONTH(sale_date) = 6;')[0]->t}},
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE()) AND MONTH(sale_date) = 7;')[0]->t}},
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE()) AND MONTH(sale_date) = 8;')[0]->t}},
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE()) AND MONTH(sale_date) = 9;')[0]->t}},
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE()) AND MONTH(sale_date) = 10;')[0]->t}},
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE()) AND MONTH(sale_date) = 11;')[0]->t}},
                            {{DB::select('SELECT sum(sale_price) AS t FROM cars WHERE YEAR(sale_date) = YEAR(CURDATE()) AND MONTH(sale_date) = 12;')[0]->t}},
                        ],
                        spanGaps: false,
                    },
                    {
                        label: "Ausgaben",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "#FF6384",
                        borderColor: "#FF6399",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "#FF6399",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "#FF6399",
                        pointHoverBorderColor: "#FF6399",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: [
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE()) AND MONTH(purchase_date) = 1;')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE()) AND MONTH(purchase_date) = 2;')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE()) AND MONTH(purchase_date) = 3;')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE()) AND MONTH(purchase_date) = 4;')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE()) AND MONTH(purchase_date) = 5;')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE()) AND MONTH(purchase_date) = 6;')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE()) AND MONTH(purchase_date) = 7;')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE()) AND MONTH(purchase_date) = 8;')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE()) AND MONTH(purchase_date) = 9;')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE()) AND MONTH(purchase_date) = 10;')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE()) AND MONTH(purchase_date) = 11;')[0]->t}},
                            {{DB::select('SELECT sum(purchase_price) AS t FROM cars WHERE YEAR(purchase_date) = YEAR(CURDATE()) AND MONTH(purchase_date) = 12;')[0]->t}},
                        ],
                        spanGaps: false,
                    }
                ]
            },
            options: {
                responsive: true
            }
        });
    })
</script>
@endpush

