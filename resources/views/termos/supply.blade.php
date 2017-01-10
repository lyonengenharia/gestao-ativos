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
            <p ><h2><b>TERMO DE RESPONSABILIDADE PARA FORNECIMENTO DE NOTEBOOK</b></h2></p>
        </td>
    </tr>
    <tr style="padding:150px 5px 0 5px;display:block;">
        <td></td><td></td>
    </tr>
    <tr>
        <td colspan="2">
            <p style="margin: 15px 0px 0px 15px">Eu, {{$employed->NOMFUN}} CPF: {{$employed->NUMCPF}}, pelo presente instrumento,
               acuso o recebimento de um {{$bem->DesBem}}, {{$termo->obs}} , patrimônio: Lyon {{$bem->CodBem}} de
                propriedade da Lyon Engenharia.
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <p style="margin: 15px 15px 0px 15px">Eu, {{$employed->NOMFUN}}, firmo o presente com o compromisso de assumir inteira
                responsabilidade pela guarda e zelo do bem, utilizando-a única e exclusivamente para minhas atividades
                profissionais, me responsabilizando por ressarcir a empresa em caso de perda; em caso de dano ou avaria
                por minha culpa, bem como pela legalidade dos softwares nele instalados e de apresentá-la em local
                previamente combinado, quando solicitado pelos administradores da Lyon Engenharia restituindo-o à
                empresa, quando por esta solicitado, ou quando cessarem as minhas atividades. Fica proibida a
                transferência do recurso fornecido, devendo o empregado devolvê-lo na Administração, mediante
                protocolo de entrega.
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <p style="margin: 15px 15px 0px 15px">Eu, {{$employed->NOMFUN}}, firmo o presente com o compromisso de assumir inteira
                Eu, Rogério Reis Lopes, responsabilizo por ressarcir a empresa de quaisquer custos por ela arcados
                referentes à perda; em caso de dano ou avaria por minha culpa, comprometendo-me com valor médio de
                aquisição do equipamento, no montante de R$ VALOR DE MERCADO INFORMADO PELO SUPRIMENTOS.
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
    <tr>
        <td colspan="2" ><p style="margin-left: 15px">Testemunhas</p></td>
    </tr>
    <tr style="margin-top: 5px">
        <td>
            <p style="margin-left: 15px">
                Nome:________________________________________________________
            </p>
            <p style="margin-left: 15px">
                CI:___________________________________________________________
            </p>
            <p style="margin-left: 15px">
                CPF:__________________________________________________________
            </p>
        </td>
        <td>
            <p style="margin-left: 15px">
                Nome:________________________________________________________
            </p>
            <p style="margin-left: 15px">
                CI:___________________________________________________________
            </p>
            <p style="margin-left: 15px">
                CPF:__________________________________________________________
            </p>
        </td>
    </tr>
    <tr style="padding:350px 5px 0 5px;display:block;">
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