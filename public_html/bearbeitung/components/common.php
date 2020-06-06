<?php

include_once dirname(__FILE__) . '/' . 'utils/file_utils.php';
include_once dirname(__FILE__) . '/' . 'utils/system_utils.php';

include_once dirname(__FILE__) . '/' . 'utils/event.php';
include_once dirname(__FILE__) . '/' . 'utils/sm_datetime.php';
include_once dirname(__FILE__) . '/' . 'utils/link_builder.php';

include_once dirname(__FILE__) . '/http_handler/download_http_handler.php';
include_once dirname(__FILE__) . '/http_handler/dynamic_search_http_handler.php';
include_once dirname(__FILE__) . '/http_handler/image_http_handler.php';
include_once dirname(__FILE__) . '/http_handler/grid_edit_http_handler.php';
include_once dirname(__FILE__) . '/http_handler/modal_grid_view_http_handler.php';
include_once dirname(__FILE__) . '/http_handler/modal_delete_http_handler.php';
include_once dirname(__FILE__) . '/http_handler/multilevel_selection_http_handler.php';
include_once dirname(__FILE__) . '/http_handler/page_http_handler.php';
include_once dirname(__FILE__) . '/http_handler/linked_images_http_handler.php';
include_once dirname(__FILE__) . '/http_handler/autocomplete_dataset_based_http_handler.php';
include_once dirname(__FILE__) . '/http_handler/autocomplete_function_based_http_handler.php';
include_once dirname(__FILE__) . '/http_handler/selection_http_handler.php';

$formatsMap = array(

    // День
    'd' => 'DD',
    'D' => 'ddd',
    'j' => 'D',
    'l' => 'dddd',
    'N' => 'E',
    // 'S' => '', // Moment.js не поддерживает php "S" - Английский суффикс порядкового числительного дня месяца, 2 символа
    'w' => 'd',
    'z' => 'DDD',

    // Неделя
    'W' => 'W',

    // Месяц
    'F' => 'MMMM',
    'm' => 'MM',
    'M' => 'MMM',
    'n' => 'M',
    // 't' => '', // Moment.js не поддерживает php "t" - Количество дней в указанном месяце

    // Год
    // 'L' => '', // Moment.js не поддерживает php "L" - Признак високосного года
    'o' => 'GGGG',
    'Y' => 'YYYY',
    'y' => 'YY',

    // Время
    'a' => 'a',
    'A' => 'A',
    // 'B' => '', // Moment.js не поддерживает php "B" - Время в формате Интернет-времени (альтернативной системы отсчета времени суток)
    'g' => 'h',
    'G' => 'H',
    'h' => 'hh',
    'H' => 'HH',
    'i' => 'mm',
    's' => 'ss',
    'u' => 'x',

    // Временная зона
    'e' => 'z',
    // 'I' => '', // Moment.js не поддерживает php "I" - Признак летнего времени
    'O' => 'ZZ',
    'P' => 'Z',
    // 'T' => '', // Moment.js не поддерживает php "T" - Аббревиатура временной зоны. Примеры: EST, MDT ...
    // 'Z' => '', // Moment.js не поддерживает php "Z" - Смещение временной зоны в секундах. Для временных зон,
    // расположенных западнее UTC возвращаются отрицательные числа, а расположенных восточнее UTC - положительные.

    // Полная дата/время
    'c' => 'YYYY-MM-DDTHH:mm:ssZ',
    // 'r' => '', // Moment.js не поддерживает php "r" - Дата в формате » RFC 2822 Например: Thu, 21 Dec 2000 16:01:07 +0200
    'U' => 'X'

);

function ServerToClientConvertFormatDate($dateFormat)
{
    global $formatsMap;
    return strtr($dateFormat, $formatsMap);
}

function ClientToServerConvertFormatDate($dateFormat)
{
    global $formatsMap;
    return strtr($dateFormat, array_flip($formatsMap));
}

/*abstract class ImageFilter
{
    abstract function ApplyFilter(&$imageString, $output = null);
}*/

class ImageFilter
{
    /**
     * @param string $imageString
     * @param null|string $output
     * @return string
     */
    public function ApplyFilter($imageString, $output = null) {
        $image = imagecreatefromstring($imageString);
        $imageSize = array(imagesx($image), imagesy($image));
        $imageWidth = $imageSize[0];
        $imageHeight = $imageSize[1];

        $newImageSize = $this->GetTransformedSize($imageSize);
        $newImageWidth = $newImageSize[0];
        $newImageHeight = $newImageSize[1];

        $result = imagecreatetruecolor($newImageWidth, $newImageHeight);

        imagealphablending($result, false);
        imagesavealpha($result, true);

        $transparent = imagecolorallocatealpha($result, 255, 255, 255, 127);
        imagefilledrectangle($result, 0, 0, $newImageWidth, $newImageHeight, $transparent);

        imagecopyresampled($result, $image, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $imageWidth, $imageHeight);

        if ($output == null)
            return imagepng($result);
        else
            imagepng($result, $output);
    }

    public function GetTransformedSize($imageSize) {
        return $imageSize;
    }

}

class NullFilter extends ImageFilter
{
    function ApplyFilter($imageString, $output = null)
    {
        if ($output == null)
            return $imageString;
        else
            file_put_contents($output, $imageString);
    }
}

class ImageFitByWidthResizeFilter extends ImageFilter
{
    private $width;

    public function __construct($width)
    {
        $this->width = $width;
    }

    public function GetTransformedSize($imageSize)
    {
        $imageWidth = $imageSize[0];
        $imageHeight = $imageSize[1];

        return
            array(
                $imageWidth * $this->width / $imageWidth,
                $imageHeight * $this->width / $imageWidth
            );
    }
}

class ImageFitByHeightResizeFilter extends ImageFilter
{
    private $height;

    function __construct($height)
    {
        $this->height = $height;
    }

    function GetTransformedSize($imageSize)
    {
        $imageWidth = $imageSize[0];
        $imageHeight = $imageSize[1];

        return
            array(
                $imageWidth * $this->height / $imageHeight,
                $imageHeight * $this->height / $imageHeight
            );
    }
}

if (!function_exists('get_called_class'))
{
    function get_called_class()
    {
        $matches = array();
        $bt = debug_backtrace();
        $l = 0;
        do
        {
            $l++;
            $lines = file($bt[$l]['file']);
            $callerLine = $lines[$bt[$l]['line']-1];
            preg_match('/([a-zA-Z0-9\_]+)::'.$bt[$l]['function'].'/', $callerLine, $matches);
        } while (isset($matches[1]) && $matches[1] === 'parent');

        if (isset($matches[1])) {
            return $matches[1];
        }
        else {
            return null;
        }

    }
}
