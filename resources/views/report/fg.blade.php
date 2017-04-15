<table class="table table-condensed table-bordered" style="font-size: 10px; margin:0;border-top:none;border-bottom:none;height:100%">
    <tr style="border-top:none;border-bottom:none;">
        <td style="border-top:none;border-bottom:none;" colspan="3">{{$car->title}}</td>
    </tr>
    <tr style="border-top:none;border-bottom:none;">
        <td style="border-bottom:none;"><strong>FG</strong> {{$car->chassis_number}}</td>
        <td style="border-bottom:none;"><strong>EKD</strong> {{$car->getPurchaseDate()}}</td>
        <td style="border-bottom:none;"><strong>EKP</strong> {{$car->getPurchasePrice()}}</td>
    </tr>
</table>