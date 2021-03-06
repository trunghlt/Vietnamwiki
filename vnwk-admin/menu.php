<?php
	include ("projax/projax.php");
	include("db.php");
	include("common.php");
	include("session.php");	
	session_start();
	process(session_id(), myip());
	
	if (!logged_in()) header("location: login.php");
	$q = new db;
	$projax= new Projax();
	if( isset($_GET["action"]) ) {
	
		$action = $_GET["action"];
		
		if ($action == "delete") {
			$id = $_GET["id"];

			$sql = "SELECT ord
					FROM destinations
					WHERE id = ".$id;
			
			$q->query($sql);
			$r = mysql_fetch_array($q->re);
			$c = $r["ord"];
			
			//reorder
			$sql = "UPDATE destinations
					SET ord = ord - 1, old_ord = old_ord - 1
					WHERE (ord > ".$c.")";
			
			$q->query($sql);
			
			//delete 					
			$sql = "DELETE FROM destinations
					WHERE id = " . $id;
			
			$q->query($sql);
		}
		
		if ($action == "add") {
			$name = $_POST["name"];
			$sql = "SELECT MAX(ord) as 'maxord'
					FROM destinations";
			
			$q->query($sql);
			if ($q->n > 0) {
				$r = mysql_fetch_array($q->re);
				$n = $r["maxord"] + 1;
			}
			else {
				$n = 0;
			}
			$sql = "INSERT INTO destinations
					(EngName, ord, old_ord)
					VALUE ('".$name."','".$n."','".$n."')";
			
			$q->query($sql);		
		}
		
		if ($action == "order") {
			$order = $_POST['list'];
			
			for ($i=0; $i < sizeof($order); $i++) {
				$sql = "UPDATE destinations
						SET ord = ".$i."
						WHERE old_ord = ".$order[$i];
				$q->query($sql);
			}		
			$sql = "UPDATE destinations
					SET old_ord = ord";
			
			$q->query($sql);
		}
	}

	$sql = "SELECT * 
			FROM destinations
			ORDER BY ord";
	
	$q->query($sql);	
?>
<script src="projax/js/prototype.js" type="text/javascript"></script>
<script src="projax/js/scriptaculous.js" type="text/javascript"></script>
<link href="admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript">
	function confir(id){
		if(window.confirm('Do You want delete this destination?'))
		{
			location.href = 'control.php?action=delete&id='+id;
		}
	}
</script>
<body id='dest_menu'>
<div id="dest">
<h3>Destinations</h3>
<ul id="list">
	<?php 
	$i = 0;
	while ($r = mysql_fetch_array($q->re)) {	?>
		<li id="item_<?php echo $i?>">
			<a href='#' onclick='confir(<?php echo $r["id"]?>);'>x</a>
			<?php echo $r["ord"]?>
			<?php echo $r["EngName"]?>
			<a href="index_menu.php?id=<?php echo $r["id"] ?>" target="index_menu">(i)</a>
		</li>
		<?php 
		$i++;
	}?>
</ul>
<?php echo $projax->sortabe_element('list',array('update'=>'dest','complete'=>$projax->visual_effect('highlight','list') , 'url'=>'menu.php?action=order'));?>
	<form action="control.php?action=add" method="post">
		<input type="text" id="name" name="name" />
		<input type="submit" value="Add index" />
	</form>
</div>
</body>