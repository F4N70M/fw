<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 14.01.2020
 */

namespace Apps\Crm\View;


use Fw\Core;
use Exception;

class Lk_View
{
	/**
	 * @var Core
	 */
	private $Fw;
	private $model;


	/**
	 * Lk_View constructor.
	 * @param Core $Fw
	 * @param $model
	 */
	public function __construct(Core $Fw, $model)
	{
		$this->Fw = $Fw;
		$this->model = $model;
	}

	public function render($tpl,$data=[])
	{
//	    debug($tpl);
//	    $this->Fw->TemplateManager;
		$file = str_replace("\\", "/", dirname(__DIR__) . "/Template/default/" . $tpl);

		if(file_exists($file . ".php"))
		{
			$head = $this->head($data);
			$header = $this->getTemplateContent('header',$data);
			$content = $this->getTemplateContent($tpl,$data);
			$footer = $this->getTemplateContent('footer',$data);

			$assets = $this->getAssets();

			?>
            <!doctype html>
            <html lang="ru">
                <head>
	                <?=$head;?>
	                <?=$assets[0];?>
                </head>
                <body>
                    <header><?=$header;?></header>
                    <main><?=$content;?></main>
                    <footer><?=$footer;?></footer>
                    <?=$assets[1];?>
                </body>
            </html>
			<?
			$content = ob_get_clean();

			echo $content;
		}
		else
		{
			throw new Exception("Template file \"{$file}.php\" not found");
		}
	}

	private function head($data=[])
	{
		ob_start();
		require dirname(__DIR__) . "/Template/default/head.php";
		$content = ob_get_clean();

		return $content;
	}

	private function getAssets()
	{
		$assets = $this->Fw->Assets->get();
		$result = ['',''];
		foreach ($assets as $key => $items)
        {
	        foreach ($items as $item)
            {
                switch ($item['type'])
                {
	                case 'style' :
		                $result[$key] .= '<link href="'.$item['url'].'" rel="stylesheet" type="text/css">';
                        break;
	                case 'script' :
		                $result[$key] .= '<script type="text/javascript" src="'.$item['url'].'"></script>';
                        break;
	                default :
                        break;
                }
            }
        }
        return $result;
	}

	private function getTemplateContent($tpl,$data=[])
	{
		ob_start();
		require dirname(__DIR__) . "/Template/default/{$tpl}.php";
		$content = ob_get_clean();
		return $content;
	}
}