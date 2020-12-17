<?php

function getUrlParameters(array $params=[])
{
	$result = [];
	$get = $_GET;
	foreach ($params as $key => $value)
	{
	    if (!empty($value))
		    $get[$key] = $value;
	    else
	        unset($get[$key]);
	}
	foreach ($get as $key => $value)
	{
		$result[] = $key.'='.$value;
	}
	return '?'.implode('&',$result);
}

$months = ['01'=>'яневаря','02'=>'февраля','03'=>'марта','04'=>'апреля','05'=>'мая','06'=>'июня','07'=>'июля','08'=>'августа','09'=>'сентября','10'=>'октября','11'=>'ноября','12'=>'декабря'];

$states = [
    'relevant'=>'Не оплаченные',
    'complete'=>'Оплаченные'
];
$currentState = isset($_GET['state']) && isset($states[$_GET['state']]) ? $_GET['state'] : 'relevant';

?>
<section class="bg-b-5 c-b">
	<div class="limit">
		<div class="part">
			<h1 class="ta-c">Долги</h1>
		</div>
		<div class="part">
			<div class="part-min">
				<div class="d-g gg-1 gaf-c jc-sb">
					<div>
						<a href="<?= APP_PREFIX; ?>/new-debt" class="btn bg-g-50 bdrs-1">Добавить долг</a>
					</div>
                    <div class="d-g gaf-c jc-l ac-c bg-g-50">
						<?php foreach ($states as $stateKey => $stateName): ?>
                            <a href="<?=getUrlParameters(['state'=>$stateKey]);?>" class="p-05 <?=$currentState == $stateKey ? 'c-1' : '';?>"><?=$stateName;?></a>
						<?php endforeach; ?>
                    </div>
				</div>
			</div>
			<div class="part-min">
				<?php
//                $query = ['state'=>$currentState];
//                $tasks = $this->Fw->DebtManager->get($query);
                $tasks = $this->Fw->Db->select(['id','name','type','state','borrower','lender','content','value','date'])->from('objects')->where(['type'=>'debt','state'=>$currentState])->result();
//                debug($tasks);

				$projects = [];
				$users = [];
				?>
                <?php if(is_array($tasks) && count($tasks) > 0) : ?>
			    	<div class="d-g gg-2 gtc-2">
						<?php foreach ($tasks as $key => $item): ?>
							<div href="<?=APP_PREFIX;?>/debt-<?=$item['id'];?>" class="bg-w bsh-1 p-1">
								<div class="d-g gg-1">
									<div class="d-g gg-1 gaf-c jc-sb">
										<div>
	                                        <?php
	                                        $date = explode('.',date('d.m.Y', strtotime($item['date'])));
	                                        echo $date[0] . ' ' . $months[$date[1]] . ' ' . $date[2];
	                                        ?>
	                                    </div>
										<div class="fw-b"><?=$item['value'];?> ₽</div>
									</div>

									<div><?=$item['content'];?></div>

									<div class="d-g gg-1 gaf-c jc-sb">
										<div>
											<div class="c-b-50">заемщик</div>
<!--											<div>--><?//=$this->Fw->User($item['borrower'])->get('name');?><!--</div>-->
											<div>
                                                <?=
                                                    ($resultRequest = $this->Fw->Db->select(['id','name'])->from('users')->where(['id'=>$item['borrower']])->result())
                                                        ? $resultRequest[0]['name']
                                                        : '';
                                                ?>
                                            </div>
										</div>
										<div class="ta-r">
											<div class="c-b-50">кредитор</div>
<!--											<div>--><?//=$this->Fw->User($item['lender'])->get('name');?><!--</div>-->
											<div>
                                                <?=
                                                ($resultRequest = $this->Fw->Db->select(['id','name'])->from('users')->where(['id'=>$item['lender']])->result())
                                                    ? $resultRequest[0]['name']
                                                    : '';
                                                ?>
                                            </div>
										</div>
									</div>

									<div>
										<form method="post">
											<input type="hidden" name="request" value="debtChangeState">
											<input type="hidden" name="id" value="<?=$item['id'];?>">
											<?php
											$stateNew = ($item['state'] == 'complete' ? 'relevant' : 'complete');
											?>
											<input type="hidden" name="state" value="<?=$stateNew;?>">
											<div>
												<button type="submit" class="w-100p bg-g-25">Перенести в <?=mb_strtolower($states[$stateNew]);?></button>
											</div>
										</form>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
				</div>
                <?php else: ?>
                    <div class="c-g ta-c">Нет долгов</div>
                <?php endif; ?>
			</div>
		</div>
	</div>
</section>