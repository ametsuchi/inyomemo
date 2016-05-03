$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(function(){
  
  // navigation
  $(".mdl-navigation__link").removeClass("is-active");
  var pathname = window.location.pathname;
  console.log("pathname",pathname);
  if(pathname.startsWith('/memo')){
    $("#nav_memo").addClass("is-active");
  }else if(pathname.startsWith("/home")){
    $("#nav_memo").addClass("is-active");
  }else if(pathname.startsWith("/wishlist")){
    $("#nav_wishlist").addClass("is-active");
  }else if(pathname.startsWith("/archive/search")){
    $("#nav_search").addClass("is-active");
  }else{
    $("#nav_archive").addClass("is-active");
  }


	// header
	var logout = document.getElementById("logout");
	logout.addEventListener("click",function(){
		location.href = "/logout";
	});

	var dialog = document.querySelector('dialog');
    var showDialogButton = document.getElementById('evernote');
    if (! dialog.showModal) {
      dialogPolyfill.registerDialog(dialog);
    }
    showDialogButton.addEventListener('click', function() {
    	var url = window.location.href;

	    dialog.showModal();
	    $("#loading").show();
	    $("#loading_image").addClass("is-active");
	    $("#evernote_oauth").hide();  
    　// 認証用URLを受け取る
    	 $.get(
     		'/evernote/authorize',
     		{url : url}
     		,
     		function(url){
    	 		$("#evernote_oauth").attr("href",url);
    	 		$("#loading").hide();
    	 		$("#loading_image").removeClass("is-active");
    	 		$("#evernote_oauth").show();
     		}
	     );
    });
    dialog.querySelector('.close').addEventListener('click', function() {
      dialog.close();
    });

    // 検索ボタン
    $("#card-search").keypress(function(event){
        if(event.which == 13){
            $(".doc-search-button").click();
            return false;
        }
    });

});