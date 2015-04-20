	<!-- RIBBON -->
	<div id="ribbon">

		<span class="ribbon-button-alignment">
			<span id="refresh" class="btn btn-ribbon"><i class="fa fa-chevron-right"></i></span>
		</span>

		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<?php
				foreach ($breadcrumbs as $display => $url) {
					$breadcrumb = $url != "" ? '<a href="'.$url.'">'.$display.'</a>' : $display;
					echo '<li>'.$breadcrumb.'</li>';
				}
				echo '<li>'.$page_title.'</li>';
			?>
		</ol>
		<!-- end breadcrumb -->

		<!-- logout button -->
		<span class="ribbon-button-alignment pull-right" id="logout">
			<span class="btn btn-ribbon"><i class="fa fa-sign-out"></i> sign out</span>
		</span>
		<!-- end logout button -->

	</div>
	<!-- END RIBBON -->