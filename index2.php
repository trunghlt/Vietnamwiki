<?php
include('core/common.php');
include('core/init.php');
include('core/classes.php');
include('core/session.php');
//include("core/classes/Color.php");
	$check_index = new IndexElement;
	if($_GET["index_id"]!='' && $check_index->query(Edition::filterId($_GET["index_id"]))==0)
		header("location:index.php");

include('preprocess.php');
include('redirect.php');
include('header.php'); 
include("ajaxLoad.php");
include('destination.php');
//change_template();
?>
<td class="center">	
<div style = "background: #EDEFF4; height: 28px;">
<div id="menuWrapper">
	<div id="toolbar"></div>
    </div>
</div>
<div id='col2'>
	<div id="contentTable">
	<?php include("viewdest.php");?>
	</div>
</div>
</td>
<td class="right">
    <?php include('listquestion.php');?>
</td>
</tr>
<tr>
<td colspan=3>
	<?php include("footLinks.php");?>
</td>
</tr>
</tbody></table>
<script language="javascript">
jQuery(function(){
	loadToolbar("toolbar");
});

function signOut() {
	jQuery.post("/requests/logout.php", {}, 
				function(response) {
                                        load_qanda(0);
					loadToolbar("toolbar");
                                        //jQuery(a[id="#link_add"]);
                                        jQuery('#check_method_login').val(0);
						if(document.getElementById('link_add').value == 1){
							document.getElementById('link_add').value = 0;
							document.getElementById('link_add').innerHTML = "<a onClick=\"jQuery('#loginDialog').css('visibility','visible').dialog('open');jQuery('#check_method_login').val(1);\">+ Add new topic</a>";
						}						
                                        loadNotification();
                                });
}
/*
function submitLogin() {	
	var loginForm = $("loginForm");
	loginForm.set("send", {	url: "requests/postLogin.php", evalScripts: true});
	loginForm.send();
	loginForm.get("send").addEvent("onComplete", function(response){
		loadToolbar("toolbar");
	});
}*/
//Set value when user register successfully email
function set_value(){
    loadNotification();
    loadToolbar("toolbar");
    load_qanda(0);
    if(jQuery('li').index(jQuery("#link_add")) != -1){
        if(document.getElementById('link_add').value == 0){
            document.getElementById('link_add').value = 1;
            document.getElementById('link_add').innerHTML = "<a onClick=\"jQuery('#composeDialog').css('visibility','visible').dialog('open')\">+ Add new topic</a>";
        }
    }    
}
//end
function submitLogin(dom,check) {	
	jQuery.post("/requests/postLogin.php", jQuery("#"+dom).serialize(), 
			function(response){
			
				if(response==-2)
					alert("This user has been banned");
 				else if(response == 'false'){
                                    alert("Login's fail");
                                }
				else{
					if(response != '' && response != 'success'){

                                                var str = jQuery("#"+dom).serialize().split("&");
                                                var name = str[0].split("=");
                                                jQuery("#name_user").val(name[1]);
                                                
                                                if(jQuery('input').index(jQuery("#check_method_login")) != -1){
                                                     if(jQuery("#check_method_login").val()==1){
                                                         jQuery("#editpost").val("index");
                                                     }
                                                }
                                                
						document.getElementById('id_user').value = response;

                                                jQuery('#FillEmailDialog').css('visibility','visible').dialog('open');
					}
					else if(response == 'success'){
                                                if(jQuery('input').index(jQuery("#check_method_login")) != -1){
                                                    if(jQuery("#check_method_login").val()==0){
                                                        var str = jQuery("#"+dom).serialize().split("&");
                                                        var name = str[0].split("=");
                                                        window.location="feed.php?username="+name[1];
                                                    }
                                                    else{
                                                        set_value();
                                                    }
                                                }
                                                else{
                                                    var str = jQuery("#"+dom).serialize().split("&");
                                                    var name = str[0].split("=");
                                                    window.location="feed.php?username="+name[1];                                                   
                                                }
                                            }                                        
				}
	});
}

</script>
<?php
include("forms/composeForm.php");
include("forms/loginForm.php");
include("forms/register_email.php");
include("footer.php");
?>
