<table class="table table-condensed table-bordered" style="font-size: 10px; margin:-8px;border-top:none;border-bottom:none;">
    <tr style="border-top:none;border-bottom:none;">
        <td style="border-top:none;border-bottom:none;" colspan="2"><strong>Steuer</strong> {{$car->getTaxIdentifier()}}</td>
    </tr>
    <tr style="border-top:none;border-bottom:none;">
        <td style="border-bottom:none; white-space: nowrap;"><strong>Gewinn</strong> {!! \App\Formatter::indicatedCurrency($car->sale_price - $car->purchase_price) !!}</td>
        <td style="border-bottom:none;white-space: nowrap;"><strong>MwSt.</strong>
            @php
            echo (($car->sale_date && ($car->sale_price - $car->purchase_price) > 0) ?
                                            ($car->tax) ?
                                                \App\Formatter::indicatedCurrency($car->sale_price - $car->purchase_price - (($car->sale_price - $car->purchase_price)/1.19)) :
                                                \App\Formatter::indicatedCurrency($car->sale_price - (($car->sale_price)/1.19))
                                            : null);
            @endphp
        </td>
    </tr>
</table>