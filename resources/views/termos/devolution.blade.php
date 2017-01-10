<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="assets/css/app.css" rel="stylesheet">

    <!-- Scripts -->
</head>
<body style="margin: 15px 15px 25px 15px; padding: 0px 5px 0px 0px; font-family: Raleway,sans-serif;color: #636b6f">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="800" style="border-collapse: collapse;">
    <tr>
        <td align="right" colspan="2">
            <img src="{{asset('img/gestaoativos.png')}}">
        </td>
    </tr>
    <tr>
        <td align="center" colspan="2">
            <p ><h2><b>TERMO DE DEVOLUÇÃO PARA {{$bem->CodBem}}  </b></h2></p>
        </td>
    </tr>
    <tr style="padding:150px 5px 0 5px;display:block;">
        <td></td><td></td>
    </tr>
    <tr>
        <td colspan="2">
            <p style="margin: 15px 0px 0px 15px">Eu, {{$employed->NOMFUN}} CPF: {{$employed->NUMCPF}}, pelo presente instrumento,
                devolvo o  {{$bem->DesBem}}, {{$termo->obs}} , patrimônio: Lyon {{$bem->CodBem}} de propriedade da Lyon
                Engenharia Comercial Eireli que se encontra em perfeito estado de conservação e funcionamento, conforme
                vistoria assinada pelas partes, ficando isento de qualquer responsabilidade a partir da presente data.
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <p style="margin: 15px 15px 0px 15px">
                O presente termo foi registrado em duas vias, uma da empresa e outra do empregado, ambas assinadas. O
                termo da empresa será armazendo físicamente e digitalmente.
            </p>
        </td>
    </tr>
    <tr style="padding:100px 5px 0 5px;display:block;">
        <td></td><td></td>
    </tr>
    <tr style="margin-top: 25px">
        <td>
            <p style="margin-left: 15px">
                ___________________________________________________________________
            </p>
            <p style="margin-left: 15px;margin-top: -15px">Setor de Tecnologia da informação - Lyon</p>
        </td>
        <td>
            <p style="margin-left: 15px;margin-right: 15px">
                ____________________________________________________________________
            </p>
            <p style="margin-left: 15px;margin-top: -15px">{{$employed->NOMFUN}}</p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <p style="margin-left: 15px">
                ___________________________________________________________________
            </p>
            <p style="margin-left: 15px;margin-top: -15px">Cidade e data</p>
        </td>
    </tr>
    <tr style="padding:50px 5px 0 5px;display:block;">
        <td></td><td></td>
    </tr>
</table>
</body>
</html>