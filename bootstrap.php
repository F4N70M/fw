<?php
/**
 * @author: KONARD
 * @version: 1.0
 */

//	Определяем корневую директорию
// define('ROOT_DIR', str_replace('\\', '/', getcwd()) );

// Подключить автозагрузчик
require 'vendor/autoload.php';

// Подключить фреймворк
require 'fw/bootstrap.php';


/*
// Запуск
$fw = new \Fw\Fw();














$di = $fw->di;

// debug($di);

$db = $di->get('Db');
$errors = $di->get('errorManager');

$app = $fw->getApp();




$desc = "Дорогой друг!

У тебя в руках не простая книга.
Это целый мир, полный захватывающих приключений.
Приготовься!";
$a = ['b'=>'c','d'=>['e'=>'f','desc'=>$desc]];
$b = json($a,true);
$c = json($a,false);

// $insert = $db
// 	->insert('options')
// 	->values(['name'=>'json','value'=>$b])
// 	->do();

$select = $db
	->select()
	->from('options')
	->all();

// $delete = $db
// 	->delete()
// 	->from('options')
// 	->where(['id'=>$insert])
// 	->do();

// $select2 = $db
// 	->select()
// 	->from('options')
// 	->all();

// debug($insert);
debug($select);
// debug($delete);
// debug($select2);













debug('LOCK IMAGES USE CANVAS');

*/