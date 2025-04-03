<?php
	$color = "#000000";
	$branch = "—";
	$git_file = base_path() . "/.git/HEAD";
	if (file_exists($git_file)) {
		if ($gitinfo = file($git_file)) {
			$color = "#FF88FF";
			$exp = explode("/", $gitinfo[0], 3);
			$branch = trim( $exp[2] );
			if ($branch == "main") {
				$color = "#88EEAA";
			}
		}
	}
?>
<div class="barnacle-component" style="background-color: <?php echo $color ?>">
  <?php echo $branch ?>
</div>
