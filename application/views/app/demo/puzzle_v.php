<!-- CSS style for Shuffle Puzzle-->
<link rel="stylesheet" href="<?php echo URL_ASSETS ?>shuffle_puzzle/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo URL_ASSETS ?>shuffle_puzzle/suffle_puzzle_with_menu.css" type="text/css">
	
	<!-- JavaScript libraries -->
	<script type="text/javascript" src="<?php echo URL_ASSETS ?>shuffle_puzzle/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="<?php echo URL_ASSETS ?>shuffle_puzzle/sp_4.min.js"></script>

	<div id="Puzzle"></div>
	<!-- Your parameters for Shuffle Puzzle -->
	<script type="text/javascript">
		$(function(){
			$('#Puzzle').sp4_();
		});
	</script>
