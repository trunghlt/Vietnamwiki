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
		if($this->re){
			while($row = mysql_fetch_assoc($this->re))
				$row2[] = $row;
		}
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
	public function orderby($order,$range = ''){

			$this->order = "$order $range";
	}	
/***************************************************************
Set value $this->limit
***************************************************************/
	public function limit($start,$length){

			$this->limit = "$start ,$length";
	} 
/***************************************************************
command select can change by option
Return value in array
***************************************************************/				 	
	public function select($col,$table,$where){
		if($col == ''){	
			$col = '*';
		}
		else{
			if(is_array($col)){
					$col = implode(' , ',$col);
				}
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