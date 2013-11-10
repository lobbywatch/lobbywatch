<?php

include_once dirname(__FILE__) . '/' . 'utils/file_utils.php';
include_once dirname(__FILE__) . '/' . 'utils/system_utils.php';

include_once dirname(__FILE__) . '/' . 'utils/event.php';
include_once dirname(__FILE__) . '/' . 'utils/sm_datetime.php';
include_once dirname(__FILE__) . '/' . 'utils/link_builder.php';

$formatsMap = array(
    'd' => 'd',
    'e' => 'j',
    'a' => 'D',
    'A' => 'l',
    'V' => 'W',

    'B' => 'F',
    'b' => 'M',
    'm' => 'm',
    'g' => 'o',
    'Y' => 'Y',
    'y' => 'y',

    'H' => 'H',
    'I' => 'h',
    'l' => 'g',
    'M' => 'i',
    'S' => 's',
    'p' => 'A',
    'P' => 'a');

function OSFormatToDateFormat($osFormat)
{   
    global $formatsMap;
    $result = $osFormat;
    foreach($formatsMap as $osId => $dateId)
        $result = str_replace('%' . $osId, $dateId, $result);
    return $result;
}

function DateFormatToOSFormat($dateFormat)
{   
    global $formatsMap;
    $result = $dateFormat;
    foreach($formatsMap as $osId => $dateId)
        $result = str_replace($dateId, '%' . $osId, $result);
    return $result;
}

abstract class ImageFilter
{
    abstract function ApplyFilter(&$imageString, $output = null);
}

class NullFilter extends ImageFilter
{
    function ApplyFilter(&$imageString, $output = null)
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
        $this->width= $width;
    }

    public function GetTransformedSize($imageSize)
    {
        $imageWidth = $imageSize[0];
        $imageHeight = $imageSize[1];

        $result = array(
            $imageWidth * $this->width / $imageWidth,
            $imageHeight * $this->width / $imageWidth);

        return $result;
    }

    public function echobig($string, $bufferSize = 8192)
    {
        for ($chars=strlen($string)-1,$start=0;$start <= $chars;$start += $bufferSize)
            echo substr($string,$start,$bufferSize);
    }


    public function ApplyFilter(&$imageString, $output = null)
    {
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

        //ImageUtils::EnableAntiAliasing($result);
        imagecopyresampled($result, $image, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $imageWidth, $imageHeight);
        if ($output == null)
            return imagepng($result);
        else
            imagepng($result, $output);

    }

}

class ImageFitByHeightResizeFilter extends ImageFilter
{
    private $height;

    function __construct($Height)
    {
        $this->height = $Height;
    }

    function GetTransformedSize($imageSize)
    {
        $imageWidth = $imageSize[0];
        $imageHeight = $imageSize[1];

        $result = array(
            $imageWidth * $this->height / $imageHeight,
            $imageHeight * $this->height / $imageHeight);

        return $result;
    }

    function echobig($string, $bufferSize = 8192)
    {
        for ($chars=strlen($string)-1,$start=0;$start <= $chars;$start += $bufferSize)
            echo substr($string,$start,$bufferSize);
    }


    function ApplyFilter(&$imageString, $output = null)
    {
        $image = imagecreatefromstring($imageString);
        $imageSize = array(imagesx($image), imagesy($image));
        $imageWidth = $imageSize[0];
        $imageHeight = $imageSize[1];

        $newImageSize = $this->GetTransformedSize($imageSize);
        $newImageWidth = $newImageSize[0];
        $newImageHeight = $newImageSize[1];

        $result = imagecreatetruecolor($newImageWidth, $newImageHeight);
        ImageUtils::EnableAntiAliasing($result);
        imagecopyresampled($result, $image, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $imageWidth, $imageHeight);

        if ($output == null)
            return imagejpeg($result);
        else
            imagejpeg($result, $output);
    }
}

abstract class HTTPHandler
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function GetName()
    {
        return $this->name;
    }

    public abstract function Render(Renderer $renderer);
}

class DownloadHTTPHandler extends HTTPHandler
{
    private $dataset;
    private $fieldName;
    private $contentType;
    private $downloadFileName;

    public function __construct($dataset, $fieldName, $name, $contentType, $downloadFileName, $forceDownload = true)
    {
        parent::__construct($name);
        $this->dataset = $dataset;
        $this->fieldName = $fieldName;
        $this->contentType = $contentType;
        $this->downloadFileName = $downloadFileName;
        $this->forceDownload = $forceDownload;
    }

    public function Render(Renderer $renderer)
    {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);

        $this->dataset->SetSingleRecordState($primaryKeyValues);
        $this->dataset->Open();
        $result = '';
        if ($this->dataset->Next())
            $result = $this->dataset->GetFieldValueByName($this->fieldName);
        $this->dataset->Close();

        header('Content-type: ' . FormatDatasetFieldsTemplate($this->dataset, $this->contentType));
        if ($this->forceDownload)
            header('Content-Disposition: attachment; filename="' . FormatDatasetFieldsTemplate($this->dataset, $this->downloadFileName) . '"');
        
        echo $result;
    }
}

class ImageHTTPHandler extends HTTPHandler
{
    private $dataset;
    private $fieldName;
    private $imageFilter;

    public function __construct($dataset, $fieldName, $name, $imageFilter)
    {
        parent::__construct($name);
        $this->dataset = $dataset;
        $this->fieldName = $fieldName;
        $this->imageFilter = $imageFilter;
    }

    function TransformImage(&$imageString)
    {
        echo $this->imageFilter->ApplyFilter($imageString);
    }

    public function Render(Renderer $renderer)
    {
        $result = '';
        header('Content-type: image');

        $primaryKeyValues = array ( );
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);

        $this->dataset->SetSingleRecordState($primaryKeyValues);
        $this->dataset->Open();
        if ($this->dataset->Next())
            $result = $this->dataset->GetFieldValueByName($this->fieldName);
        $this->dataset->Close();

        if (GetApplication()->IsGETValueSet('large'))
            echo $result;
        else
            $this->TransformImage($result);

        return '';
    }
}

class ShowTextBlobHandler extends HTTPHandler
{
    private $dataset;
    private $fieldName;
    private $parentPage;
    private $caption;
    private $column;

    public function __construct($dataset, $parentPage, $name, $column)
    {
        parent::__construct($name);
        $this->dataset = $dataset;
        $this->parentPage = $parentPage;
        $this->column = $column;
    }

    public function Render(Renderer $renderer)
    {
        echo $renderer->Render($this);
    }

    public function Accept(Renderer $renderer)
    {
        $renderer->RenderTextBlobViewer($this);
    }

    public function GetParentPage()
    { return $this->parentPage; }

    public function GetCaption()
    {
        return $this->parentPage->RenderText($this->column->GetCaption());
    }

    public function GetValue(Renderer $renderer)
    {
        $result = '';
        $primaryKeyValues = array ( );
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);

        $this->dataset->SetSingleRecordState($primaryKeyValues);
        $this->dataset->Open();
        if ($this->dataset->Next())
        {
            if ($this->column == null)
                ;//$result = $this->dataset->GetFieldValueByName($this->fieldName);
            else
                $result = $renderer->Render($this->column);
        }
        $this->dataset->Close();
        return $result;
    }
}

class DynamicSearchHandler extends HTTPHandler
{
    /** @var Dataset */
    private $dataset;
    /** @var string */
    private $name;
    /** @var string */
    private $idField;
    /** @var string */
    private $valueField;
    /** @var string */
    private $captionTemplate;

    /**
     * @param Dataset $dataset
     * @param Page|null $parentPage
     * @param string $name
     * @param string $idField
     * @param string $valueField
     * @param string $captionTemplate
     */
    public function __construct($dataset, $parentPage, $name, $idField, $valueField, $captionTemplate)
    {
        parent::__construct($name);
        $this->dataset = $dataset;
        $this->parentPage = null;
        $this->name = $name;
        $this->idField = $idField;
        $this->valueField = $valueField;
        $this->captionTemplate = $captionTemplate;
    }

    private function GetSuperGlobals()
    {
        return GetApplication()->GetSuperGlobals();
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Render(Renderer $renderer)
    {
        /** @var string $term */
        $term = '';
        if ($this->GetSuperGlobals()->IsGetValueSet('term'))
            $term = $this->GetSuperGlobals()->GetGetValue('term');
        
        if (!StringUtils::IsNullOrEmpty($term))
        {
            $this->dataset->AddFieldFilter(
                $this->valueField,
                new FieldFilter('%'.$term.'%', 'ILIKE', true)
            );

        }

        header('Content-Type: text/html; charset=utf-8');

        $this->dataset->Open();

        $result = array();
        $valueCount = 0;

        $highLightCallback = Delegate::CreateFromMethod($this, 'ApplyHighlight')->Bind(array(
            Argument::$Arg3 => $this->valueField,
            Argument::$Arg4 => $term
        ));

        while ($this->dataset->Next())
        {
            $result[] = array(
                "id" => $this->dataset->GetFieldValueByName($this->idField),
                "value" => (
                        StringUtils::IsNullOrEmpty($this->captionTemplate) ?
                        $this->dataset->GetFieldValueByName($this->valueField) :
                        DatasetUtils::FormatDatasetFieldsTemplate($this->dataset, $this->captionTemplate, $highLightCallback)
                )
            );
            if ($valueCount >= 20)
                break;
        }

        echo SystemUtils::ToJSON($result);
        
        $this->dataset->Close();
    }

    /**
     * @param string $value
     * @param string $currentFieldName
     * @param string $displayFieldName
     * @param string $term
     * @return string
     */
    public function ApplyHighlight($value, $currentFieldName, $displayFieldName, $term)
    {
        if ($currentFieldName == $displayFieldName && !StringUtils::IsNullOrEmpty($term))
        {
            $patterns = array();
            $patterns[0] = '/(' . preg_quote($term) . ')/i';

            $replacements = array();
            $replacements[0] = '<em class="highlight_autocomplete">$1</em>';

            return preg_replace($patterns, $replacements, $value);;
        }
        else
            return $value;
    }
}

class PageHttpHandler extends HTTPHandler
{
    private $page;
    
    public function __construct($name, $page)
    {
        parent::__construct($name);
        $this->page = $page;
    }
    
    public function Render(Renderer $renderer)
    {
        $this->page->BeginRender();
        $this->page->EndRender();
    }
}

?>