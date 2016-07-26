$(function(){
    autosize(document.querySelectorAll('textarea'));

      // スマホ用の画像対応
  var retina = window.devicePixelRatio;

  if(retina == 2) {
    var $img = $("#book_image");
    var width = $img.width();
    var height = $img.height();
    var bookUrl = $img.attr("src");
    alert(bookUrl);
    if(bookUrl.indexOf("SX100") !== -1){
        bookUrl = bookUrl.replace("SX100","SX200");
        $img.width(width);
        $img.height(height);
    }
  }

    // mobileの時はフッター邪魔なので非表示
    if($(window).width() < 500){
        $("footer").hide();
    }

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
        var quoteBr = quote.replace(/\r\n/g,"<br />");
        quoteBr = quoteBr.replace(/(\n|\r)/g,"<br />");


        $cloneDiv = $add.clone();

        $cloneDiv.attr("id","newDiv").prependTo("#memo-detail");
        $("#add_note","#newDiv").html(note);
        $("#add_time","#newDiv").html(date);

        if(quote === ""){
            $("#add_quote","#newDiv").remove();
        }else{
            $("#add_quote","#newDiv").html("<span>" + quoteBr + "</span>");
        }

        if(page != 0){
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
                $("#add_more").attr("name",id).show();
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
        var id = $(this).attr("name");
        $("#note"+id).remove();

        close_modal("#memoDialog");

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
        var id = $(this).attr("name");
        location.href = "/memo/edit/" + id;
    });



// $('#openEditDialog').on(function(){


//   $('#openEditDialog').leanModal(
//     {
//         overlay : 0.5,               // 背面の透明度 
//         closeButton: ".modal_close"  // 閉じるボタンのCSS classを指定
//   });
// });

  $('.openEditDialog').on("click",function(e){
                console.log("?");

        var id = $(this).attr("name").replace("menu","");
        $(".doc-edit").attr("name",id);
        $(".doc-delete").attr("name",id);



        var o=options;
        var modal_id="#memoDialog";
                $("#lean_overlay").click(function(){
                    close_modal(modal_id)});
                $(o.closeButton).click(function(){
                    close_modal(modal_id)});
                var modal_height=$(modal_id).outerHeight();
                var modal_width=$(modal_id).outerWidth();
                $("#lean_overlay").css({"display":"block",opacity:0});
                $("#lean_overlay").fadeTo(200,o.overlay);
                $(modal_id).css({"display":"block","position":"fixed","opacity":0,"z-index":11000,"left":50+"%","margin-left":-(modal_width/2)+"px","top":"30%"});
                $(modal_id).fadeTo(200,1);
                e.preventDefault();
  });

    // dialog setting
    var defaults={top:100,overlay:0.5,closeButton:null};
    var overlay=$("<div id='lean_overlay'></div>");
    $("body").append(overlay);
    var options=$.extend(defaults,options);

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

// らいぶらりのやつ、動的にひもづけできない
function　openDialog(options){

    console.log("openDialog");


    return aaa(options);
} 


            // var o=options;
            // $(".openEditDialog").on("click",function(e){
            //     );
