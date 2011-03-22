<style>
div.sort {border-bottom: 1px solid #777; margin-top: 20px; border-spacing: 0; height: 17px;}
    div.sort .tabs {float: right; border-spacing: 0; height: 19px; margin-right: 5px;}
        div.sort .tabs span {font-size: 12px; float: left; line-height: 21px; color: #777;}
        div.sort .tabs a {border: 1px solid #777; margin-left: 5px; line-height: 13px; font-size: 12px; text-decoration: none; padding: 2px; }
        div.sort .tabs a.current {border-bottom: 1px solid white; font-size: 12px;}
</style>


<div id='qanda'>
<a class="none" href="/viewallq_a.php">
<?php
    if(isset($index_name)){
        if($index_name=="Overview"){
?>
            <div class="head3" style="margin-left: 0px; width: 168px;padding:6px">Questions and Answers</div>
<?php
        }else{
?>
        <div class="head3" style="margin-left: 0px; width: 168px; max-width: 187px;padding:6px">Questions and Answers</div>
<?php
        }
    }
    else{
?>
        <div class="head3" style="margin-left: 0px; width: 168px; max-width: 187px;padding:6px">Questions and Answers</div>
<?php }?>
</a>
<div class="button" style="margin-top: 10px; margin-left: 35px;"><a onclick='question();'>Ask a question</a></div>
<div style="clear: both"></div>


<div class="sort">
    <div class="tabs">
        <span>Sorted by </span>
        <a class="current" id="sortByTime" href="#"/>Newest</a>
        <a id="sortByLike" href="#"/>Like</a>
    </div>
    <div style="clear: both"></div>
</div>                        



<div id="view_qanda"><!-- --></div>
</div>
<script>
GLOBAL = {
    options: {    
        start       : 0,
        type        : 2,
        post_id     : <?=(isset($post_id) && $post_id>0)? $post_id : 0?> ,
        index_id    : <?=(isset($index_id)&& $index_id>0)? $index_id : 0?>,
        destination : <?=$destination?>,
        num_row     : 5,
        type        : 1,
        type_view   : 2        
    }
}

loadQandA = function(o) {
    GLOBAL.options = jQuery.extend(GLOBAL.options, o || {});
    jQuery.post("requests/QandA.php", GLOBAL.options, function(response) {
        jQuery('#view_qanda').html(response); 
    });
}

jQuery(document).ready(function(){
    jQuery('#questionDialog').remove();
    jQuery('#answerDialog').remove();
    jQuery("#Emailquestion").remove();
    jQuery("#sortByTime").click(function() {
        jQuery(this).addClass("current");
        jQuery("#sortByLike").removeClass("current");
        loadQandA({type: 1, start: 0});
    });
    jQuery("#sortByLike").click(function() {
        jQuery(this).addClass("current");
        jQuery("#sortByTime").removeClass("current");
        loadQandA({type: 2, start: 0});
    });

    jQuery("#sortByLike").click();
});


function question(){
   jQuery('#questionDialog').css('visibility','visible').dialog('open');
}


function answer(id){
    jQuery('#answerDialog').css('visibility','visible').dialog('open');
    jQuery('#questionId').val(id);
}


function sortanswer(id,_type){
    jQuery.post("/requests/sortAnswer.php",{id_q:id,type:_type}, function(response){
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
