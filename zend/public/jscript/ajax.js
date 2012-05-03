function makeObject(){
	var x;
	if (window.ActiveXObject) {
		x = new ActiveXObject("Microsoft.XMLHTTP");
	}else if (window.XMLHttpRequest) {
		x = new XMLHttpRequest();
	}
	return x;
}
var request = makeObject();
var request1 = makeObject();

//AJAX the content
var the_content;
function check_content(f,section){
	var formData = '', elem = ''; 
	for(var s=0; s<f.elements.length; s++){ 
		elem = f.elements[s]; 
  if(elem.type=="radio" && elem.checked!=true)
  continue;
		if(formData != ''){ 
			formData += '&'; 
		}
		formData += elem.name+"="+escape(elem.value); 
  	if(formData != '')
 { 
		formData += '&'; 
	}
	formData += "output=ajax"; 

	}
	request.open(f.method, f.action, true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
 request.onreadystatechange = function()
 {
  if(request.readyState == 1)
  {
		 document.getElementById(section).innerHTML = '<div class="spinner">&nbsp</div>';
	 }
	 if(request.readyState == 4)
  {
		 var answer = request.responseText;
		 document.getElementById(section).innerHTML = answer;
	 }
 }
 request.send(formData);
	return false;
}
function check_content1(f,section){
	var formData = '', elem = ''; 
	for(var s=0; s<f.elements.length; s++){ 
		elem = f.elements[s]; 
  if(elem.type=="radio" && elem.checked!=true)
  continue;
		if(formData != ''){ 
			formData += '&'; 
		}
		formData += elem.name+"="+escape(elem.value); 
  	if(formData != '')
 { 
		formData += '&'; 
	}
	formData += "output=ajax"; 

	}
	request.open(f.method, f.action, true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
 request.onreadystatechange = function()
 {
  if(request.readyState == 1)
  {
		 document.getElementById(section).innerHTML = '<div class="spinner">&nbsp</div>';
	 }
	 if(request.readyState == 4)
  {
		 var answer = request.responseText;
		 document.getElementById(section).innerHTML = answer;
   reload_me();
	 }
 }
 request.send(formData);
	return false;
}
function request_action(url,section,act)
{
 var url=url;
 if(url.match(/\?/i)) 
 url=url+'&action1='+act;
 else
 url=url+'?action1='+act;
 url=url+'&output=ajax';
 request1.open('get',url,true);

 request1.onreadystatechange = function()
 {
  if(request1.readyState == 1)
  {
   document.getElementById(section).innerHTML = '<div class="spinner">&nbsp</div>';
  }
  if(request1.readyState == 4)
  {
   var answer = request1.responseText;
   document.getElementById(section).innerHTML = answer;
  }
 }
 request1.send(null);
 return false;
}
function friend_request_decline(url,section)
{
 var check= confirm("Do you really want to decline this friend request.");
 if(check!=true)
 return false;
 return request_action(url,section,'decline');
}
function friend_request_reject(url,section)
{
 var check= confirm("Do you really want to reject this friend request.");
 if(check!=true)
 return false;
 return request_action(url,section,'decline_friend');
}