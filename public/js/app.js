$('document').ready(()=>{



	$('a[data-confirm]').click(function(ev){
		//Recebe o valor do href que nesse caso seria o ( id_tweet = :id_tweet )
		var href = $(this).attr('href');

		if(!$('#confirm-delete').length){
			$('body').append('<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header bg-danger text-white">EXCLUIR ITEM<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body">Tem certeza que deseja Excluir o item selecionado?</div><div class="modal-footer"><button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button><a id="data-confirmOK" class="btn btn-danger text-white">Apagar</a></div></div></div></div>');
		}

		//setando o valor do href para a variável(href) criado acima
		$('#data-confirmOK').attr('href', href);

		$('#confirm-delete').modal({shown:true});

		return false;
	});



});