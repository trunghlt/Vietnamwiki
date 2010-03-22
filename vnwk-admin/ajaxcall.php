<?php
	include ("projax/projax.php");
	include("db.php");
	include("common.php");
	include("session.php"); 
	session_start();
	process(session_id(), myip());
	
	if (!logged_in()) header("location: login.php");	$projax= new Projax();
	$q = new db;
	$task=isset($_GET['task'])?$_GET['task']:'view';
	
	$id = $_GET['id'];
	
	if (isset($_GET["action"])) {
		$action = $_GET["action"];
		if ($action == "add") {
			$name = $_POST["name"];

			$sql = "SELECT COUNT(*) as n
					FROM index_menu
					WHERE dest_id = ".$id;
			$q->query($sql);
			$r = mysql_fetch_array($q->re);
			$num = $r["n"];
			$n = $num;
			
			$sql = "INSERT INTO index_menu
					(name, dest_id, ord, old_ord)
					VALUE ('".$name."', '".$id."', '".$n."', '".$n."')";
			
			$q->query($sql);
		}
		
		if ($action == "delete") {
			$index_id = $_GET["index_id"];

			$sql = "SELECT ord
					FROM index_menu
					WHERE id = ".$index_id;
			
			$q->query($sql);
			$r = mysql_fetch_array($q->re);
			$c = $r["ord"];
			
			//reorder
			$sql = "UPDATE index_menu
					SET ord = ord - 1, old_ord = old_ord - 1
					WHERE (dest_id = ".$id.") AND (ord > ".$c.")";
			
			$q->query($sql);
			
			//delete 					
			$sql = "DELETE FROM index_menu
					WHERE id = " . $index_id;
			
			$q->query($sql);

		}
		//lock
		if ($action == "tg_lock") {
			$index_id = $_GET["index_id"];
			$sql = "UPDATE index_menu
					SET locked = 1 - locked
					WHERE id = ".$index_id;
			
			$q->query($sql);
			echo $projax->sortabe_element('indexlist',array('update'=>'lbl',
											'complete'=>$projax->visual_effect('highlight','test') , 
											'url'=>'ajaxcall.php?action=order&indexlist='.$id,
											'constraint' => 0));
		}		
		if ($action == "order") {
			$order = $_POST["indexlist"];
			for ($i=0; $i < sizeof($order); $i++) {
				$sql = "UPDATE index_menu
						SET ord = ".$i."
						WHERE (dest_id = '".$id."') AND (old_ord = '".$order[$i]."')";
				$q->query($sql);
			}		
			$sql = "UPDATE index_menu
					SET old_ord = ord
					WHERE dest_id='".$id."'";
			
			$q->query($sql);			
		}
	}
?>
<script src="projax/js/prototype.js" type="text/javascript"></script>
<script src="projax/js/scriptaculous.js" type="text/javascript"></script>
<link href="admin.css" rel="stylesheet" type="text/css" />
<!--Confirm-->
<script type="text/javascript" language="javascript">
	function confir(id,index_id){
		if(window.confirm('Do you want delete this index?'))
		{
			location.href = "index_menu.php?action=delete&id="+id+"&index_id="+index_id;
		}
	}
</script>
<body id="index_menu">
<div id='lbl'>
<h3>Index menu</h3>
<?php
	$sql = "SELECT * FROM index_menu
			WHERE dest_id = '".$id."'
			ORDER BY ord";
	
	$q->query($sql);
	$i = 0;
?>
	<ul id='indexlist'>
<?php while ($row = mysql_fetch_array($q->re)) {	?>
		<li id="item_<?php echo $i?>">
			<a href='#' onClick="confir(<?php echo $id?>,<?php echo $row["id"]?>);">x</a>
			<?php echo $row["name"]; 			
			$i++; ?>
			<a href='#' onClick="javascript:tlock(<?php echo $id?>,<?php echo $row["id"]?>);" ><?=$row['locked']? 'lock':'unlock'?></a>
		</li>
<?php } ?>
	</ul>
	
	<?php echo $projax->sortabe_element('indexlist', array('update'=>'lbl','complete'=>$projax->visual_effect('highlight','indexlist') , 'url'=>'index_menu.php?action=order&id='.$id)); ?>
	<form action="index_menu.php?action=add&id=<?php echo $id?>" method="post">
		<input type="text" id="name" name="name" />
		<input type="submit" value="Add index" />
	</form>
</div>
</body>
<script>
	function tlock(x,id) {
		//call Ajax delete function
		new Ajax.Updater(	'lbl',
							'index_menu.php?action=tg_lock&id='+x+'&index_id='+id,
							{evalScripts:true});
	
		return true;
			
	}
</script>