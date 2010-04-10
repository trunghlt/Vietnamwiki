<?php
class color{
//take id in db
	public $id;
//take value set_value in db
	public $set_value;
//take value color in mysql_query
	public $color;

/***************************************************************
Get result in database color
@id: can be ''.
Return array result
***************************************************************/	
	public function query($id="",$condition=""){
		$q = new db;
		if($id=="" && $condition==''){
			$q->query("select * from color order by color asc");
			if($q->n>0){
				while($r = mysql_fetch_assoc($q->re)){
					$row[] = $r;
				}
				return $row;
			}
		}
		else if($id!="" && $condition==''){
			$q->query("select * from color where id=$id");
			if($q->n>0){
				while($r = mysql_fetch_assoc($q->re)){
					$row[] = $r;
				}
				return $row;			
			}
		}
		else if($condition!=''){
			$q->query("select * from color where $condition");
			if($q->n>0){
				while($r = mysql_fetch_assoc($q->re)){
					$row[] = $r;
				}
				return $row;			
			}		
		}
	}

/***************************************************************
Update in database color
@id: id in color
@type: type table will be change
@page: update page
***************************************************************/	
	public function update($id,$type,$page){
		$q = new db;
		$count = 0;
		
		if($type == "test"){
			$q->query("SELECT * from color where id=$id");
				while($r1 = mysql_fetch_assoc($q->re))
					$arr = $r1;
			$q->query("SELECT * from color_setting");
			if($q->n > 0){
				while($r = mysql_fetch_assoc($q->re))
					$row[] = $r;	
				foreach($row as $value){
					$check_page1 = explode('-',$page);
					$check_page = explode('-',$value['page']);
					for($i = 0; $i < count($check_page1); $i++){
						if(in_array($check_page1[$i], $check_page))
						{
							$count = $count + 1;
						}
					}
					// if position have existed.
					if($count == 2 ){
						$q->query("UPDATE color_setting SET id_color = $id,color='$arr[color]' where id=$value[id]");
						break;
					}
					if($count == 1)
						$count=0;
				}
				if($count==0){
					$q->query("INSERT INTO color_setting(id_color,color,test,page) VALUES ($id,'$arr[color]',1,'$page')");
				}
			}
			else{
				$q->query("INSERT INTO color_setting(id_color,color,test,page) VALUES ($id,'$arr[color]',1,'$page')");
			}
		}
		else if($type == "main"){
			$q->query("UPDATE color_setting SET setting = 1");
		}
	}
	
	public function query_setting($id="",$condition=""){
		$q = new db;
		if($id=="" && $condition==''){
			$q->query("select * from color_setting");
			if($q->n>0){
				while($r = mysql_fetch_assoc($q->re)){
					$row[] = $r;
				}
				return $row;
			}
		}
	}	
	
}
?>