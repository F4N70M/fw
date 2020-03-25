<section>
    <div class="limit">
            <div class="part-min">
                <div class="d-g gaf-c jc-sb">
                    <div>HEADER</div>
                    <div>
	                    <?php
                        $id = $this->Fw->Account->getCurrentId();
                        if ($id)
                        {
	                        $user = $this->Fw->User($this->Fw->Account->getCurrentId());
	                        if ($user) :
		                        ?>
                                Пользователь: <b><?= $user->get('name'); ?></b> [<?= $user->get('type'); ?>] (<a href="/lk/logout">выйти</a>)
	                        <?php
	                        endif;
                        }
	                    ?>
                    </div>
                </div>
            </div>
    </div>
</section>