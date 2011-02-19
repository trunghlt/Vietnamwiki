<?php
class Solr{
	private $solr_path = array(0=>"answer",1=>"question",2=>"review",3=>"article");
	//private $host = "localhost";
	//private $port = "8083";
        
	private function init_solr(){
            global $SERVER_SOLR,$PORT_SOLR, $HOST_SOLR;
            return new Apache_Solr_Service( $HOST_SOLR, $PORT_SOLR,  "/".$SERVER_SOLR);
	}
	public function check_exists_data($num_solr){
		$solr = $this->init_solr();
		if ( !@$solr->ping() ) {
			return -1;
		}
		$response = $solr->search( "cat:".$this->solr_path[$num_solr]);
		if ( $response->getHttpStatus() == 200 ) {
			if ( $response->response->numFound == 0 )
					return 0;
			else
				return 1;
		}
		else
			return -1;
	}
	/*
	*add solr
	*$num_solr:
	*	0: answer, 1: question, 2: review, 3:article.
	*	return service
	*$arr : data (is array)
	*/
	private function add_solr($num_solr,$arr ){
		if(is_array($arr) && is_numeric($num_solr)){
			$solr = $this->init_solr();

			if ( ! @$solr->ping() ) {
				return 0;
			}
			$documents = array();

			foreach ( $arr as $item => $fields ) {
				$part = new Apache_Solr_Document();

				foreach ( $fields as $key => $value ) {
					if ( is_array( $value ) ) {
						foreach ( $value as $datum ) {
							$part->setMultiValue( $key, html_entity_decode($datum) );
						}
					}
					else {
						$part->$key = html_entity_decode($value);
					}
				}

				$documents[] = $part;
			}

		//
		//
		// Load the documents into the index
		//
			try {
				$solr->addDocuments( $documents );
				$solr->commit();
				$solr->optimize();
                                return 1;
			}
			catch ( Exception $e ) {
				return 0;
			}
		}
		return 0;
	}
	/*
	*Query data and strore in solr
	*$num_solr:
	*	0: answer, 1: question, 2: review, 3:article.
	*	return service
	*$arr : data (is array)
	*/
        public function add_data($num_solr){
            $q = new Db;
            if(!is_numeric($num_solr)){
                if($num_solr > 3 || $num_solr < 0)
                    return 0;
            }
            switch($num_solr){
				case 0:$q->query("select * from answer");break;
                case 1:$q->query("select * from question");break;
                case 2:$q->query("select * from reviews");break;
                case 3:$q->query("select * from posts,posts_texts where posts_texts.post_id = posts.post_id");break;
            }
			$parts = array();
			$i = 0;
            if($q->re){
				if($q->n>0){
					while($r = mysql_fetch_assoc($q->re)){
						if($num_solr==3){
							$parts[$i]['id'] = "article_".$r['post_id'];
							$parts[$i]['cat'] = $this->solr_path[$num_solr];
							$parts[$i]['small_img'] = $r['post_small_img_url'];
							$parts[$i]['big_img'] = $r['post_big_img_url'];
							$parts[$i]['date'] = $r['post_time'];
							$parts[$i]['title'] = trim($r['post_subject']);
							$parts[$i]['content'] = trim($r['post_summary']);
						}
						elseif($num_solr==2){
							$parts[$i]['id'] = "review_".$r['id'];
							$parts[$i]['cat'] = $this->solr_path[$num_solr];
							$parts[$i]['date'] = $r['review_date_time'];
							$parts[$i]['content'] = trim($r['review_text']);
						}
						elseif($num_solr==1){
							$parts[$i]['id'] = "question_".$r['id'];
							$parts[$i]['cat'] = $this->solr_path[$num_solr];
							$parts[$i]['date'] = $r['date'];
							$parts[$i]['content'] = trim($r['content']);
						}
						elseif($num_solr==0){
							$parts[$i]['id'] = "answer_".$r['id'];
							$parts[$i]['cat'] = $this->solr_path[$num_solr];
							$parts[$i]['date'] = $r['date'];
							$parts[$i]['question_id'] = $r['question_id'];
							$parts[$i]['content'] = trim($r['content']);
						}
						$i = $i+1;
					}
					if($this->add_solr($num_solr,$parts)==0)
                        return 0;
					return 1;
				}

            }
                return 0;
        }
	/*
	*get data in solr
	*$num_solr:
	*	0: answer, 1: question, 2: review, 3:article.
	*	return service
	*$offset : position start
	*$limit : row will be returned
	*$type_sort : array include : 0 = field and 1 = type sort(desc,asc)
	* return data
	*/
	public function get_solr($num_solr=0,$str='',$offset=0,$limit = 10,$type_sort=null){
		$solr = $this->init_solr();
		if($type_sort!=null)
			if(!is_array($type_sort))
				return 0;
		if ( ! @$solr->ping() ) {
			return -1;
		}
		$str_sort = '';
		$num = 0;
		if(is_numeric($num_solr)){
			if($str!='')
				$query = array("content: '".$str."' AND cat:".$this->solr_path[$num_solr]);
			else
				$query = array('cat:'.$this->solr_path[$num_solr]);
			if($type_sort!=null){
				$str_sort = "$type_sort[0] $type_sort[1]";
			}

				if($str_sort!=""){
					$response = $solr->search( $query, $offset, $limit,array('sort' => $str_sort,'fl'=>"*,score","hl"=>"true","hl.fragsize"=>250,'hl.fl'=>'content',"hl.mergeContiguous"=>"true","hl.simple.pre"=>"<span class='highlighted'>","hl.simple.post"=>"</span>") );
				}
				else{
					$response = $solr->search( $query, $offset, $limit,array("hl"=>"true",'hl.fl'=>'content',"hl.mergeContiguous"=>"true","hl.fragsize"=>250,"hl.simple.pre"=>"<span class='highlighted'>","hl.simple.post"=>"</span>",));
				}
				$arr = array();
				if ( $response->getHttpStatus() == 200 ) {
					if ( $response->response->numFound > 0 ) {
						$num = $response->response->numFound;
						foreach ( $response->response->docs as $doc ) {
							if(isset($doc->small_img)){
								$small_img = $doc->small_img;
							}
							else
								$small_img = '';
							if(isset($doc->big_img)){
								$big_img = $doc->big_img;
							}
							else
								$big_img = '';
							$id = explode('_',$doc->id);
							if($response->highlighting!=null){
								foreach($response->highlighting as $key=>$content_h){
									if($key == $doc->id){
										$content_hight = $content_h->content;
									}
								}
							}
							else
								$content_hight[0] = $doc->content;
							if(isset($doc->question_id))
								$question_id = $doc->question_id;
							else
								$question_id = 0;
							if(isset($doc->title))
								$subject = $doc->title;
							else
								$subject = $doc->cat;
							$temp = array(
											'id' => $id[1],
											'subject' => $subject,
											'date' => $doc->date,
											'post_small_img_url'=> $small_img,
											'question_id'=>$question_id,
											'post_big_img_url'=> $big_img,
											'summary' => $content_hight[0]
										);
							$arr[] = $temp;
						}
					}
					else
						return 0;
				}
				else{
					return 0;
				}
		}
		$arr[] = $num;
		return $arr;
	}
	/*
	*Get answer or question
	*/
	public function get_a_q($num_solr=0,$str,$offset=0,$limit = 10){
		$solr = $this->init_solr();

		if ( ! @$solr->ping() ) {
			return -1;
		}

		$num = 0;

		if(is_numeric($num_solr)){
			if($num_solr==0)
				$id_q = "question_id";
			else{
				$id_q = "id";
				$str = "question_".$str;
			}

			if($str!='')
				$query = array($id_q.": ".$str." AND cat: ".$this->solr_path[$num_solr]);
			else
				$query = array('cat:'.$this->solr_path[$num_solr]);

			$response = $solr->search( $query, $offset, $limit);
				$arr = array();
				if ( $response->getHttpStatus() == 200 ) {
					if ( $response->response->numFound > 0 ) {
						$num = $response->response->numFound;
						foreach ( $response->response->docs as $doc ) {
							$id = explode('_',$doc->id);
							$temp = array(
											'id' => $id[1],
											'subject' => $doc->cat,
											'date' => $doc->date,
											'summary' => $doc->content
										);
							$arr[] = $temp;
						}
					}
					else
						return 0;
				}
				else{
					return 0;
				}
		}
		$arr[] = $num;
		return $arr;
	}
	/*
	*delete data in solr
	*$num_solr:
	*	0: answer, 1: question, 2: review, 3:article.
	*/
	public function delete_all_solr($num_solr){
		$solr = $this->init_solr();

		if ( ! @$solr->ping() ) {
			return 0;
		}

		$solr->deleteByQuery('cat:'.$this->solr_path[$num_solr]);
		$solr->commit();
		$solr->optimize();
	}
}
?>