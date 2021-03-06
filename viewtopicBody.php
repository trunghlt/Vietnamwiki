<?php
require_once("ajaxLoad.php");
//require_once("widgets/GoogleTranslate.php");
update_current_page(myip(),selfURL());
$page = isset($_GET["page"])? $_GET["page"] : 1;

$page = pagePostFilter($page);		

function get_post($id){
	//$memcache = new Memcache;
	$currentPostElement = new PostElement;
	$currentPostElement->query($id);
	$post = array('post_subject'=>$currentPostElement->title,'post_text'=>$currentPostElement->content,
				  'reference'=>$currentPostElement->reference,'authorUsername'=>$currentPostElement->authorUsername,
				  'indexId'=>$currentPostElement->indexId,'locked'=>$currentPostElement->locked,
				  'summary'=>$currentPostElement->summary,'smallImgURL'=>$currentPostElement->smallImgURL,
				  'bigImgURL'=>$currentPostElement->bigImgURL,'id'=>$currentPostElement->id
				 );
	Mem::$memcache->set("post_".$id,$post);
	return $post;
}

$post = Mem::$memcache->get("post_".$post_id);
if($post == NULL){
	$post = get_post($post_id);	 
}
/*
//increase page_view
if ($page == 1) {
	mysql_query("UPDATE posts 
				SET page_view='".($row["page_view"]+1)."' 
				WHERE post_id='".$post_id."'") or die(mysql_error());
}
*/
/*$post = $memcache->get("post");

if($post == NULL){
	$sql = "SELECT post_subject, post_text, reference
			FROM posts_texts
			WHERE post_id='".$post_id."'";
	$re2 = mysql_query($sql) or die(mysql_error());
	
	$post = mysql_fetch_array($re2);
	$memcache->set("post",$post);
}*/
$title = $post['post_subject'];
$content = $post['post_text'];
if($post['reference']!=NULL) 
    $reference = $post['reference'];
else 
    $reference = '';
?>

<div id="postContent">
	<?php 	
	//get number of pages
	$content = htmlspecialchars_decode($content, ENT_QUOTES);
	$content = str_replace("|", "&", $content);		 
	$content = str_replace('\"', '"', $content);
	$content = str_replace("\'", "'", $content);
	$ps = '|';

	$page_break = '<hr />';
	$content = str_replace($page_break, $ps, $content);
	
	$page_break = '<hr style="width: 100%; height: 2px;" />';
	$content = str_replace($page_break, $ps, $content);
	
	$pnum = substr_count($content, $ps) + 1;

	//content
	function strpos_n($x, $c, $n) {
		$p = strpos($x, $c);
		for ($i = 2; $i <= $n; $i++) {
			$p = strpos($x, $c, $p + 1);
		}
		return $p;
	}
	$start = ($page == 1)? 0 : strpos_n($content, $ps, $page - 1) + 1;
	$end = ($page == $pnum)? strlen($content) - 1 : strpos_n($content, $ps, $page) - 1;
	$len = $end - $start + 1;
	$s = substr($content, $start, $len);
	
	if (isset($_REQUEST["lang"])) {
		$lang = $_REQUEST["lang"];
		$notification = "Please be aware that the translation which is powered by Google Translator can be slightly different from its original content !";
		$re = translateTexts(array($title, $s, $notification), 'en', $lang);
		$title = $re[0];
		$s = $re[1];
		$translatedNotif = $re[2]; ?>
		<span style="font-size: 9px; color: #CC0000;"><?php echo $translatedNotif; ?></span>
		<?php
	}
       // echo "<div id='flash_hn'></div>";
    /*
    if ($post_id == 29){ $panorama = "http://hanoi1000.vn/index.html?view.hlookat=0.00&view.vlookat=0.00&view.fov=1.0"; }
    if ($post_id == 11){ $panorama = "http://panorama.vn/saigon/240/";}
    */    
    ?>
    <?if (isset($panorama)) {?><div style="height: 180px; overflow: hidden; margin-bottom: 10px;"><iframe src ="<?=$panorama?>" width="100%" height="300px" frameborder="0" ><p>Your browser does not support iframes.</p></iframe></div><?}?>
	
<?	if ($post_id == 32) { //vietnamwiki.net facebook page
		$like_url = "http%3A%2F%2Fwww.facebook.com%2Fpages%2FVietnamWikinet%2F161569890530861%3Fv%3Dwall";
	}
	else if ($post_id == 9) { //Hoi An facebook page
		$like_url = "http%3A%2F%2Fwww.facebook.com%2Fpages%2FHoi-An%2F360046378805";
	}
	else {
		$like_url = urlencode("http://www.vietnamwiki.net".getPostPermalink($post_id));
	}
?>

	<div class="title_wrapper">
	    <span class="header"><?=$title?></h1>
	    
        <?//should be deprecated by a php function
    	$clean["postId"] = $post_id;
        $currentUser = new User;
    	$currentUser->query(myUser_id(myip())); 
        ?>
        <?if (logged_in() || chkFbLoggedIn()) {
            if ((!$postElement->locked)||($currentUser->level == 1)|| User::check_user_post($currentUser->id, $clean["postId"])) { ?>
		        <a class='link' id='edit_link' onClick='editClick()'>[Edit]</a>			
	        <?}
		} else { ?>
            <a class='link' onClick="jQuery('#type_login').val(2);loginDialog.css('visibility','visible').dialog('open')">[Edit]</a>		
        <?}?>
        
	    <iframe src="http://www.facebook.com/plugins/like.php?href=<?=$like_url?>&amp;layout=button_count&amp;show_faces=true&amp;width=90&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px; float:right;" allowTransparency="true"></iframe>
	    <div style="clear: both"></div>
	</div>
	<?
	echo "<div id='postContent_relative'>";
		echo $s;
	echo "</div>";
	
	//reference
	if($reference!='')	{
		$refTokens = preg_split("/\n/", $reference);?>
		<br/>
		<span onclick="jQuery('#refList').toggle()" style='cursor: pointer; color:black; font-weight: bold; font-size:9pt;margin-top:10px;'> <img src="css/images/bg/arrow.jpg"/> Reference</span> 
		<ul id="refList" class="refList">
		<?php foreach ($refTokens as $t) {
                        $t = htmlspecialchars_decode($t, ENT_QUOTES);
                        $t = str_replace("|", "&", $t);
                        $t = str_replace('\"', '"', $t);
                        $t = str_replace("\'", "'", $t);
                 ?>
			<li><?php echo $t;?></li>
		<?php } ?>
		</ul>
		
	<?php } ?>
</div>

<?php 
	if ($page < $pnum) {
		?>
		 <br/><span class="style1">(Continue next page...)</span><br/>
		<?php
	}
	
	?><br /> <?php
	//page division
			function writelink($s, $id, $p) {
				echo '<a class="link" href="viewtopic.php?id='.$id.'&page='.$p.'" >'.$s.'</a>';									
			}
	if ($pnum > 1) {
		writelink("<< ", $post_id, 1);
		for ($i = 1; $i <= $pnum; $i++) {

			if ($i - $page !== 0) { writelink($i, $post_id, $i); }
			else  { echo '<b>'.$i.'</b>'; }
				
			if ($i < $pnum) echo " | ";
		}
		writelink(" >>", $post_id, $pnum);
	}
	
	// Bookmarks
	?>
	<div class="bookmarks">
	<ul>
		<li> 
			<div class="delicious"></div>
			<a href="http://del.icio.us/post?url=<?php echo selfURL()?>;title=<?php echo $title?>" title="Post this story to Delicious" id="delicious">Delicious</a>			
		</li>
		<li> 
			<div class="digg"></div>
			<a href="http://digg.com/submit?url=<?php echo selfURL()?>;title=<?php echo $title?>" title="Post this story to Digg" id="digg">Digg</a> 
		</li>
		<li> 
			<div class="reddit"></div>
			<a href="http://reddit.com/submit?url=<?php echo selfURL()?>;title=<?php echo $title?>" title="Post this story to reddit" id="reddit">reddit</a> 
		</li>
		<li> 
			<div class="stumbleupon"></div>
			<a href="http://www.stumbleupon.com/submit?url=<?php echo selfURL()?>;title=<?php echo $title?>" title="Post this story to StumbleUpon" id="stumbleupon">StumbleUpon</a> 
		</li>
		<li>
			<div class="facebook"></div>
			<a href="http://www.facebook.com/sharer.php?u=<?php echo selfURL()?>" title="Post this story to Facebook" id="facebook">Facebook</a>
		</li>
	</ul>
	</div>
	<?php 
	
	//tool bar		
	if (logged_in()) {
		$username = $post['authorUsername'];//$currentPostElement->authorUsername;
		$CurrentUser = $memcache->get("CurrentUser");
		if($CurrentUser == NULL){
			$CurrentUser = myUsername(myip());
			$memcache->set("CurrentUser",$CurrentUser);
		}
		$user_info = $memcache->get("user_info");
		if($user_info == NULL){
			$u = new User;
			$user_info = $u->query_username($CurrentUser);
			$memcache->set("user_info",$user_info);				
		}
		$userId = $user_info["id"];
	}
	else {
		$userId = -1;
	}
?>
 	
<div id="ribbon" align="right" style="width: 100%; background: #E6E6E6; clear:right;">&nbsp;</div>
<div id="editorList" class="editorInfo"></div>

<script language="javascript">
jQuery(document).ready(function(){
        loadEditorList(<?php echo $post_id?>, "editorList","");
	loadEdittingRibbon(<?php echo $post_id?>, "ribbon");
});

function editClick() {
	//$("textEditFrame").contentWindow.location.reload(true);
	jQuery('#editDialog').css('visibility','visible').dialog('open');
}

function signOut() {
	jQuery.post("/requests/logout.php", {}, 
				function(response) {
					loadToolbar("toolbar");
                                        load_qanda(0);
                                        loadNotification();
					loadEdittingRibbon(<?php echo $post_id?>, "ribbon");
					jQuery('#field_not_login_comment').html("Email :<br /><input class='field' name='fill_email_comment' id='fill_email_comment' type='text' style='width:250px' value=''/><br />Name :<br /><input class='field' name='fill_name_comment' id='fill_name_comment' type='text' style='width:250px' value=''/><br /><input class='field' name='check_login_comment' id='check_login_comment' type='hidden' value='1'/>");
                                        jQuery('#type_login').val(1);
                                        jQuery('#editpost').val('login');
                                });
}
/*function getflashhn() {
	jQuery.post("requests/get_flash_hn.php",{},
				function(response) {
					jQuery('#flash_hn').html(response);
				});
}*/
//Set value when user register successfully email
function set_value(){
    loadToolbar("toolbar");
    loadEdittingRibbon(<?php echo $post_id?>, "ribbon");
    jQuery('#field_not_login_comment').html("<input class='field' name='check_login_comment' id='check_login_comment' type='hidden' value='2'/>");
    load_qanda(0);
    loadNotification();
}
//end
function submitLogin(dom,check) {	
	jQuery.post("/requests/postLogin.php", jQuery("#"+dom).serialize(), 
			function(response){
                                response = jQuery.trim(response);
				if(parseInt(response)==-2)
				{
                                    jQuery("#dialog_notification").html("This user has been banned");
                                    dialog_notification.dialog('open');
				}
				else if(response == 'false'){
                                    jQuery("#dialog_notification").html("Login's fail");
                                    dialog_notification.dialog('open');
                                }
                                else
				{
					if(response != '' && response != 'success'){
						document.getElementById('id_user').value = response;
                                                
                                                var str = jQuery("#"+dom).serialize().split("&");
                                                var name = str[0].split("=");
                                                jQuery("#name_user").val(name[1]);
                                                
						if(check==2){
							document.getElementById('editpost').value = 'editpost';
						}
                                                
                                                jQuery('#FillEmailDialog').css('visibility','visible').dialog("open");
					}
					else if(response == 'success'){
						if(check==2){                                                        
							//edit_login.dialog('close');
                                                        loginDialog.dialog("close");
							jQuery('#editDialog').css('visibility','visible').dialog('open');
                                                        set_value();
						}
                                                else{                                                    
                                                    var str = jQuery("#"+dom).serialize().split("&");
                                                    var name = str[0].split("=");
                                                    window.location="feed.php?username="+name[1];
                                                }
                                               
					}
					else
					{
						//jQuery('#field_not_login_comment').html("<input class='field' name='check_login_comment' id='check_login_comment' type='hidden' value='2'/>");
						//load_qanda(0);
                                                //if(check==2)
							//edit_login.dialog('close');
					}
				}
	});
}

</script>

<?php include("commentListPainter.php"); ?>

