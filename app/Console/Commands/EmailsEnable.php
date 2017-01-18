<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class EmailsEnable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:enable {data?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $listExistente = "";
        $listIncositencia = "";
        $data = $this->argument('data');
        $data = empty($data) ? Carbon::now()->format('Y-m-d') : $data;
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
            ->where('R034FUN.DATADM', '>=', $data)
            ->where('R034FUN.SITAFA', '!=', 7);
        if ($Employed->count() > 0) {
            File::delete(storage_path('app/public') . '/enable-emails.csv');
            $Employed = $Employed->get();

            foreach ($Employed as $Employ):
                $employed = new \App\Pojo\Employed($Employ->NUMEMP, $Employ->TIPCOL, $Employ->NUMCAD);
                $row = iconv('utf-8', 'windows-1252', $employed->NOMFUN) . ";" . $employed->EMACOM . ";Lyon2016" . ";" . $Employ->CODCCU . "-" . $Employ->NOMCCU . ";";
                if (empty(trim($employed->EMACOM))) {
                    $listExistente .= $row . "<br>";
                } elseif ($employed->EMACOM == 'Favor verificar esse e-mail') {
                    $listIncositencia .= $row . "<br>";
                } else {

                    \App\Facades\Logging::AppEndFile('enable-emails.csv', $row);
                }

            endforeach;
            if(!empty($listExistente)){
                $listExistente = "<br><br><p>Lista de existentes</p><p>$listExistente</p>";
            }
            if(!empty($listIncositencia)){
                $listIncositencia = "<br><br><p>Lista de inconsistência</p><p>$listIncositencia</p>";
            }
            //enviar por email
            $data = new \DateTime($data);
            $Data = new \App\Pojo\Message();
            $Data->setTitle('E-mail de funcionários admitidos');
            $Data->setSubTitle("Lista do dia " . $data->format('d/m/y') . "  à " . Carbon::now()->format('d/m/Y'));
            $bodyMessage = "<p>Prezados segue anexo</p>";
            $Data->setBody($bodyMessage.$listExistente.$listIncositencia);
            $Data->setAttach("public/enable-emails.csv");
            $Data->setAttachName("enable-emails.csv");
            $message = new \App\Mail\Information($Data);
            $message->to(env('NOTIFICATION_TI'));
            $message->from(env('MAIL_DEFAULT_TI', 'informatica@lyonengenharia.com.br'));
            \Illuminate\Support\Facades\Mail::send($message);
        } else {
            $data = new \DateTime($data);
            $Data = new \App\Pojo\Message();
            $Data->setTitle('E-mail de funcionários admitidos');
            $Data->setSubTitle('Criar e-mails');
            $bodyMessage = "<p>Não existe e-mails para criação na data " . $data->format('d/m/y') . "</p>";
            $Data->setBody($bodyMessage);
            $message = new \App\Mail\Information($Data);
            $message->to(env('NOTIFICATION_TI'));
            $message->from(env('MAIL_DEFAULT_TI', 'informatica@lyonegenharia.com.br'));
            \Illuminate\Support\Facades\Mail::send($message);
        }
        $this->info("Notificação enviada para :".env('NOTIFICATION_TI'));
    }
}
