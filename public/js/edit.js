$(function(){
	autosize(document.querySelectorAll('textarea'));

	$("#edit").click(function(){
		$("#edit_saving").show();
		$("#saving_image").addClass("is-active");


		var id = $("#id").val();
		var url = "/memo/edit/" + id + "/save";

		$.post(url,
			{
				'page':$("#page").val(),
				'note':$("#note").val(),
				'quote':$("#quote").val()
			},
			function(){
                $.get(
                    '/evernote/writeevernote',
                    {
                        'isbn': $("#isbn").val(),
                        'title': $("#title").val(),
                        'author': $("#author").val(),
                    }
                );
               window.location.href = "/memo/" + $("#isbn").val();
			}
		);
	});
});