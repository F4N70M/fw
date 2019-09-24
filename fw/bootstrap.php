<?php

/**
 * @author: KONARD
 * @version: 1.0
 */
	require 'functions.php';


/*
 * Подключение системы
 */
$Fw = new Fw\Core();

$Fw->services->Router->getCurrentApp();


debug(
	$Fw->services->Db
		->select()
		->from('aliases')
		->all()
);


debug('\\\\TODO: Создать сервисы');
debug('\\\\TODO: Создать модули');