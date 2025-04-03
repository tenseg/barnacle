<?php
	$style = "";
	$branch = "—";
	$git_file = base_path() . "/.git/HEAD";
	if (file_exists($git_file)) {
		if ($gitinfo = file($git_file)) {
			$style = "background-color: #FF88FF; color: #000000; font-weight: 900;";
			$exp = explode("/", $gitinfo[0], 3);
			$branch = trim( $exp[2] );
			if ($branch == "main") {
				$style = "background-color: #44CC66; color: #000000; font-weight: 900;";
			}
		}
	}
?>
<div class="barnacle-component" style="<?php echo $style ?>">
  <?php echo $branch ?>
</div>
