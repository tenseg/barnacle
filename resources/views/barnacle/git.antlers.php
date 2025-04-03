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
  <svg
    xmlns="http://www.w3.org/2000/svg"
    width="24"
    height="24"
    viewBox="0 0 24 24"
    style="display: inline-block; width: 18px"
    aria-label="Git Branch"
  >
    <path
      fill="none"
      stroke="currentColor"
      stroke-linecap="round"
      stroke-linejoin="round"
      stroke-width="0.9"
      d="M17 7a2 2 0 1 0 0-4a2 2 0 0 0 0 4M7 7a2 2 0 1 0 0-4a2 2 0 0 0 0 4m0 14a2 2 0 1 0 0-4a2 2 0 0 0 0 4M7 7v10M17 7v1c0 2.5-2 3-2 3l-6 2s-2 .5-2 3v1"
    />
  </svg>
  <?php echo $branch ?>
</div>
