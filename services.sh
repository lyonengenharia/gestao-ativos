nohup gulp watch > log_watch_gulp&
echo "Inciado o Gulp Watch"
nohup php ./artisan queue:work > log_queue_work&
echo "Inciado Laravel Queue"
