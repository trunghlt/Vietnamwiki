<?php
      function trunc_title($post_id) {
            $sql = "SELECT post_subject
                        FROM posts_texts
                        WHERE post_id = '".$post_id."'";
            $result = mysql_query($sql) or die(mysql_error());
            $row = mysql_fetch_array($result);
            $title = ltrim($row["post_subject"]);
            $s = substr($title, 0, 29);
            if (strlen($title) > 30) $s .= "...";
            return $s;
      }
      
      function write_link($row) {
            echo "<tr><td class='menu'>";
            echo "<a href = 'viewtopic.php?id=".$row["target"]."' class='latestact'>";            
            echo $row["act_username"] . " " . $row["type"];
            if (($row["type"] == "create")||($row["type"]=="update")) echo "d ";
            if ($row["type"] == "comment") echo "ed on ";
            echo "<span class='title'>".trunc_title($row["target"])."</span>";
            echo "</a>";
            echo "</tr></td>";
      }
	  
      function write_link_2($row) {
            echo "<tr width='100%'><td class='menu'  >";
            echo "<a href = 'viewtopic.php?id=".$row["post_id"]."' class='latestact'>";
			$sql = "SELECT post_subject
					FROM posts_texts
					WHERE post_id = '".$row["post_id"]."'";
			$result = mysql_query($sql) or die(mysql_error());
			$p = mysql_fetch_array($result);             
			$title = ltrim($p["post_subject"]);
            $s = substr($title, 0, 29);
            if (strlen($title) > 30) $s .= "...";
            echo $s;
            echo "</a>";
            echo "</tr></td>";
      }	  
?>
<td valign="top" width="16%">
   <table style="border: 1px solid; border-color: #CCCCCC;" cellpadding="5px" cellspacing="0" width="100%">
    <tbody>
		<tr>
		<?php
			if (logged_in()) {  ?> 
				<td valign="top" width="100%">
				<?php include("Private_headlines.php"); ?>
				</td>
		<?php } ?>
		</tr>
		<tr>
		<td valign="top" width="100%">
			<?php include("public_headlines.php");?>	
		</td>
		</tr>
		<tr>
		<td valign="top" width="100%">
			<?php include("most_view_panel.php");?>	
		</td>
		</tr>		
	</tbody>
  </table>
</td> 