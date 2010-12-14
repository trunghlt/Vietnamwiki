<div id='qanda'>
<?php
    
    if($index_name=="Overview"){
?>
    <div class="head3" style="margin-left: 0px; width: 168px;padding:6px">Questions and Answers</div>
<?php
    }else{
?>
    <div class="head3" style="margin-left: 0px; width: 187px; max-width: 187px;padding:6px">Questions and Answers</div>
<?php
    }
?>
<div class="button" style="margin-top: 10px; margin-left: 35px;"><a onclick='question();' >Ask a question</a></div>
<div style="clear: both"></div>
<!--<div class="button" style="margin-top: 10px; float: left;"><a href="/viewallq_a.php" >View All</a></div>-->
<div style="clear: both"></div>
<div id="view_qanda"><!-- --></div>
</div>
<script>
jQuery(document).ready(function(){
         load_qanda(0);
});
    function load_qanda(id){
        jQuery('#questionDialog').remove();
        jQuery('#answerDialog').remove();
        jQuery("#Emailquestion").remove();
       // jQuery("#Emailanswer1").remove();
        <?php
            if(isset($post_id) && $post_id>0){
        ?>
                jQuery.post("requests/QandA.php", {start:id,post_id:<?php echo $post_id;?>,index_id:<?php echo $index_id;?>,destination:<?php echo $destination;?>,num_row:5,type:1,type_view:2},
                                        function(response) {
                                                jQuery('#view_qanda').html(response);
                                        });
          <?php
            }
            else if(isset($index_id) && $index_id>0){
                $post_q = new PostElement;
                $row_q = $post_q->query("",$index_id);
          ?>
                jQuery.post("requests/QandA.php", {start:id,post_id:<?php echo $row_q[0]['post_id'];?>,index_id:<?php echo $index_id;?>,destination:<?php echo $dest_id;?>,num_row:5,type:1,type_view:2},
                                        function(response) {
                                                jQuery('#view_qanda').html(response);
                                        });
          <?php
            }
        ?>
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
                                        else if(_type==1)
                                            jQuery("#l_q_"+id).html(response);
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
