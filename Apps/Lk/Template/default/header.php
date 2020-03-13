<section>
    <div class="limit">
            <div class="part-min">
                <div class="d-g gaf-c jc-sb">
                    <div>HEADER</div>
                    <div>
	                    <?php
	                    $user = $this->Fw->Account->getCurrent();

	                    if ($user) :
		                    ?>
                            Пользователь: <b><?= $user['name']; ?></b> [<?= $user['type']; ?>] (<a href="/lk/logout">выйти</a>)
	                    <?php
	                    endif;
	                    ?>
                    </div>
                </div>
            </div>
    </div>
</section>