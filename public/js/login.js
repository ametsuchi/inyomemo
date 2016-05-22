var twitter = document.getElementById("twitter");
var github = document.getElementById("github");
twitter.addEventListener("click",function(){
	var check = document.getElementById("remember").checked;
	window.location.href = "/auth/twitter?remember=" + check;
});
github.addEventListener("click",function(){
	var check = document.getElementById("remember").checked;
	window.location.href = "/auth/github?remember=" + check;
});