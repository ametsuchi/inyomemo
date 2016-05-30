$(function(){
	$("#addListButton").on("click",function(){
		var name = $("#select-section").val();
		var isbn = $(this).attr("name");
		close_modal("#addListDialog");
		var new_flg = false;
		if(name == "新規作成…"){
			new_flg = true;
		}
		$.post(
				'/wishlist/add',
				{
					'isbn':isbn,
					'title':$('#title'+isbn).val(),
					'author':$('#author'+isbn).val(),
					'imageUrl':$('#imageUrl'+isbn).val(),
					'amazonUrl'	:$('#amazonUrl'+isbn).val(),
					'publicationDate':$('#publicationDate'+isbn).val(),
					'name':name,
					'new_flg':new_flg
				}
			);
	});

	$(".addListOpen").on("click",function(){
		var isbn = $(this).attr("name");
		$("#addListButton").attr("name",isbn);
	});

	$('div.mdl-select > ul > li').click(function(e) {
        var text = $(e.target).text();
        $(e.target).parents('.mdl-select').addClass('is-dirty').children('input').val(text);
    });
});