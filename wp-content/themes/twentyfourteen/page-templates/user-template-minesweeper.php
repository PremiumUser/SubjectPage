<?php
/**
 * Template Name: Admin template minesweeper
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<div id="main-content" class="main-content">

<?php
	if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
<?php
$usosid = $wpdb->get_var("select identifier from wp_wslusersprofiles where user_id =".intval(get_current_user_id()));
if ($usosid != null) { //Visible only to logged users

echo "<form name=\"someform\" id=\"someform\" action=\"\" method=\"post\">";
$width=10;
$height=10;
$minesnum=15;
$isdisarm;
if($_POST["mineact"]=="disarm") $isdisarm = true;
else $isdisarm = false;
/*if ($_POST["mineact"] == "disarm") {
	echo "<h3><input type=\"radio\" name=\"mineact\" value=\"check\">Check</h3>";
	echo "<h3 style=\"color:#005500\"><input type=\"radio\" name=\"mineact\" value=\"disarm\" checked>Flag</h3>";
} else {
	echo "<h3 style=\"color:#660000\"><input type=\"radio\" name=\"mineact\" value=\"check\" checked>Check</h3>";
        echo "<h3><input type=\"radio\" name=\"mineact\" value=\"disarm\">Flag</h3>";
}*/
$posted=false;
$addval="";
$gameover=false;
for ($i=0; $i<$height; $i++) {
	for ($j=0;$j<$width; $j++) {
//		echo "submit".strval($i)."_".strval($j);
		if ($_POST["submit".$j."_".$i.""]) {
			$posted = true;
			//echo "Clicked!";
			if (!$isdisarm) {
				$addval = array('x'=>$j,'y'=>$i);
				if ($wpdb->get_var("SELECT COUNT(*) from minesweeper where x=".$j." and y=".$i." and UID=".$usosid." and type ='b'") == 1) {
					$gameover=true;
				}
			}
			else 
				$wpdb->insert('minesweeper',array('UID'=>$usosid, 'x'=>$j, 'y'=>$i, 'type'=>'d'));
		}
	}
	
}
if ($_POST["gamestatus"] == "gameover") {
	$posted = false;
	$gameover = false;
}
if ($gameover)
	echo "<h1>GAME OVER. Click any field to restart.</h1>";
else
	echo "<h1>Game is on.</h1>";
//if ($_POST["gamestatus"] == "gameover") 
//	$posted = false;
if (!$posted) {
	$arr;
	for ($a = 0; $a < $minesnum; $a++) {
		srand();
		$newnum=false;
		$x = 0;
		$y = 0;
		while (!$newnum) {
			$y = rand(0, $height-1);
			$x = rand(0, $width-1);
			$newnum=true;
			for ($b = 0; $b < $a; $b++) {
				if ($arr[$b][0] == $y && $arr[$b][1] == $x)
					$newnum=false;
			}
		}
		$arr[$a][0] = $y;
		$arr[$a][1] = $x;
	}
	$wpdb->query("delete from minesweeper where UID=".$usosid);
	$wpdb->query("create table minesweeper (UID int, x int, y int, type varchar(1));");
	for ($i = 0; $i < $minesnum; $i++)
		$wpdb->insert('minesweeper',array('UID' => $usosid,'x' => $arr[$i][0], 'y' => $arr[$i][1], 'type'=>'b'),array('%d','%d','%d','%s'));
}

$gamedata;
if ($gameover)
	echo "<input type=\"hidden\" name=\"gamestatus\" value=\"gameover\">";
printmines:
for ($i=0; $i<$height; $i++) {
	for ($j=0; $j<$width; $j++) {
		$gamedata[$i][$j][0] = 0;
		$gamedata[$i][$j][1] = 0;
	}
}

//$wpdb->query("create table minesweeper (UID int, x int, y int, type varchar(1));");
$recnum = $wpdb->get_var("SELECT COUNT(*) FROM minesweeper where UID =".$usosid);
for ($i = 0; $i<$recnum; $i++) {
	$row = $wpdb->get_row("SELECT * FROM minesweeper where UID =".$usosid, ARRAY_N, $i);
	if ($row[3] == 'b') { //Bomb
		for ($j = -1; $j < 2; $j++) { 
			for ($k = -1; $k < 2; $k++) {
				$gamedata[$row[1]+$j][$row[2]+$k][1]++;
			}
		}
		$gamedata[$row[1]][$row[2]][1]=9;
	} else {
		if ($row[3] == 'm') {
			if ($gamedata[$row[1]][$row[2]][0] == 0) {
				$gamedata[$row[1]][$row[2]][0] = 1;
			}
		} else {
			if ($gamedata[$row[1]][$row[2]][0] == 0) {
				$gamedata[$row[1]][$row[2]][0]=2;
		} else {
				if ($gamedata[$row[1]][$row[2]][0] == 2) {
					$gamedata[$row[1]][$row[2]][0]=0;
				}
			}
		}
	}
}
if ($addval != "") {
	$queue = array('1' => $addval);
	//var_dump($queue);
	$quelen = 2;
	$quebeg = 1;
	while ($quebeg < $quelen) {
		$slot = $queue[$quebeg];
		//var_dump($slot);
		$x = $slot['x'];
		$y = $slot['y'];
		if ($x > -1 and $y > -1 and $x < $width and $y < $height and $gamedata[$x][$y][0] == 0) {//New field view
			$gamedata[$x][$y][0] = 1;
			$wpdb->insert('minesweeper',array('UID'=>$usosid, 'x'=>$x, 'y'=>$y, 'type'=>'m'));
			if ($gamedata[$x][$y][1] == 0) {
				$queue[$quelen] = array('x'=>$x+1, 'y'=>$y-1);
				$queue[$quelen+1] = array('x'=>$x+1, 'y'=>$y);
				$queue[$quelen+2] = array('x'=>$x+1, 'y'=>$y+1);
				$queue[$quelen+3] = array('x'=>$x, 'y'=>$y+1);
				$queue[$quelen+4] = array('x'=>$x, 'y'=>$y-1);
				$queue[$quelen+5] = array('x'=>$x-1, 'y'=>$y+1);
				$queue[$quelen+6] = array('x'=>$x-1, 'y'=>$y);
				$queue[$quelen+7] = array('x'=>$x-1, 'y'=>$y-1);
				$quelen = $quelen+8;
			}
		}
		if ($quelen > 1000)
			die("Looped.");
		$quebeg++;
	}
	//TODO:
	$addval = "";
	goto printmines;
}

echo "</form>";

//echo "<table style=\"border-color:#ffffff; border:1px\"><tr><td>";
for ($i=0; $i<$height; $i++) {
	for ($j=0;$j<$width;$j++) {
		$val;
		if ($gamedata[$i][$j][0]==1)
			$val = strval($gamedata[$i][$j][1]);
		else if ($gamedata[$i][$j][0]==2)
			$val = "H";
		else
			$val = " ";
		if (intval($val)>=9)
			$val = 'M';
		echo "<input style=\"width:50px; border-width:1px\" type=\"submit\" name =\"submit".$i."_".$j."\" value= \"".$val." \" form= \"someform\"> ";
	}
	echo "<br>";
}
//echo "</td><td>";
if ($isdisarm) {
	        echo "<h3><input type=\"radio\" name=\"mineact\" value=\"check\" form=\"someform\">Check</h3>";
	        echo "<h3 style=\"color:#005500\"><input type=\"radio\" name=\"mineact\" value=\"disarm\" form=\"someform\" checked>Flag</h3>";
} else {
	        echo "<h3 style=\"color:#660000\"><input type=\"radio\" name=\"mineact\" value=\"check\" form=\"someform\" checked>Check</h3>";
	        echo "<h3><input type=\"radio\" name=\"mineact\" value=\"disarm\" form=\"someform\">Flag</h3>";
}
//echo "</td></tr></table>";

}//End of invisibility
?>
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();?>
