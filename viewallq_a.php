<?php
session_start();
$session_id = session_id();
include('core/common.php');
include('core/init.php');
include('core/classes.php');
include('core/session.php');
	$ip = $_SERVER['REMOTE_ADDR'];
	process($session_id, $ip);
include('core/filters.php');
include('header.php');
include('destination.php');
include('ajaxLoad.php');

?>
<style>
    #view_qanda{
        font-size: 14px;
    }
    #view_qanda > ul > li {
        border-bottom: double;
    }
.question{
    text-align: left;
    width: 800px;
    margin-bottom: 10px;
}
.phantrang ul{
    margin:0px;
    padding: 0px;
    list-style-type: none;
}
.phantrang ul li{
    display: inline;    
}
.phantrang ul li a{
    color: #CC0000;
    width: 50px;
}
</style>
<?php
    $type_sort = 1;
    $s = 0;
    if(isset($_GET["type_sort"])){
        if(is_numeric($_GET["type_sort"])){
            $type_sort = $_GET["type_sort"];
        }
    }
    if(isset($_GET["s"])){
        if(is_numeric($_GET["s"])){
            $s = $_GET["s"];
        }
    }
?>
    <td class="center" style="width:820px;">
		<div id="menuWrapper"><div id="toolbar"><?php getToolbarHTML();?></div></div>
		<div id="contentTable">
                    <div>
                        <h1>Questions and Answers</h1>
                        <div class="sort">
                            <select id="method_sort" onchange="load_qanda(<?php echo $s;?>);">
                                <option value="1" <?php if($type_sort==1) echo "selected";?> >Sort Question By Time</option>
                                <option value="2" <?php if($type_sort==2) echo "selected";?> >Sort Question By Like</option>
                            </select>
                        </div>
                        <div id="qanda" style="width:820px !important;"><div id="view_qanda"><!-- --></div></div>
                    </div>
		</div>
	</td>
</tr>
<tr>
	<td colspan=3>
		<?php include("footLinks.php");?>
	</td>
</tr>
</tbody>
</table>
<script type="text/javascript">
 jQuery(document).ready(function(){
        load_qanda(<?php echo $s;?>);
});
function signOut() {
	jQuery.post("/requests/logout.php", {},
				function(response) {
                                    set_value();
                                });
}
//Set value when user register successfully email
function set_value(){
    loadToolbar("toolbar");
    loadNotification();
    load_qanda(0);
}
//end
function submitLogin(dom,check) {
	jQuery.post("/requests/postLogin.php", jQuery("#"+dom).serialize(),
			function(response){
                                response = jQuery.trim(response);
				if(parseInt(response)==-2){
                                    jQuery("#dialog_notification").html("This user has been banned");
                                    dialog_notification.dialog('open');
					//alert("This user has been banned");
                                }
 				else if(response == 'false'){
                                    jQuery("#dialog_notification").html("Login's fail");
                                    dialog_notification.dialog('open');
                                   // alert("Login's fail");
                                }
                                else
				{
					if(response != '' && response != 'success'){
						document.getElementById('id_user').value = response;

                                                var str = jQuery("#"+dom).serialize().split("&");
                                                var name = str[0].split("=");
                                                jQuery("#name_user").val(name[1]);
						document.getElementById('editpost').value = 'search';
                                                jQuery('#FillEmailDialog').css('visibility','visible').dialog("open");
					}
					else if(response == 'success'){
                                                loginDialog.dialog("close");                                                
                                                set_value();
					}
				}
	});
}
    function load_qanda(id){
        jQuery('#questionDialog').remove();
        jQuery('#answerDialog').remove();
        jQuery("#Emailquestion").remove();
        jQuery.post("/requests/QandA.php",{start:id,num_row:10,type:jQuery("#method_sort").val(),sort:1,type_view:1},function(response){jQuery("#view_qanda").html(response);});
    }
    function question(){
       jQuery('#questionDialog').css('visibility','visible').dialog('open');
    }
    function answer(id){
 	jQuery('#answerDialog').css('visibility','visible').dialog('open');
        jQuery('#questionId').val(id);
    }
    function sortanswer(id,_type){
        jQuery.post("/requests/sortAnswer.php",{id_q:id,type:_type},function(response){                                    
                                    if(response!="" && response!=null){
                                        if(_type==2)
                                            jQuery("#q"+id).html(response);
                                        else if(_type==1){
                                            jQuery("#l_q_"+id).html(response);
                                        }
                                    }
                                });
    }
    function like(_id,_type,_v,_sort){
        jQuery.post("/requests/like.php",{id:_id,type:_type,value:_v},function(response){
                         if(_type==2)
                            sortanswer(_sort,2);
                         else if(_type==1)
                            sortanswer(_sort,1);
                    });
    }
</script>
<?php
include("forms/composeForm.php");
include("forms/loginForm.php");
include("forms/register_email.php");
include("forms/resetPass.php");
include("footer.php");
?>
