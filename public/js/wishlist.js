  $('.add-list-button').click(function(){
    var isbn = $(this).attr('id');
    var titleid = $("#setting-name").attr("name");
    $("#card"+isbn).hide("10");
    $.post(
      '/wishlist/delete',
      {
        'isbn':isbn,
        'titleid':titleid
      }
    );      

  });

  $(function() {
        $(".doc-setting").on("click",function(){
            $(".error").hide();
            $("#setting-loading").hide();
        });

        $("#setting-rename").on("click",function(){
            var titleid = $("#setting-name").attr("name");
            var name = $("#setting-name").val();

            // ダイアログの中
            $("#setting-name").val(name);
            // リスト名
            $("#select-section").val(name);
            // メニューの中
            $(".menu" + titleid).text(name);

            close_modal("#settingWishlistDialog");

            $.get(
                '/wishlist/rename',
                {
                    'titleid':titleid,
                    'name':name
                }
                );
        });

        $("#setting-delete").on("click",function(){
            var titleid = $("#setting-name").attr("name");
            $("#setting-loading").show();
            $("#setting-loading-image").addClass("is-active");
            $.get(
                '/wishlist/deletelist',
                {
                    'titleid':titleid
                },
                function(){
                    window.location.href = "/wishlist/0";
                    $.get('/evernote/delete/'+titleid);
                }
            );
        });

  });