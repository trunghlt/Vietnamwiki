<?php
	$sql = "SELECT * 
			FROM posts
			WHERE post_id = '".$post_id."'";
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
?>
<div id="rate">
<div class="rating" id="rating_1"><?php echo $row["rate"];?></div>
<!-- 
<div id="statistic">
<?php
	$rate_count = $row["rate_count"];
	$s = $rate_count;
	$s .= ($rate_count > 1)? " readers":" reader";
	echo "<span class='style5'>Average: ".$row["rate"]. " - ".$s." rated</span>";
?>
</div>
 -->
</div>
<span class="style5"><?php 
$sql = "SELECT page_view
		FROM posts
		WHERE post_id = '".$post_id."'";
$result = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($result);
echo $row["page_view"];
$s = ($row["page_view"] > 1)? "views" : "view";
?> page <?php echo $s ?></span>
<script type="text/javascript">


var NUMBER_OF_STARS = 5;

function reload_rating() {
    var ratings = document.getElementsByTagName('div');
    for (var i = 0; i < ratings.length; i++)
    {
        if (ratings[i].className != 'rating')
            continue;
            
        var rating = ratings[i].firstChild.nodeValue;
        ratings[i].removeChild(ratings[i].firstChild);
        if (rating > NUMBER_OF_STARS || rating < 0)
            continue;
        for (var j = 0; j < NUMBER_OF_STARS; j++)
        {
            var star = document.createElement('img');
            if (rating >= 0.8)
            {
                star.setAttribute('src', './images/rating_on.gif');
                star.className = 'on';
                rating--;
            }
            else if(rating >= 0.3)
            {
                star.setAttribute('src', './images/rating_half.gif');
                star.className = 'half';
                rating = 0;
            }
            else
            {
                star.setAttribute('src', './images/rating_off.gif');
                star.className = 'off';
            }
			ratings[i].appendChild(star);
		}		
	}
}

function init_rating()
{
    var ratings = document.getElementsByTagName('div');
    for (var i = 0; i < ratings.length; i++)
    {
        if (ratings[i].className != 'rating')
            continue;
            
        var rating = ratings[i].firstChild.nodeValue;
        ratings[i].removeChild(ratings[i].firstChild);
        if (rating > NUMBER_OF_STARS || rating < 0)
            continue;
        for (var j = 0; j < NUMBER_OF_STARS; j++)
        {
            var star = document.createElement('img');
            if (rating >= 1)
            {
                star.setAttribute('src', './images/rating_on.gif');
                star.className = 'on';
                rating--;
            }
            else if(rating >= 0.5)
            {
                star.setAttribute('src', './images/rating_half.gif');
                star.className = 'half';
                rating = 0;
            }
            else
            {
                star.setAttribute('src', './images/rating_off.gif');
                star.className = 'off';
            }
            var widgetId = ratings[i].getAttribute('id').substr(7);
            star.setAttribute('id', 'star_'+widgetId+'_'+j);
            star.onmouseover = new Function("evt", "displayHover("+widgetId+", "+ j+")");
            star.onmouseout = new Function("evt", "displayNormal("+widgetId+", "+ j+")");
		star.onmousedown = new Function("evt", "update_rate("+j+")");
            ratings[i].appendChild(star);
        } 
    }
}

function displayHover(ratingId, n)
{
    for (var i = 0; i <= n; i++)
    {
		var star = document.getElementById('star_'+ratingId+'_'+i);
        star.setAttribute('src', './images/rating_over.gif');
    }
}

function displayNormal(ratingId, n)
{
    for (var i = 0; i <= n; i++)
    {
        var status = document.getElementById('star_'+ratingId+'_'+i).className;
        var star = document.getElementById('star_'+ratingId+'_'+i);
        star.setAttribute('src', './images/rating_'+status+'.gif');
    }
}

function show_rate(s) {
	document.getElementById("rate").innerHTML = s;
	reload_rating();
}

function update_rate(x) {
	x_rate(x + 1, show_rate);
}

window.onload = init_rating;
</script>
