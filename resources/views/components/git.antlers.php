<?php
	$style = "";
	$branch = "—";
	$git_file = base_path() . "/.git/HEAD";
	if (file_exists($git_file)) {
		if ($gitinfo = file($git_file)) {
			$style = 'style="background-color: #FF88FF; color: #000000; font-weight: 900;"';
			$exp = explode("/", $gitinfo[0], 3);
			$branch = trim( $exp[2] );
			if ($branch == "main") {
				$style = 'style="background-color: #44CC66; color: #000000; font-weight: 900;"';
			}
		}
	}
?>
<a class="barnacle-component" <?php echo $style ?>
  title="Git
  <?php echo $branch ?>
  branch" >
  <svg
    xmlns="http://www.w3.org/2000/svg"
    width="18"
    height="18"
    viewBox="0 0 24 24"
  >
    <path
      fill="none"
      stroke="currentColor"
      stroke-linecap="round"
      stroke-linejoin="round"
      stroke-width="1.6"
      d="M17 7a2 2 0 1 0 0-4a2 2 0 0 0 0 4M7 7a2 2 0 1 0 0-4a2 2 0 0 0 0 4m0 14a2 2 0 1 0 0-4a2 2 0 0 0 0 4M7 7v10M17 7v1c0 2.5-2 3-2 3l-6 2s-2 .5-2 3v1"
    />
  </svg>
  <span class="barnacle-label"><?php echo $branch ?></span>
</a>
