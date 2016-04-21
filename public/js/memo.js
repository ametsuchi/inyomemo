$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



$(function(){
    autosize(document.querySelectorAll('textarea'));

    //post
    $("#save").click(function(){
        var isbn = $("#isbn").val();
        var title = $("#title").text();
        var author = $("#author").text();
        var quote = $("#quote").val();
        var note = $("#note").val();
        var image_url = $("#book_image").attr("src");
        var amazon_url = $("#amazon_link").attr("href");
        var page = $("#page").val();
        var date = getLocaleDateString();

    	// 投稿内容を追加
        var $add = $("#add_div").children();
        var noteBr = note.replace(/\r\n/g,"<br />");
        noteBr = noteBr.replace(/(\n|\r)/g,"<br />");


        $cloneDiv = $add.clone();
        $cloneDiv.attr("id","newDiv").prependTo("section");
        $("#add_note","#newDiv").html(note);
        $("#add_time","#newDiv").html(date);

        if(quote === ""){
            $("#add_quote_div","#newDiv").remove();
        }else{
            var quoteBr = quote.replace(/\r\n/g,"<br />");
            quoteBr = quoteBr.replace(/(\n|\r)/g,"<br />");
            $("#add_quote","#newDiv").html(quoteBr);
        }

        if(page == 0){
            $("#add_page","#newDiv").remove();
        }else{
            $("#add_page","#newDiv").text("P." + page);
        }
        $("#newDiv").show(1000);
        // reset
        $("#page").val("");
        $("#note").val("");
        $("#quote").val("");
        $("#newDiv").attr("id","addDiv");

    	$.post(
    		'/memo/post',
    		{
    			'isbn': isbn,
    			'title': title,
    			'author': author,
    			'quote': quote,
    			'note': note,
    			'image_url': image_url,
    			'amazon_url': amazon_url,
    			'page': page

    		},
            function(id){
                $("#addDiv").attr("id","note"+id);
                $("#add_delete").attr("id","delete"+id);
                $("#add_edit").attr("id","edit"+id);
            }
    	);
    });

    // 削除
    $("section").on("click",".doc-delete",function(){
        var id = $(this).attr("id").replace("delete","");
        $("#note"+id).remove();

        var url = "/memo/delete/" + id;
        $.get(
            {
                url
            }
        );
    });

    // 編集
    $("section").on("click",".doc-edit",function(){
        var id = $(this).attr("id").replace("edit","");
        location.href = "/memo/edit/" + id;
    });


    // 編集ボタンと削除ボタン追加
    $(document).on("mouseenter",".note",function(){
            var id = $(this).attr("id").replace("note","");
            $("#delete"+id).show();
            $("#edit"+id).show();
        }
    );

    $(document).on("mouseleave",".note",function(){
            var id = $(this).attr("id").replace("note","");
            $("#delete"+id).hide();
            $("#edit"+id).hide();
        }
    );

});



function getLocaleDateString()
{
    var date = new Date();
    var dateStr = [
        date.getFullYear(),
        date.getMonth() + 1,
        date.getDate()
        ].join( '/' ) + ' '
        + date.toLocaleTimeString();
    return dateStr.substr(0,dateStr.length-3);
}