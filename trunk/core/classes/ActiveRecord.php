<?php
/***************************************************************
Class connect database by option
***************************************************************/
class Active{
//take result in mysql_query
	public $re;
//condition in query	
	public $order;
//condition in query
	public $limit;
	
/***************************************************************
Init class active
***************************************************************/	
	public function __construct(){
		$this->limit = '';
		$this->order = '';
	}
	
/***************************************************************
Get result in query
Return array result
***************************************************************/

	public function get_result(){
		if($this->re!=null){
                    if(mysql_num_rows($this->re)>0){
			while($row = mysql_fetch_assoc($this->re))
				$row2[] = $row;
                    }
                    else
                       $row2=0;
		}
                else
                    $row2=0;
		return @$row2;
	}

/***************************************************************
command query

***************************************************************/
	public function query($str){
		$this->re = mysql_query($str);
	}
	
/***************************************************************
Get num row in query
Return num 
***************************************************************/
	public function get_num(){
		if($this->re){
			$num = mysql_num_rows($this->re);
			return @$num;
		}
		
	}
/***************************************************************
delete table
 * $table :table's name
 * $where :condition
***************************************************************/
	function delete($table, $where=''){
                if($where!='')
                    $where = "where $where";
		$this->query("delete from $table $where");
	}
/***************************************************************
Update table
 * $table :table's name
 * $arr :array include record's name and value
 * $where :condition
***************************************************************/
	function update($table,$arr,$where){
		foreach($arr as $key=>$value){
			$str[] = "$key = '$value'";
		}
		$str2 = implode(",",$str);
		$this->query("update $table set $str2 where $where");
	}
/***************************************************************
Add table
 * $table :table's name
 * $arr :array include record's name and value
***************************************************************/
	function add($table,$arr){
		foreach($arr as $key=>$value){
			$arr1[] = "$key";
                        if(is_string($value))
                            $arr2[] = "'$value'";
                        else
                            $arr2[] = "$value";
		}
		$str = implode(",",$arr1);
                $str1 = implode(",",$arr2);
		$this->query("Insert into $table($str) values ($str1)");
                return @mysql_insert_id();
	}
/***************************************************************
Free old result
***************************************************************/
	public function free(){
		if($this->re){
			@mysql_free_result($this->re);
		}
	}

/***************************************************************
Set value $this->order
***************************************************************/
	public function orderby($order){

			$this->order = "$order";
	}	
/***************************************************************
Set value $this->limit
***************************************************************/
	public function limit($str){

			$this->limit = "$str";
	} 
/***************************************************************
command select can change by option
Return value in array
***************************************************************/				 	
	public function select($col='',$table='',$where=''){
		if($col == ''){	
			$col = '*';
		}
		else{
			if(is_array($col)){
					$col = implode(' , ',$col);
				}
			else
				$col = $col;
		}
		if($where != ''){
			if(is_array($where)){
				foreach($where as $key=>$value){
					if(is_string($value))
						$r[] = "$key='$value'";
					else
						$r[] = "$key=$value";
				}
				$where = implode(" and ",$r);
				$where = "where $where";
			}
			else{
				$where = "where $where";
			}
		}
		if($this->order!=''){
			$order = 'order by '. $this->order;
			$this->order='';
		}
		else
			$order = '';
		if($this->limit!=''){
			$limit = 'limit '. $this->limit;
			$this->limit='';
		}
		else
			$limit = '';		
	
		$sql = "select $col from $table $where $order $limit";
		$this->query($sql);

		return $this->get_result();
	}
}
?>