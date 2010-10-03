<?php
include("../core/init.php");
include("../core/common.php");
include("../core/classes/Db.php");
include("../core/classes/ActiveRecord.php");
include("../core/classes/User.php");
include('../libraries/TalkPHP_Gravatar.php');
include("../core/classes/Questions.php");
include("../core/classes/Answers.php");
    
        $q_s = Mem::$memcache->get("ques");
        if($q_s == NULL){
            $q_s = Questions::getQ();
        }
        $a_r = Mem::$memcache->get("ans");
        if($a_r == NULL){
            $a_r = Answers::getA();
        }
	$row_per_page = 5;
	$s = $_POST["start"];
        if(count($q_s)>0){
            $n_row = count($q_s);
            if($n_row > $row_per_page)
                $num_page = ceil($n_row/$row_per_page);
            else {
                $num_page = 1;
            }
            $per = (($s/$row_per_page)+1)*$row_per_page;
            if($per <= $n_row)
		$r = $per;
            else
		$r = $n_row;
            echo "<ul>";
            foreach ($q_s as $key=>$v){
                if($key >= $s && $key < $r ){
                    echo "<li><div class='question'><img src='".$v['avatar']."' height=30 width=30 align='left'/>$v[name]:".$v['content']."    <a style='cursor: pointer; color: #DB1C00;text-decoration: underline;' onclick='answer($v[id]);' >reply</a></div>";
                    if(count($a_r)){
                        echo "<ul style=''>";
                        foreach ($a_r as $v2)
                        {
                            if($v2['question_id']==$v['id'])
                                echo "<li><img src='".$v2['avatar']."' height=30 width=30 align='left'/>$v2[name]:".$v2["content"]."</li>";
                        }
                        echo "</ul>";
                    }
                    echo "<div class='clear'></div></li>";
                }
            }
            echo "</ul>";
        }
        if($num_page > 1){
?>
<br />
<div class="phantrang">
<div class='prev'><a href="#" id="mycarousel-prev">&laquo; Prev</a></div>
<ul id="mycarousel" class="jcarousel-skin-tango">
<?php
	$tranghh = ($s/$row_per_page)+1;
	for($i = 1;$i <= $num_page; $i++)
		if($i != $tranghh)
			echo "<li><a style='cursor: pointer; color: #DB1C00;' onclick='load_qanda(".($i-1)*$row_per_page.");'>$i</a></li>";
		else
			echo "<li>".$i."</li>";
?>
</ul>
<div class='next'><a href="#" id="mycarousel-next">&nbsp;Next &raquo;</a></div>
<div style='clear:left;'><!-- --></div>
</div>
<script type="text/javascript">
function mycarousel_initCallback(carousel) {

    jQuery('#mycarousel-next').bind('click', function() {
        carousel.next();
        return false;
    });

    jQuery('#mycarousel-prev').bind('click', function() {
        carousel.prev();
        return false;
    });
};
jQuery(document).ready(function() {
    jQuery("#mycarousel").jcarousel({
        scroll: 2,
<?php
	if($num_page > 1)
		echo "start:$tranghh,";
	else
		echo "start:1,";
?>
        initCallback: mycarousel_initCallback,
        // This tells jCarousel NOT to autobuild prev/next buttons
        buttonNextHTML: null,
        buttonPrevHTML: null
    });
})

</script>
<?php
}
$post_id = $_POST["post_id"];
$index_id = $_POST["index_id"];
$destination = $_POST["destination"];
include("../forms/askquestion.php");
include("../forms/replyquestion.php");
?>
