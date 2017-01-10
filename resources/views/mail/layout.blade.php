<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Demystifying Email Design</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0; font-family: Raleway,sans-serif;color: #636b6f">



<table align="center" border="0" cellpadding="0" cellspacing="0" width="800" style="border-collapse: collapse;">
    <tr bgcolor="#D2B48C">
        <td style="padding: 40px 0 30px 0;" >
            <table border="0">
                <tr>
                    <td>
                        <img src="{{url('img/1_Primary_logo_on_transparent_175x75.png')}}">
                    </td>
                    <td>
                        <h1>{{ $Data->getTitle() }}</h1>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
    <tr>
        <td align="right">
            <table border="0" cellpadding="10" cellspacing="10" style="padding-bottom: 100px">
                <tr>
                    <td>
                        <b>{{$Data->getSubTitle()}}</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        {!! $Data->getBody() !!}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="75%" bgcolor="#D2B48C" style="padding: 30px 30px 30px 30px" >
            &reg; Lyon Engenharia {{\Carbon\Carbon::now()->year}}<br/>
            Tecnologia da Informação | informatica@lyonengenharia - (31)2125-6639
        </td>
    </tr>
</table>
</body>
</html>