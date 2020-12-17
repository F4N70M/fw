<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 26.03.2020
 */

namespace Fw\Components\Services\Files;


use Fw\Components\Services\Database\QueryBuilder;
//use Fw\Components\Services\Entity\Entity;
use Exception;
//use Fw\Components\Services\Items\Item;

class File/* extends Item*/
{
    protected $entityName;
    protected $where = [];
    protected $data;
    protected $Entity;


	public function __construct(QueryBuilder $Db, int $id)
	{
		$entityName = 'files';

        $this->Db = $Db;
        $this->entityName = $entityName;
        $this->where['id'] = $id;
	}

	public function icon()
	{
		$extension = $this->get('extension');
		$mime = $this->get('mime');
		$mimeExplode = explode('/',$mime);
        // var_dump($mimeExplode);
		$tplFileName = (in_array($mimeExplode[0], ['image','video','audio'])) ? $mimeExplode[0] : 'default';
		$tplFile = str_replace('\\', '/', __DIR__).'/src/'.$tplFileName.'.png';

		// наше изображение
		$img = imagecreatefrompng($tplFile);
        // var_dump($tplFile, $img);
		// определяем цвет, в RGB
		$color = imagecolorallocate($img, 255, 255, 255);
		// указываем путь к шрифту
		$font = str_replace('\\', '/', __DIR__).'/src/font/OpenSans-Bold.ttf';
		$fz = 110;
		$w = imagesx($img);
		$h = imagesx($img);
		$text = mb_strtoupper($extension);
		$box = imagettfbbox ( $fz, 0, $font, $text);
		$x = ($w/2)-($box[2]-$box[0])/2; //по оси x
		$y = ($h/2)-($box[5]-$box[3])/2; //по оси y

		imagettftext($img, $fz, 0, $x, $y, $color, $font, $text);
		// 24 - размер шрифта
		// 0 - угол поворота
		// 365 - смещение по горизонтали
		// 159 - смещение по вертикали

		ob_start();
		imagepng($img);
		$result = ob_get_clean();

		return 'data:image/png;base64,'.base64_encode($result).'';
	}


    /**
     * @return array
     * @throws Exception
     */
    public function info()
    {
        if (!is_array($this->data))
            $this->data = ($data = $this->Db->select()->from($this->entityName)->where($this->where)->result()) ? $data[0] : null;
        return $this->data;
    }


    /**
     * @param $key
     * @return bool
     * @throws Exception
     */
    private function has($key)
    {
        $data = $this->info();
        return isset($data[$key]);
    }


    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function get($key)
    {
        if ($this->has($key))
            return $this->data[$key];
        else
            return false;
    }


    /**
     * @param $key
     * @param $value
     * @return bool
     * @throws Exception
     */
    public function set($key,$value)
    {
        if ($this->has($key))
        {
            $result = $this->Entity->update($this -> entityName, [$key => $value], $this -> where);
            if ($result) {
                $this->data[$key] = $value;
            }
            return (bool) $result;
        }
        else
        {
            return false;
        }
    }


    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function edit(array $data)
    {
        return (bool) $this->Entity->update($this->entityName, $data, $this->where);
    }


    /**
     * @return bool
     * @throws Exception
     */
    public function delete()
    {
        $result = $this->Entity->delete($this->entityName, $this->where);
        return $result;
    }

}