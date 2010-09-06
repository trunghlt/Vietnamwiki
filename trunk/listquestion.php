<div id='qanda'>
<div class="head3" style="margin-left: 0px; width: 168px;padding:6px">Questions and Answers</div>
<div style="padding:5px;"><a style='cursor: pointer; color: #DB1C00;text-decoration: underline;' onclick='question();' >Ask Question</a></div>
<div id="view_qanda"><!-- --></div>
</div>
<script>
jQuery(document).ready(function(){
         load_qanda(0);
});
    function load_qanda(id){
        jQuery('#questionDialog').remove();
        jQuery('#answerDialog').remove();
        jQuery("#Emailquestion1").remove();
        jQuery("#Emailanswer1").remove();
        <?php
            if(isset($post_id) && $post_id>0){
        ?>
                jQuery.post("requests/QandA.php", {start:id,post_id:<?php echo $post_id;?>,index_id:<?php echo $index_id;?>,destination:<?php echo $destination;?>},
                                        function(response) {
                                                jQuery('#view_qanda').html(response);
                                        });
          <?php
            }
            else if(isset($index_id) && $index_id>0){
                $post_q = new PostElement;
                $row_q = $post_q->query("",$index_id);
          ?>
                jQuery.post("requests/QandA.php", {start:id,post_id:<?php echo $row_q[0]['post_id'];?>,index_id:<?php echo $index_id;?>,destination:<?php echo $dest_id;?>},
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
</script>