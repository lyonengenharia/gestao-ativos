<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Emails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:disable {data?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make file where list all employees fired, shude';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = $this->argument('data');
        $data = empty($data) ? Carbon::now()->format('Y-m-d') : $data;
        $dateFilter = new \DateTime($data);
        $Employed = \Illuminate\Support\Facades\DB::connection('vetorh')
            ->table('R034FUN')
            ->select([
                'R034FUN.NUMEMP', 'R034FUN.TIPCOL', 'R998LSF.VALKEY',
                'R034FUN.NUMCAD', 'R034CPL.EMACOM', 'R034CPL.EMAPAR',
                'R034FUN.NOMFUN', 'R010SIT.DESSIT', 'R034FUN.CODCCU',
                'R018CCU.NOMCCU', 'R034FUN.DATADM', 'R034FUN.DATAFA',
                'R034FUN.SITAFA', 'R034FUN.NUMCPF'
            ])
            ->join('R998LSF', function ($inner) {
                $inner->on('R998LSF.KEYNAM', '=', 'R034FUN.TIPCOL')
                    ->where('R998LSF.LSTNAM', '=', 'LTipCol');
            })
            ->join('R018CCU', function ($inner) {
                $inner->on('R018CCU.NUMEMP', '=', 'R034FUN.NUMEMP')
                    ->whereColumn('R018CCU.CODCCU', '=', 'R034FUN.CODCCU');
            })
            ->join('R010SIT', function ($inner) {
                $inner->on('R010SIT.CODSIT', '=', 'R034FUN.SITAFA');
            })
            ->join('R034CPL', function ($inner) {
                $inner->on('R034FUN.NUMEMP', '=', 'R034CPL.NUMEMP')
                    ->whereColumn('R034FUN.TIPCOL', '=', 'R034CPL.TIPCOL')
                    ->whereColumn('R034FUN.NUMCAD', '=', 'R034CPL.NUMCAD');
            })
            ->where('DATAFA', '>=', $data)
            ->where('R034FUN.SITAFA', '=', 7);
        if ($Employed->count() > 0) {
            File::delete(storage_path('app/public') . '/disableemail.csv');
            $Employed = $Employed->get();
            $rowEmail = "";
            foreach ($Employed as $Employ):
                $dataAfa = new \Datetime($Employ->DATAFA);
                $rowEmail .= "<p>".$Employ->NOMFUN.", ".$Employ->EMACOM.", ".$Employ->CODCCU."-". iconv('windows-1252','utf-8',$Employ->NOMCCU).", ".$dataAfa->format('d/m/Y')."</p>";
                $row = $Employ->NOMFUN.";".$Employ->EMACOM.";".$Employ->CODCCU.";".$Employ->NOMCCU.";".$dataAfa->format('d/m/Y');
                \App\Facades\Logging::AppEndFile('disableemail.csv',$row);
            endforeach;
            //enviar por email
            $Data = new \App\Pojo\Message();
            $Data->setTitle('E-mail de funcionários demitidos '.$dateFilter->format('d/m/Y').' á '.Carbon::now()->format('d/m/Y'));
            $Data->setSubTitle('Desabilitar e-mails');
            $bodyMessage = "<p>Prezados,</p><p>Em anexo está a listagem de funcionarios demitidos,abaixo os detalhes:</p>";
            $Data->setBody($bodyMessage."<p><h4>Detalhes das contas</h4></p>".$rowEmail);
            $Data->setAttach("public/disableemail.csv");
            $Data->setAttachName("DisableEmails.csv");
            $message = new \App\Mail\Information($Data);
            $message->to(env('NOTIFICATION_TI'));
            $message->from(env('MAIL_DEFAULT_TI','informatica@lyonegenharia.com.br'));
            \Illuminate\Support\Facades\Mail::send($message);
        }else{
            $Data = new \App\Pojo\Message();
            $Data->setTitle('E-mail de funcionários demitidos '.$dateFilter->format('d/m/Y').' á '.Carbon::now()->format('d/m/Y'));
            $Data->setSubTitle('Desabilitar e-mails');
            $bodyMessage = "<p>Não existe e-mails para desabilitar</p>";
            $Data->setBody($bodyMessage);
            $message = new \App\Mail\Information($Data);
            $message->to(env('NOTIFICATION_TI'));
            $message->from(env('MAIL_DEFAULT_TI','informatica@lyonegenharia.com.br'));
            \Illuminate\Support\Facades\Mail::send($message);
        }
        $this->info("Notificação enviada para :".env('NOTIFICATION_TI'));

    }
}
