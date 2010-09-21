<?php
require_once("ajaxLoad.php");
//require_once("widgets/GoogleTranslate.php");
update_current_page(myip(),selfURL());
$page = isset($_GET["page"])? $_GET["page"] : 1;

$page = pagePostFilter($page);		

function get_post($id){
	$memcache = new Memcache;
	$memcache->connect("127.0.0.1", 11211);
	$currentPostElement = new PostElement;
	$currentPostElement->query($id);
	$post = array('post_subject'=>$currentPostElement->title,'post_text'=>$currentPostElement->content,
				  'reference'=>$currentPostElement->reference,'authorUsername'=>$currentPostElement->authorUsername,
				  'indexId'=>$currentPostElement->indexId,'locked'=>$currentPostElement->locked,
				  'summary'=>$currentPostElement->summary,'smallImgURL'=>$currentPostElement->smallImgURL,
				  'bigImgURL'=>$currentPostElement->bigImgURL,'id'=>$currentPostElement->id
				 );
	$memcache->set("post_".$id,$post);
	return $post;
}

$post = $memcache->get("post_".$post_id);
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
	
	//title
	echo "<h1>". HtmlSpecialChars($title) . "</h1>";      
	
	echo "<div id='postContent_relative'>";
		echo $s;
	echo "</div";
	
	//reference
	if($reference!='')	{
		$refTokens = preg_split("/\n/", $reference);?>
		<br/>
		<span onclick="jQuery('#refList').toggle()" style='cursor: pointer; color:black; font-weight: bold; font-size:9pt;margin-top:10px;'> <img src="css/images/bg/arrow.jpg"/> Reference</span> 
		<ul id="refList" class="refList">
		<?php foreach ($refTokens as $t) { ?>
			<li><?php echo htmlspecialchars($t)?></li> 
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
	loadEdittingRibbon(<?php echo $post_id?>, "ribbon");
	loadEditorList(<?php echo $post_id?>, "editorList","");
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
					loadEdittingRibbon(<?php echo $post_id?>, "ribbon");
					jQuery('#field_not_login_comment').html("Email :<br /><input class='field' name='fill_email_comment' id='fill_email_comment' type='text' style='width:250px' value=''/><br />Name :<br /><input class='field' name='fill_name_comment' id='fill_name_comment' type='text' style='width:250px' value=''/><br /><input class='field' name='check_login_comment' id='check_login_comment' type='hidden' value='1'/>");
				});
}

function submitLogin(dom,check) {	
	jQuery.post("/requests/postLogin.php", jQuery("#"+dom).serialize(), 
			function(response){
				if(response == -2)
				{
					alert("This user has been banned");
				}
				else
				{
					if(response != '' && response != 'success'){
						loadToolbar("toolbar");
						loadEdittingRibbon(<?php echo $post_id?>, "ribbon");
						document.getElementById('id_user').value = response;
						if(check==2){
							document.getElementById('editpost').value = 'editpost';
						}
						jQuery('#field_not_login_comment').html("<input class='field' name='check_login_comment' id='check_login_comment' type='hidden' value='2'/>");						
						jQuery('#FillEmailDialog').css('visibility','visible').dialog('open');
					}
					else if(response == 'success'){
						loadToolbar("toolbar");
						if(check==2){
							edit_login.dialog('close');
							jQuery('#editDialog').css('visibility','visible').dialog('open');
						}
						loadEdittingRibbon(<?php echo $post_id?>, "ribbon");
						jQuery('#field_not_login_comment').html("<input class='field' name='check_login_comment' id='check_login_comment' type='hidden' value='2'/>");
                                                load_qanda(0);
					}
					else
					{
						jQuery('#field_not_login_comment').html("<input class='field' name='check_login_comment' id='check_login_comment' type='hidden' value='2'/>");

						load_qanda(0);
                                                if(check==2)
							edit_login.dialog('close');							
					}
				}
	});
}

</script>

<?php include("commentListPainter.php"); ?>

