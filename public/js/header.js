$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



 $(function(){ 
  // navigation
  $(".mdl-layout__tab").removeClass("is-active");
  var pathname = window.location.pathname;
  if(pathname.startsWith('/memo')){
    $("#nav_memo").addClass("is-active");
  }else if(pathname.startsWith("/home")){
    $("#nav_memo").addClass("is-active");
  }else if(pathname.startsWith("/wishlist")){
    $("#nav_wishlist").addClass("is-active");
  }else if(pathname.startsWith("/wordsearch")){
    $("#nav_search").addClass("is-active");
  }else if(pathname.startsWith("/archive")){
    $("#nav_archive").addClass("is-active");
  }else{
    $("#nav_memo").addClass("is-active");
  }


	// header
	var logout = document.getElementById("logout");
	if (logout != null){
    logout.addEventListener("click",function(){
      location.href = "/logout";
    });
  }

  $('a[rel*=leanModal]').leanModal(
    {
        overlay : 0.5,               // 背面の透明度 
        closeButton: ".modal_close"  // 閉じるボタンのCSS classを指定
  });

  $('a[rel*=leanModal]').click(function(){
    $("#loading_image").removeClass("is-active");
  });

  // 検索部分の入力範囲広げるためにロゴを消す
  $("#exsearchMobile").on("focus",function(){
    $(".mobile-logo").hide();
  });

  $("#exsearchMobile").on("blur",function(){
    if($(this).val() == ""){
      $(".mobile-logo").show();
    }
  });

  // スマホ用の画像対応
  var retinaSwitch = window.devicePixelRatio;
  if(retinaSwitch == 2) {
    $(".section__book-image > a > img").each(function(index){
        var w = $(this).width();
        var h = $(this).height();
        var url = $(this).attr("src");
        if(url.indexOf("SX100") !== -1){
          url = url.replace("SX100","SX200");
          $(this).attr("src",url);
          $(this).width(w);
          $(this).height(h);
        }
    });
  }
});

  // evernoteの連携
  function ever(){
      var oauthUrl = "";
      var url = window.location.href;
      // loading 表示
      $("#loading").show();
      $("#loading_image").addClass("is-active");
      // 認証用URLを受け取る
      $.get(
         '/evernote/authorize',
         {url : url}
         ,
         function(url){
           $("#loading").hide();
           $("#loading_image").removeClass("is-active");
           close_modal("#evernoteDialog");
           window.location.href = url;
         }
       );
  }



	// var dialog = document.getElementById("everdialog");
 //  var showDialogButton = document.getElementById('evernote');
 //    if (! dialog.showModal) {
 //      dialogPolyfill.registerDialog(dialog);
 //    }
 //  $(".evernoteLink").on('click', function() {
 //      var url = window.location.href;

	//     dialog.showModal();
	//     $("#loading").show();
	//     $("#loading_image").addClass("is-active");
	//     $("#evernote_oauth").hide();  
 //    　// 認証用URLを受け取る
 //    	 $.get(
 //     		'/evernote/authorize',
 //     		{url : url}
 //     		,
 //     		function(url){
 //    	 		$("#evernote_oauth").attr("href",url);
 //    	 		$("#loading").hide();
 //    	 		$("#loading_image").removeClass("is-active");
 //    	 		$("#evernote_oauth").show();
 //     		}
	//      );
 //    });
 //    dialog.querySelector('.close').addEventListener('click', function() {
 //      dialog.close();
 //    });

    // 検索ボタン
    $("#card-search").keypress(function(event){
        if(event.which == 13){
            $(".doc-search-button").click();
            return false;
        }
    });


    // modal
    function close_modal(modal_id){
      console.log(modal_id);
      $("#lean_overlay").fadeOut(200);$(modal_id).css({"display":"none"})}

/*--------------------------------------------------------------------------*
 *  
 *  footerFixed.js
 *  
 *  MIT-style license. 
 *  
 *  2007 Kazuma Nishihata [to-R]
 *  http://blog.webcreativepark.net
 *  
 *--------------------------------------------------------------------------*/

new function(){
  
  var footerId = "footer";
  //メイン
  function footerFixed(){
    //ドキュメントの高さ
    var dh = document.getElementsByTagName("body")[0].clientHeight;
    //フッターのtopからの位置
    document.getElementById(footerId).style.top = "0px";
    var ft = document.getElementById(footerId).offsetTop;
    //フッターの高さ
    var fh = document.getElementById(footerId).offsetHeight;
    //ウィンドウの高さ
    if (window.innerHeight){
      var wh = window.innerHeight;
    }else if(document.documentElement && document.documentElement.clientHeight != 0){
      var wh = document.documentElement.clientHeight;
    }
    if(ft+fh<wh){
      document.getElementById(footerId).style.position = "relative";
      document.getElementById(footerId).style.top = (wh-fh-ft-1)+"px";
    }
  }
  
  //文字サイズ
  function checkFontSize(func){
  
    //判定要素の追加 
    var e = document.createElement("div");
    var s = document.createTextNode("S");
    e.appendChild(s);
    e.style.visibility="hidden"
    e.style.position="absolute"
    e.style.top="0"
    document.body.appendChild(e);
    var defHeight = e.offsetHeight;
    
    //判定関数
    function checkBoxSize(){
      if(defHeight != e.offsetHeight){
        func();
        defHeight= e.offsetHeight;
      }
    }
    setInterval(checkBoxSize,1000)
  }
  
  //イベントリスナー
  function addEvent(elm,listener,fn){
    try{
      elm.addEventListener(listener,fn,false);
    }catch(e){
      elm.attachEvent("on"+listener,fn);
    }
  }

  addEvent(window,"load",footerFixed);
  addEvent(window,"load",function(){
    checkFontSize(footerFixed);
  });
  addEvent(window,"resize",footerFixed);
  
}