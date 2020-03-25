<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 25.03.2020
 */
?>
<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<h1 class="ta-c"><?=$data['title'];?></h1>
			</div>
			<div class="part-min">
				<?php
				    debug($this->Fw->Task($data['id'])->info());
				?>
			</div>
		</div>
	</div>
</section>
