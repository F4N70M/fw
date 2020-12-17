<?php
$user = $this->Fw->Account->getCurrent();
//debug($user);
?>
<?php if ($user['rank'] >= 1) : ?>
    <section>
        <div class="limit">
            <div class="part">
                <div class="part-min">
                    <!--FOOTER-->
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>