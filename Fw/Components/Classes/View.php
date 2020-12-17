<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 27.07.2020
 */

namespace Fw\Components\Classes;


use Exception;
use Fw\Core;

class View
{
	/**
	 * @var Core
	 */
	private $Fw;


	/**
	 * Lk_View constructor.
	 * @param Core $Fw
	 */
	public function __construct(Core $Fw)
	{
		$this->Fw = $Fw;
	}

    /**
     * @param $theme
     * @param $template
     * @param array $data
     * @param array $info
     * @throws Exception
     */
    public function render($theme, $template, $data=[], $info=[])
    {
        $this->data = $data;
        $this->app  = $info;
        $dirPathTheme = THEMES_DIR . '/' . $theme;
        $uriPathTheme = THEMES_URI . '/' . $theme;
        define('THEME_DIR', THEMES_DIR . '/' . $theme);
        define('THEME_URI', THEMES_URI . '/' . $theme);
//        $uriPathTheme = THEMES_URI . '/' . $theme;
        $namePathTemplate = THEME_DIR . '/content/' . $template;

        if (file_exists($filePHP = $namePathTemplate . '.php'))
        {
            $configFile = THEME_DIR . '/config.json';
            if (file_exists($configFile))
            {
                $config = json(file_get_contents($configFile), false);
                $this->templateInit(THEME_URI, $config);
            }
            try
            {
                $assets = $this->Fw->Assets->get();
                $head = $this->getPartRenderContent(THEME_DIR, 'head', $template);
                $header = $this->getPartRenderContent(THEME_DIR, 'header', $template);
                $footer = $this->getPartRenderContent(THEME_DIR, 'footer', $template);
                $main = $this->getPartRenderContent(THEME_DIR, 'content', $template);
//                $main = null;

                require THEME_DIR . '/' . 'base.php';
            }
            catch (Exception $e)
            {
                debug($e);
            }
//		}
//      elseif (file_exists($namePathTemplate . '.json'))
//      {
//			$dirTemplate = THEMES_DIR . '/' . $theme;
//			$te = $this -> Fw -> TemplateEngine;
//			$config = $this -> getConfigTemplate($dirTemplate, $template);
//			$te -> render($config, $data, $info);
        }
        else
        {
            throw new Exception("template \"{$namePathTemplate}\" does not exist.");
        }
    }

	protected function getPartRenderContent($dir, $part,  $template)
    {
        $file = file_exists($fileTmp = $dir.'/'.$part.'/'.$template.'.php')
            ? $fileTmp
            : (
            file_exists($fileTmp = $dir.'/'.$part.'.php')
                ? $fileTmp
                : false
            );
        if ($file)
            return $this->fileGetRenderContent($file);

        return false;
    }

	protected function fileGetRenderContent($filename)
    {
        ob_start();
            require $filename;
        $content = ob_get_clean();
        return $content;
    }

    /**
     * @param $theme
     * @param $template
     * @param array $data
     * @param array $info
     * @throws Exception
     */
    public function renderPopup($theme, $template, $data=[], $info=[])
    {
        $this->data = $data;
        $this->app  = $info;
        $dirPathTheme = THEMES_DIR . '/' . $theme;
        $uriPathTheme = THEMES_URI . '/' . $theme;
        $namePathTemplate = $dirPathTheme . '/content/' . $template;

        if (file_exists($filePHP = $namePathTemplate . '.php'))
        {
            $configFile = $dirPathTheme . '/config.json';
            if (file_exists($configFile))
            {
                $config = json(file_get_contents($configFile), false);
                $this->templateInit($uriPathTheme, $config);
            }
            try
            {
//                $id = $this->data['name'];
                $main = $this->getPartRenderContent($dirPathTheme, 'content', $template);
                require $dirPathTheme . '/' . 'popup.php';
            }
            catch (Exception $e)
            {
                debug($e);
            }
        }
        else
        {
            throw new Exception("template \"{$namePathTemplate}\" does not exist.");
        }
    }

    /**
     * @param $theme
     * @param $template
     * @param array $data
     * @param array $info
     * @throws Exception
     */
    public function renderContent($theme, $template, $data=[], $info=[])
    {
        $this->data = $data;
        $this->app  = $info;
        $dirPathTheme = THEMES_DIR . '/' . $theme;
        $uriPathTheme = THEMES_URI . '/' . $theme;
        $namePathTemplate = $dirPathTheme . '/content/' . $template;

        if (file_exists($filePHP = $namePathTemplate . '.php'))
        {
            $configFile = $dirPathTheme . '/config.json';
            if (file_exists($configFile))
            {
                $config = json(file_get_contents($configFile), false);
                $this->templateInit($uriPathTheme, $config);
            }
            try
            {
                $main = $this->getPartRenderContent($dirPathTheme, 'content', $template);
                echo $main;
            }
            catch (Exception $e)
            {
                debug($e);
            }
        }
        else
        {
            throw new Exception("template \"{$namePathTemplate}\" does not exist.");
        }
    }

	protected function templateInit($uriPathTheme, $config)
	{
        if (isset($config['styles']))
        {
            foreach ($config['styles'] as $key => $name)
            {
                $uri = $uriPathTheme . '/' . $name;
                $this->Fw->Assets->setStyle($name, $uri);
            }
        }
        if (isset($config['scripts']))
        {
            foreach ($config['scripts'] as $key => $name)
            {
                $uri = $uriPathTheme . '/' . $name;
                $this->Fw->Assets->setScript($name, $uri);
            }
        }
	}

	protected function getConfigTemplate($tplDir,$tplName)
	{
		// Получаем массив из файла json
		$tpl = $this->getArrayTemplateConfig($tplDir.'/'.$tplName);
//		debug($tpl);

		// Если это блок
		if (
		isset(
			$tpl['family'],
			$tpl['name']
		)/* or isset(
				$tpl[0]['family'],
				$tpl[0]['name']
			)*/
		)
		{
			$result = $tpl;
		}
		// Если это набор
		if (isset(
			$tpl['header'],
			$tpl['content'],
			$tpl['footer']
		))
		{
			$result = [
				'family' => 'structure',
				'name'   => 'html',
				'blocks' => [
					[
						'family' => 'structure',
						'name'   => 'head'
					],
					[
						'family' => 'structure',
						'name'   => 'body',
						'blocks' => [
							[
								'family' => 'structure',
								'name'   => 'header',
								'blocks' => is_string($tpl['header'])
									? $this->getArrayTemplateConfig($tplDir.'/'.$tpl['header'])
									: (
									is_array($tpl['header'])
										? $tpl['header']
										: []
									)
							],
							[
								'family' => 'structure',
								'name'   => 'main',
								'blocks' => is_string($tpl['content'])
									? $this->getArrayTemplateConfig($tplDir.'/'.$tpl['content'])
									: (
									is_array($tpl['content'])
										? $tpl['content']
										: []
									)
							],
							[
								'family' => 'structure',
								'name'   => 'footer',
								'blocks' => is_string($tpl['footer'])
									? $this->getArrayTemplateConfig($tplDir.'/'.$tpl['footer'])
									: (
									is_array($tpl['footer'])
										? $tpl['footer']
										: []
									)
							]
						]
					]
				]
			];
		}
		return $result;
	}

	protected function getArrayTemplateConfig($path)
	{
		$file = $path.'.json';
		if ( file_exists($file))
		{
			$fileContent = file_get_contents($file);
			if (is_json($fileContent))
				return json($fileContent, false);
		}
		return [];
	}
}