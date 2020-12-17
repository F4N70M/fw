<?php
$user = $this->Fw->Account->getCurrent();
?>
<?php if ($user['rank'] >= 5) : ?>
    <section>
        <div class="limit">
            <div class="part">
                <div class="part-min">
                    FOOTER
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>