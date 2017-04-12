<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title>Rechnung: {{$data['title']}}</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- CUSTOM STYLE  -->
    <link href="{{ asset('invoice.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="print" href="{{asset('css/print.css')}}">
    <link rel="stylesheet" type="text/css" media="print" href="{{asset('css/print/bootstrap-print.css')}}">
    <link rel="stylesheet" type="text/css" media="print" href="{{asset('css/print/bootstrap-print-md.css')}}">
    <!-- GOOGLE FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css' />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div class="container">
    <form id="form">
    <div class="row pad-top-botm ">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <i class="fa fa-car fa-5x"></i>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">

            <strong>AL Automobile</strong>
            <br />
            Emscheralle. 20
            <br />
            44369 Dortmund
            <br />
            Deutschland

        </div>
    </div>
    <div  class="row text-center contact-info">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <hr />
            <span>
                 <strong><i class="fa fa-envelope"></i> </strong>  info@alautomobile.de
             </span>
            <span>
                 <strong><i class="fa fa-phone"></i> </strong>  +49 (0)177 8332330
             </span>
            <span>
                 <strong><i class="fa fa-print"></i> </strong>  +49 (0)231 33481579
             </span>
            <hr />
        </div>
    </div>
    <div  class="row pad-top-botm client-info">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-4">
            <table class="table table-condensed">
                <tbody>
                <tr>
                    <td colspan="2">
                        <h4>Rechnung</h4>
                    </td>
                </tr>
                <tr>
                    <td>Datum</td>
                    <td>{{$data['date']}}</td>
                </tr>
                <tr>
                    <td>Rechnungsnr.:</td>
                    <td>{{$data['invoice_no']}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <input type="text" class="form-control" placeholder="Name" name="buyer_name" value="{{$data['buyer']['name']}}"/>
            <input type="text" class="form-control" placeholder="Straße" name="buyer_street" value="{{$data['buyer']['street']}}"/>
            <input type="text" class="form-control" placeholder="PLZ Ort" name="buyer_location" value="{{$data['buyer']['location']}}"/>
            <input type="text" class="form-control" name="buyer_country" value="{{$data['buyer']['country']}}" />
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Marke / Modell</th>
                        <th>Fahrgestellnummer</th>
                        <th>Erstzulassung</th>
                        <th>km-Stand</th>
                        <th>Farbe</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" class="form-control" name="title" id="title" value="{{$data['title']}}" /></td>
                            <td><input type="text" class="form-control" name="chassis_number" id="chassis_number" value="{{$data['chassis_number']}}" /></td>
                            <td><input type="text" class="form-control" name="first_registration" id="first_registration" value="{{$data['first_registration']}}"/></td>
                            <td><input type="text" class="form-control" name="km" id="km" value="{{$data['km']}}"/></td>
                            <td><input type="text" class="form-control" name="color" id="color" value="{{$data['color']}}"/></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <tbody>
                    <tr>
                        <td>
                            <input type="text"  class="form-control" name="pos_title" id="pos_title" value="{{$data['pos_title']}}" />
                            <textarea class="form-control" id="description" name="description">{{$data['description']}}</textarea>
                            <i>Leistungserbringungsdatum: <input type="text" class="form-control" style="width: 150px; display:inline" name="service_provision_date" id="service_provision_date" value="{{$data['service_provision_date']}}" /></i>
                        </td>
                        <td style="text-align: right">{{\App\Formatter::currency($data['price'])}}</td>
                    </tr>
                    </tbody>
                </table>
                <hr/>
            </div>

            <div class="ttl-amts">
                <h5>  Netto :
                @if(!$data['tax'])
                    {{\App\Formatter::currency( ((float)$data['price']/(1.19)) )}}
                @else
                        {{\App\Formatter::currency($data['price'])}}
                @endif
                </h5>
            </div>
            <hr />
            <div class="ttl-amts">
                <h5>
                    @if($data['tax'])
                        Kein Umsatzsteuerausweis möglich - §25a UStG. Gebrauchtgegenstände-Sonderregelung
                    @else
                        inklusive 19% UStG.: {{\App\Formatter::currency( ($data['price'] - $data['price']/(1.19)) )}}
                    @endif
                </h5>
            </div>
            <hr />
            <div class="ttl-amts">
                <h4> <strong>Gesamtbetrag :
                        {{\App\Formatter::currency($data['price'])}}
                    </strong> </h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <strong>Rechnung fällig nach Erhalt</strong>
        </div>
    </div>
    {{--<div class="row print-hidden">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <strong> Important: </strong>
            <ol>
                <li>
                    This is an electronic generated invoice so doesn't require any signature.

                </li>
                <li>
                    Please read all terms and polices on  www.yourdomaon.com for returns, replacement and other issues.

                </li>
            </ol>
        </div>
    </div>--}}
    <div class="row pad-top-botm print-hidden">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <hr />
            <a href="javascript:void(0)" class="btn btn-primary btn-block btn-lg" id="saveAndPrint" ><i class="fa fa-print"></i> Speichern und Drucken</a>
        </div>
    </div>
    </form>
</div>

<div class="container print-footer">
    <hr/>
    <div class="row">
        <div class="col-md-3">
            <strong>AL Automobile</strong>
            <br />
            Emscheralle. 20
            <br />
            44369 Dortmund
            <br />
            Deutschland
        </div>
        <div class="col-md-3">
            E-Mail
            <br />
            info@almobile.de
            <br />
            Internet
            <br />
            www.al-mobile.de
        </div>
        <div class="col-md-3">
            Sparkasse Dortmund
            <br />
            BIC: DORTDE33XXX
            <br />
            IBAN: DE 1044 0501 9902 3101 8638
        </div>
        <div class="col-md-3">
            Amtsgericht Dortmund
            <br/>
            Geschäftsführer
            <br/>
            Necmi Al
            <br/>
            USt.-IdNr.: DE 240 407 209
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript">
    $(function() {
        $('#saveAndPrint').click(function() {
            var data={};
            $.each($('#form').serializeArray(), function() {data[this.name] = this.value});
            data['buyer'] = {
                name: data.buyer_name,
                street: data.buyer_street,
                location: data.buyer_location,
                country: data.buyer_country
            };


            axios.post('{{route('car_save_invoice', ['id' => $data['car_id']])}}', data).then(function(response) {
                window.print();
            });
        });
    });
</script>
</body>
</html>