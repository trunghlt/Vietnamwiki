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
 	jQuery.post("requests/QandA.php", {start:id},
				function(response) {
                                      jQuery('#view_qanda').html(response);
				});
    }
    function question(){
 	jQuery('#questionDialog').css('visibility','visible').dialog('open');
    }
    function answer(name){
 	jQuery('#answerDialog').css('visibility','visible').dialog('open');
    }
    
</script>