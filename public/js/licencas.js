function handleData(a,e,t){$("#resultOfSearch").empty(),$.each(a,function(a,e){var t='<div class="panel panel-default"><div class="panel-heading"><b>'+e.CODBEM+'</b></div><div class="panel-body"><p><b>Data Aquisição:</b> '+e.DATAQI+" </p><p><b>Item:</b> "+e.DESBEM+" </p><p><b>Descrição:</b> "+e.DESESP+" </p><p><b>Empresa:</b> "+e.NOMEMP+' </p><button class="btn btn-primary  btn-xs selecao" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Marcar</button></div><div style=\'display: none\' class=\'codemp\'>'+e.CODEMP+"</div></div>";$("#resultOfSearch").append(t)}),$("#buttonSearch").empty(),$("#buttonSearch").append("Pesquisar")}