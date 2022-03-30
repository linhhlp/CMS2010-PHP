/*
* @author HLP4ever 2010
* There are function need for This bouns, written by HLP
*
*/


//*************************
// AJAX Functions
// create XMLHttp Object

function ajax_connect () {
    var status = false;
    // check IE
    try {
        //if is IE 5
        status = new ActiveXObject('Msxml2.XMLHTTP');
    }
    catch (e) {
        // if not, maybe is older version
        try {
            status = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(E) {
            // non-IE
            status = false;
        }
    }
    // if non IE so create new
    if(!status && typeof XMLHttpRequest != 'undifined') {
        status = new XMLHttpRequest;
    }
    return status;
}


function ajax_process(address,GETorPOST,str){
	hien_thi_div('mess_userbonus',loading_str );
    var ajax = new ajax_connect();
    if(GETorPOST == "GET") {
        ajax.open("GET",address);
        ajax.onreadystatechange = function() {
            if(ajax.readyState == 4 && ajax.status == 200) {
            	Result_login = ajax.responseText; 
            	action_login(Result_login);
            }
        }
        ajax.send(null);
    }
    else {
        ajax.open("POST",address,true);
        ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=UTF-8");
        ajax.onreadystatechange = function() {
            if(ajax.readyState == 4 && ajax.status == 200) {
            	//alert(ajax.responseText);
            	Result_login = ajax.responseText;
            	action_login(Result_login);
            }
        }
        ajax.send(str);
    }
    
    
}
var x =false;
function getformvalue(form,valfun) {
    // This will collect all form values become a string.
    // If there is VALFUN it means that we have to check value (Validation function)
    // if form is not a object, so get it by ID:
    if(typeof form != 'object') form = document.getElementById(form);
    var check = true;
    x = true;
    var str = '';
    // Loop for each form value
    for(var i=0;i < form.elements.length;i++) {
        str += form.elements[i].name + "=" + form.elements[i].value + "&";
    }
    //
    return str;
}
// END AJAX Functions
//************************

// Action functions

function hien_thi_div(div,str) {
    if(typeof div != 'object') div = document.getElementById(div);
    div.innerHTML = str;
}
var form_login_str  = '<form method="POST" id="form_userbonus" name="form_userbonus" onSubmit="return false;"> ';
form_login_str		= form_login_str + '<label for="name">Tên của bạn</label><input name="name" value="" type="text"/> ';
form_login_str		= form_login_str + '<select name=logintype><option value=friend>Khách đến chơi</option><option value=own>Chủ nhà</option></select><br /> ';
form_login_str		= form_login_str + '<input name="privatekey" value="'+privatekey+'" type="hidden"/><input name="prefix" value="'+prefix+'" type="hidden"/>';
form_login_str 		= form_login_str + '<label for="submit"><input type="button" value="Tham gia" name="submit" ';
form_login_str		= form_login_str + 'onclick="ajax_process(address,\'POST\', getformvalue(\'form_userbonus\') )"/></label>';
form_login_str 		= form_login_str + '</form>';

var form_member_login_str = '<div class="canh_bao_userbounus">Bạn đăng nhập với tên của 1 thành viên</div><br>';
form_member_login_str = form_member_login_str +  'Nhập password (đăng nhập như thành viên) Nhấn cancel để chọn tên đăng nhập mới';
    // Hien dang nhap cho password
form_member_login_str = form_member_login_str + '<form method="POST" id="form_userbonus" name="form_userbonus" onSubmit="return false;">';
form_member_login_str = form_member_login_str + '<input name="pass" value="" type="password"/>';
form_member_login_str = form_member_login_str + '<input name="privatekey" value="'+privatekey+'" type="hidden"/><input name="prefix" value="'+prefix+'" type="hidden"/>';
form_member_login_str = form_member_login_str + '<input type="button" value="Tham gia" name="submit" onclick="ajax_process('+ "'" +address + " ','POST', getformvalue('form_userbonus')" + ')"/> ';
form_member_login_str = form_member_login_str + '</form> ';
    // Hien Cancel
form_member_login_str = form_member_login_str + '<input type="button" onclick="hien_thi_div(\'mess_userbonus\',form_login_str);" value="Cancel"> ';


//END Define variables
var code;
function action_login(Result_login) {
//process xml to text
	var message = "";//alert(Result_login)
	if( Result_login != "" ) {
		xmldoc = processStrXML(Result_login);
		code = xmldoc.getElementsByTagName("code")[0].childNodes[0].nodeValue;
		if(code == 1) {
			logged = 0;
			message = "Đăng nhập thất bại. ";
		}
		else if(code == 6) {
			logged = 0;
			message = "Trùng tên thành viên. ";
		}
		else if( code == 3) {
			hien_thi_div('mess_userbonus',form_member_login_str);
			logged = 3;
		}
		else if( code == 0 || code == 2 ){
			logged = 1;
			var type = xmldoc.getElementsByTagName("type")[0].childNodes[0].nodeValue;
			var name = xmldoc.getElementsByTagName("name")[0].childNodes[0].nodeValue;
			welcome_str = type+": "+name;
		}
		else if( code == 4){
			logged = 0;
			message = "Thoát thành công. ";
		}
		else if( code == 5){
			logged = 5;
			var form_friend_login_str = '<div class="canh_bao_userbounus">Bạn của chủ blog?Vui lòng Xác thực:</div><br>';
			form_friend_login_str = form_friend_login_str + '<form method="POST" id="form_userbonus" name="form_userbonus" onSubmit="return false;">';
			form_friend_login_str		= form_friend_login_str + 'Bạn ở?: <select name=urlfriend>';
			for(i=0;i<xmldoc.getElementsByTagName("url").length;i++) {
			  form_friend_login_str = form_friend_login_str + "<option value='"+xmldoc.getElementsByTagName("url")[i].childNodes[0].nodeValue+"'>"+xmldoc.getElementsByTagName("url")[i].childNodes[0].nodeValue+"</option>";
			}
			form_friend_login_str		= form_friend_login_str + '</select><br /> ';
			form_friend_login_str = form_friend_login_str + '<input name="privatekey" value="'+privatekey+'" type="hidden"/><input name="prefix" value="'+prefix+'" type="hidden"/><input name="friendname" value="'+xmldoc.getElementsByTagName("name")[0].childNodes[0].nodeValue+'" type="hidden"/>';
			form_friend_login_str = form_friend_login_str + '<input type="button" value="Tham gia" name="submit" onclick="checkLoginFriend( \'form_userbonus\' )" /> ';
				// Hien Cancel
			form_friend_login_str = form_friend_login_str + '<input type="button" onclick="hien_thi_div(\'mess_userbonus\',form_login_str);" value="Cancel"> ';
			form_friend_login_str = form_friend_login_str + '</form> ';
			hien_thi_div('mess_userbonus',form_friend_login_str);
			
		}
	}
	if(logged == 0) {
		hien_thi_div('mess_userbonus',message + form_login_str);
		hien_thi_div('comment_news_userbonus','');
		hien_thi_div('friend_news_userbonus','');
	}
	else if(logged == 1){
        str = 'Chào mừng <span class="userbounus_username">' + welcome_str + '</span>';
        str += '<input type="button" onclick="ajax_process(address + \'?login=logout\',\'GET\');" value="Log-out"> '
	    hien_thi_div('mess_userbonus',str);
        
        if( code == 0) {
			hien_thi_div('comment_news_userbonus',loading_str);
			setTimeout("formykun()",100);
			hien_thi_div('friend_news_userbonus',loading_str);
			setTimeout("checkfriend()",100);
        }
    }
}
// get news of own blog, and get list of friend (url) with last update (last ID we visit)
//global own blog infomation
var news_info = '';
function formykun() {
	if( code != 0 )		return; 
	var link = url + '/communitylogin.php?action=checknew';
	var ajax = new ajax_connect();
    ajax.open("GET",link);
    ajax.onreadystatechange = function() {
        if(ajax.readyState == 4 && ajax.status == 200) {
        	news_info = ajax.responseText;
        	if(news_info != "") {
				//alert(bonus);
				var xmldoc	 	= processStrXML(news_info);
				var message 	= xmldoc.getElementsByTagName("message")[0].childNodes[0].nodeValue;
				var commentNew  = xmldoc.getElementsByTagName("numbercom")[0].childNodes[0].nodeValue;
				var commentBlogNew= xmldoc.getElementsByTagName("numbercomblog")[0].childNodes[0].nodeValue;
				var str = '<a href="'+weburl+'community/news/">';
				if(message+commentNew+commentBlogNew == 0 ) str = str + 'Bạn không có gì mới';
				else str = str + 'Bạn có: ';
				if(message > 0) 	str = str + message+' tin nhắn mới, ';
				if(commentNew > 0) 	str = str +commentNew+ ' bình luận mới, ';
				if(commentBlogNew > 0) str = str +commentBlogNew+ 'bình luận cho bài viết mới';
				str = str + '</a>';
				hien_thi_div('comment_news_userbonus',str);
        	}
        }
    }
    ajax.send(null);
    
}
// global variable : friendlist update
var news_friendlist='';
function checkfriend() {
	hien_thi_div('friend_news_userbonus','<a href="'+weburl+'community/news/">Ngó sang hàng xóm!</a>');
}

function processStrXML(text) {
    if (window.DOMParser)
    {
    parser=new DOMParser();
    xmlDoc=parser.parseFromString(text,"text/xml");
    }
    else // Internet Explorer
    {
    xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
    xmlDoc.async="false";
    xmlDoc.loadXML(text);
    }
	return xmlDoc;
}
// for community component
function manage_function(reupdate) {
	hien_thi_div('mess_display_userbonus',loading_str);
	hien_thi_div('friend_display_news_userbonus',loading_str);
	mess_display(reupdate);
	friend_display(reupdate);
}
function mess_display(reupdate) {
	if(news_info != '' && reupdate == false) {
		mess_display_func(news_info);
	}
	else {
		var link = url + '/communitylogin.php?action=checknew';
		if(reupdate == true ) 	link = link+'&type=renew';
		var bonus;
		var ajax = new ajax_connect();
		ajax.open("GET",link);
		ajax.onreadystatechange = function() {
			if(ajax.readyState == 4 && ajax.status == 200) {
				news_info = ajax.responseText;
				mess_display_func(news_info);
				}
			}
		ajax.send(null);
	}
}
var from = 0;
var step = 10;	// number of friend per check
function friend_display(reupdate) {
	var link = url + '/communitylogin.php?action=checkfriendnews';
	if(reupdate == true ) 	{from = 0;link = link+'&type=renew';alert('Đang cập nhật thông tin, vui lòng chờ!'); }
	if(from == 0) news_friendlist = '';
	link = link + '&from='+from+'&step='+step;
	var str = '';
	var ajax = new ajax_connect();
	ajax.open("GET",link,true);
	ajax.onreadystatechange = function() {
		if(ajax.readyState == 4 && ajax.status == 200) {
			//alert (ajax.responseText);
			if(ajax.responseText.length > 0 && ajax.responseText != news_friendlist) {
				news_friendlist = ajax.responseText;
				friend_display_func(news_friendlist);
				from = from + step;
				setTimeout("friend_display(false)",100);
			}
			else friend_display_func(news_friendlist);
		}
	}
	ajax.send(null);
}

function mess_display_func(news_info) {
	var xmldoc	 	= processStrXML(news_info);//alert(news_info);
	var message 	= xmldoc.getElementsByTagName("message")[0].childNodes[0].nodeValue;
	var numbercom  = xmldoc.getElementsByTagName("numbercom")[0].childNodes[0].nodeValue;
	var numbercomblog= xmldoc.getElementsByTagName("numbercomblog")[0].childNodes[0].nodeValue;
	var str = '<a href="'+weburl+'community/news/"> Bạn có: '+message+' tin nhắn mới, '+numbercom+ ' bình luận mới, '+numbercomblog+ 'bình luận cho bài viết mới</a>';
	if(message>0) {
		str = str +'<h3>Tin nhắn mới</h3>';
		str = str + '<table border=0 width=95%>';
		for( i =0;i <message;i++) {
			var item		= xmldoc.getElementsByTagName("item")[i];
			var title		= item.getElementsByTagName("title")[0].childNodes[0].nodeValue;
			var content		= item.getElementsByTagName("content")[0].childNodes[0].nodeValue;
			var created_by	= item.getElementsByTagName("created_by")[0].childNodes[0].nodeValue;
			var created		= item.getElementsByTagName("created")[0].childNodes[0].nodeValue;
			str = str + '<tr><td>Title</td><td>'+title+'</td></tr><tr><td colspan=2>Content:</td></tr><tr><td colspan=2><div class=userbounus_content>'+content+'</div></td></tr><tr><td>From </td><td>'+created_by+'</td></tr><tr><td>Create date</td><td>'+created+'</td></tr>'
	
		}
		str = str + '</table>';
	}
	if(numbercom > 0) {
		str = str +'<h3>Bình luận blog</h3>';
		for( i =0;i <numbercom;i++) {
			var item		= xmldoc.getElementsByTagName("commentfg")[i];
			var title		= item.getElementsByTagName("title")[0].childNodes[0].nodeValue;
			var comment		= item.getElementsByTagName("comment")[0].childNodes[0].nodeValue;
			var date		= item.getElementsByTagName("date")[0].childNodes[0].nodeValue;
			var user		= item.getElementsByTagName("user")[0].childNodes[0].nodeValue;
			str = str + '<div>Tiêu đề:<span class="COMMENT_HLP_TITLE"> '+title+'</span> <span class="COMMENT_HLP_USER">('+user+' "  lúc  '+date+')</span></div><div class="COMMENT_HLP_COMMENT">'+comment+'</div>';
		}
	}
	if(numbercomblog >0) {
		str = str +'<h3>Bình luận cho bài viết</h3>';
		for( i =0;i <numbercomblog;i++) {
			var item		= xmldoc.getElementsByTagName("commentblog")[i];
			var title		= item.getElementsByTagName("title")[0].childNodes[0].nodeValue;
			var comment		= item.getElementsByTagName("comment")[0].childNodes[0].nodeValue;
			var date		= item.getElementsByTagName("date")[0].childNodes[0].nodeValue;
			var user		= item.getElementsByTagName("user")[0].childNodes[0].nodeValue;
			var belong		= item.getElementsByTagName("belong")[0].childNodes[0].nodeValue;
			str = str + '<div class="COMMENT_HLP_A_ITEM">Tiêu đề: <span class="COMMENT_HLP_TITLE"> '+title+'</span> Trong bài: <span class="COMMENT_HLP_TITLE"> '+belong+'</span> <span class="COMMENT_HLP_USER">('+user+' "  lúc  '+date+')</span><div class="COMMENT_HLP_COMMENT">'+comment+'</div></div>';
		}
	}
	hien_thi_div('mess_display_userbonus',str);
}
function friend_display_func(news_friendlist){
	if(news_friendlist == '') hien_thi_div('friend_display_news_userbonus','');
	var xmldoc	 	= processStrXML(news_friendlist);//alert(news_friendlist);
	var friendlist 	= xmldoc.getElementsByTagName("friendlist")[0];//alert(news_friendlist);
	var friendnumber= friendlist.getElementsByTagName("friendnumber")[0].childNodes[0].nodeValue;
	var str='<h2>Tin tức bạn bè của tôi</h2>';
	var check = 0;
	if(friendnumber>0) {
		for (i=0;i < friendnumber; i++) {
			var friend		= friendlist.getElementsByTagName("friend")[i];
			var name		= friend.getElementsByTagName("name")[0].childNodes[0].nodeValue;
			var url			= friend.getElementsByTagName("url")[0].childNodes[0].nodeValue;
			var error		= friend.getElementsByTagName("error")[0].childNodes[0].nodeValue;
			if(error == 'No' ) {
				var blognew		= friend.getElementsByTagName("numberblog")[0].childNodes[0].nodeValue;
				var commentnew	= friend.getElementsByTagName("numbercom")[0].childNodes[0].nodeValue;
				var commentblognew= friend.getElementsByTagName("numbercomblog")[0].childNodes[0].nodeValue;
				if( (blognew+commentnew+commentblognew) == 0) {continue;}	// nếu bạn bè ko có gì mới thì ko hiển thị
				check = check + blognew+commentnew+commentblognew;
				str = str +'<h3><a href="'+url+'">' +name+' </a></h3><font color="red">'+name+'</font> có tin mới: ';
				if(blognew > 0) 	str = str + blognew+' bài viết, ';
				if(commentnew > 0) 	str = str +commentnew+ ' bình luận chung, ';
				if(commentblognew > 0) str = str +commentblognew+ 'bình luận cho bài viết mới';
				str = str + '.<br />';
				if(blognew > 0 ) {
					str = str +'<h4>Bài viết mới</h4>';
					for( i =0;i <blognew;i++) {
						var item		= friend.getElementsByTagName("blog")[i];
						var title		= item.getElementsByTagName("title")[0].childNodes[0].nodeValue;
						var introtext	= item.getElementsByTagName("introtext")[0].childNodes[0].nodeValue;
						var link		= item.getElementsByTagName("link")[0].childNodes[0].nodeValue;
						var created		= item.getElementsByTagName("created")[0].childNodes[0].nodeValue;
						var created_by	= item.getElementsByTagName("created_by")[0].childNodes[0].nodeValue;
						var hit			= item.getElementsByTagName("hit")[0].childNodes[0].nodeValue;
						var category	= item.getElementsByTagName("category")[0].childNodes[0].nodeValue;
                        var id      	= item.getElementsByTagName("id")[0].childNodes[0].nodeValue;
						str = str + 'Tiêu đề:<span class="CONTENT_TITLE"><a href="'+link+'"> '+title+'</a></span> <span class="CONTENT_DATE">('+created_by+' "  lúc  '+created+')</span></div><div class="CONTENT_TEXT">'+introtext+'</div>'+"<div id=blognew"+i+"><span class=link  onClick='Binhluan(\"blognew"+i+"\", \""+url+"\",\""+name+"\",\"blog\",\""+id+"\")'>Bình luận mới</span></div><div class='CONTENT_HR'></div>";
					}
				}
				//else str = str + 'Không có bài viết mới nào';
				
				if(commentnew>0) {
					str = str +'<h4>Bình luận blog</h4>'+"<div id=comment ><span class=link  onClick='Binhluan(\"comment\", \""+url+"\",\""+name+"\",\"comment\")'>Viết bình luận</span></div><br />";
					for( i =0;i <commentnew;i++) {
						var item		= friend.getElementsByTagName("commentfg")[i];
						var title		= item.getElementsByTagName("title")[0].childNodes[0].nodeValue;
						var comment		= item.getElementsByTagName("comment")[0].childNodes[0].nodeValue;
						var date		= item.getElementsByTagName("date")[0].childNodes[0].nodeValue;
						var user		= item.getElementsByTagName("user")[0].childNodes[0].nodeValue;
						str = str + '<div class="COMMENT_HLP_A_ITEM">Tiêu đề:<span class="COMMENT_HLP_TITLE"> '+title+'</span> <span class="COMMENT_HLP_USER">('+user+' "  lúc  '+date+')</span></div><div class="COMMENT_HLP_COMMENT">'+comment+'</div>';
					}
				}
				//else str = str +'Không có bình luận chung mới';
				
				if(commentblognew>0) {
					str = str +'<h4>Bình luận cho bài viết</h4>';
					for( i =0;i <commentblognew;i++) {
						var item		= friend.getElementsByTagName("commentblog")[i];
						var title		= item.getElementsByTagName("title")[0].childNodes[0].nodeValue;
						var belongid	= item.getElementsByTagName("belongid")[0].childNodes[0].nodeValue;
						var comment		= item.getElementsByTagName("comment")[0].childNodes[0].nodeValue;
						var date		= item.getElementsByTagName("date")[0].childNodes[0].nodeValue;
						var user		= item.getElementsByTagName("user")[0].childNodes[0].nodeValue;
						var belong		= item.getElementsByTagName("belong")[0].childNodes[0].nodeValue;
						str = str + '<div class="COMMENT_HLP_A_ITEM">Tiêu đề: <span class="COMMENT_HLP_TITLE"> '+title+'</span> Trong bài: <span class="COMMENT_HLP_TITLE"> '+belong+'</span> <span class="COMMENT_HLP_USER">('+user+' "  lúc  '+date+')</span><div class="COMMENT_HLP_COMMENT">'+comment+'</div></div>'+"<div id=commentblog"+i+" ><span class=link  onClick='Binhluan(\"commentblog"+i+"\", \""+url+"\",\""+name+"\",\"commentblog\",\""+belongid+"\")'>Bình luận</span></div><br />";
					}
					
				}
				//else str = str +'Không có bình luận chung mới';
			}
			else str = str + '<h2>' +name+' </h2><font color="red"> ' +name+' ở '+url+ '</font><h3><font color="red">'+error+'</font></h3><br />';
			str = str + '<hr style="color:red">';
		}
	}
	else str = str +'<h3>Bạn không có bạn bè</h3>Hãy gửi thư mời để kết bạn!';
	if(check == 0)  str = str +'<h3>Hàng xóm bạn không có gì mới!';
	hien_thi_div('friend_display_news_userbonus',str);
}

function checkLoginFriend(form){
	if(typeof form != 'object') form = document.getElementById(form);
    // Loop for each form value
    for(var i=0;i < form.elements.length;i++) {
        if(form.elements[i].name == 'urlfriend') friend_url = form.elements[i].value;
        if(form.elements[i].name == 'friendname') friend_name = form.elements[i].value;
    }
	w = 350;
    h = 200;
    LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
    TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
    var windowFearture = "dialogTop:" + TopPosition + "; dialogLeft:" + LeftPosition + "; dialogWidth:" + w + "; dialogHeight:" + h + "; center:yes; toolbar:no; menubar:no; location:no; resizable:yes; titlebar:yes";
    window.showModalDialog(friend_url+'xml.php?type=login&user='+friend_name+'&url='+weburl, "uploadPhoto", windowFearture);
    ajax_process(address,'name='+friend_name,'POST');
}
function Binhluan(namespan, friendurl,friendname,type,id) {
    var str = '<form method="POST"  id="form_'+namespan+'" onSubmit="return false;">';
    str = str + '<input type=hidden name=friendurl value="'+friendurl+'" />';
    str = str + '<input type=hidden name=namespan value="'+namespan+'" />';
    str = str + '<input type=hidden name=type value="'+type+'" />';
    str = str + '<input type=hidden name=friendname value="'+friendname+'" />';
    if(type != 'comment')     str = str + '<input type=hidden name=id value="'+id+'" />';
    str = str + '<input type=text name=title value="Hello" size=10 /><input type=text name=content value="" size=40 />';
    str = str + '<input type="button" value="Gửi" name="submit" onclick="vietBinhluan(\''+namespan+'\', getformvalue(\'form_'+namespan+'\') )" />';
    str = str + '</form>';
    hien_thi_div(namespan,str);
}
function vietBinhluan(namespan,str) {
	var posturl = address+'?type=postnew';
	hien_thi_div(namespan,loading_str );
    var ajax = new ajax_connect();
    ajax.open("POST",posturl,true);
    ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=UTF-8");
    ajax.onreadystatechange = function() {
        if(ajax.readyState == 4 && ajax.status == 200) {
        	alert(ajax.responseText);
        	//Result_login = ajax.responseText;
        	//action_login(Result_login);
        }
    }
    ajax.send(str);
}
function htmldecoder(s){
	s = s.replace('&lt;','<');
	return s;
}