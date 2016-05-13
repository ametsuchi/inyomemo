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
           // $("#add_page","#newDiv").remove();
        }else{
            $("#add_page","#newDiv").text("P." + page);
        }
        $("#add_more").hide();
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
                $("#add_delete").attr("id","delete"+id).attr("name","delete"+id);
                $("#add_edit").attr("id","edit"+id).attr("name","edit"+id);
                $("#add_more").attr("id","more"+id).show();
                $.get(
                    '/evernote/writeevernote',
                    {
                        'isbn': isbn,
                        'title': title,
                        'author': author,
                    }
                    );
            }
    	);
    });

    // 削除
    $(document).on("click",".doc-delete",function(){
        // var id = $(this).attr("id").replace("delete","");
        var id = $(this).attr("name").replace("delete","");
        $("#note"+id).remove();

        var dialog = document.getElementById('moreDialog');
        if (! dialog.showModal) {
            dialog.close();
        }

        var url = "/memo/delete/" + id;
        $.get(
            {
                url
            },
            function(){
                var isbn = $("#isbn").val();
                var title = $("#title").text();
                var author = $("#author").text();

                $.get(
                    '/evernote/writeevernote',
                    {
                        'isbn': isbn,
                        'title': title,
                        'author': author,
                    }
                );
            } 
        );
    });

    // 編集
    $(document).on("click",".doc-edit",function(){
        var id = $(this).attr("name").replace("edit","");
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

    // mobile版
    $(document).on("click",".doc-more",function(){
            var id = $(this).attr("id").replace("more","");
            var dialog = document.getElementById('moreDialog');

            // id 返ってくるまで編集させない保険
            if(id == "add_"){
                return;
            }
            $("#dialog-delete").attr("name","delete" + id);
            $("#dialog-edit").attr("name","edit" + id);

            dialog.showModal();
    });

    $(document).on("click","#dialog-close",function(){
        var dialog = document.getElementById('moreDialog');
        dialog.close();
    });



    

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