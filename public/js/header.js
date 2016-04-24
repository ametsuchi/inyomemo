$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(function(){

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

});