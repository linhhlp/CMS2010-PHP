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
form_login_str		= form_login_str + '<label for="name">Tên của bạn</label><input name="name" value="" type="text"/><br /> ';
form_login_str 		= form_login_str + '<label for="submit"><input type="button" value="Tham gia" name="submit" ';
form_login_str		= form_login_str + 'onclick="ajax_process(address,\'POST\', getformvalue(\'form_userbonus\') )"/></label>';
form_login_str 		= form_login_str + '</form>';

var form_member_login_str = '<div class="canh_bao_userbounus">Bạn đăng nhập với tên của 1 thành viên</div><br>';
form_member_login_str = form_member_login_str +  'Nhập password (đăng nhập như thành viên) Nhấn cancel để chọn tên đăng nhập mới';
    // Hien dang nhap cho password
form_member_login_str = form_member_login_str + '<form method="POST" id="form_userbonus" name="form_userbonus" onSubmit="return false;">';
form_member_login_str = form_member_login_str + '<input name="pass" value="" type="password"/>';
form_member_login_str = form_member_login_str + '<input type="button" value="Tham gia" name="submit" onclick="ajax_process('+ "'" +address + " ','POST', getformvalue('form_userbonus')" + ')"/> ';
form_member_login_str = form_member_login_str + '</form> ';
    // Hien Cancel
form_member_login_str = form_member_login_str + '<input type="button" onclick="hien_thi_div(mess_userbonus,form_login_str);" value="Cancel"> ';
//END Define variables

function action_login(Result_login) {
	if(Result_login == 'code_return_4' || Result_login == 'code_return_1') {
		logged = 0;
	}
	else if( Result_login == 'code_return_3') {
		hien_thi_div('mess_userbonus',form_member_login_str);
		logged = 3;
	}
	else if( Result_login != ''){
		logged = 1;
		welcome_str = Result_login;
	}
	
	if(logged == 0) {
		hien_thi_div('mess_userbonus',form_login_str);
	}
	else if(logged == 1){
        str = 'Chào mừng <span class="userbounus_username">' + welcome_str + '</span>';
        str += '<input type="button" onclick="ajax_process(address + \'?login=logout\',\'GET\');" value="Log-out"> '
	    hien_thi_div('mess_userbonus',str);
		setTimeout("formykun()",100);
    }
}
function formykun() {
	var link = url + '/userbonus.php?action=checknew';
	var bonus;
	var ajax = new ajax_connect();
    ajax.open("GET",link);
    ajax.onreadystatechange = function() {
        if(ajax.readyState == 4 && ajax.status == 200) {
        	bonus = ajax.responseText;
        	if(bonus != "") {
        		alert(bonus);
        		if(confirm("Đọc tin ngay bây giờ?")) {
        			window.parent.location= weburl + "userbonus";
        		}
        	}
        }
    }
    ajax.send(null);
    
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
}