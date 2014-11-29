<?php

include_once dirname(__FILE__) . '/' . '../renderers/renderer.php';
include_once dirname(__FILE__) . '/' . 'editors.php';
include_once dirname(__FILE__) . '/' . '../dataset/dataset.php';
include_once dirname(__FILE__) . '/' . '../common.php';
include_once dirname(__FILE__) . '/' . '../utils/system_utils.php';
include_once dirname(__FILE__) . '/' . '../superglobal_wrapper.php';

// require_once 'components/renderers/renderer.php';
// require_once 'components/editors/editors.php';
// require_once 'components/dataset/dataset.php';
// require_once 'components/common.php';
// require_once 'components/utils/system_utils.php';
// require_once 'components/superglobal_wrapper.php';

class ForeignKeyInfo
{
    private $parentFieldName;
    private $childFieldName;

    public function __construct($parentFieldName, $childFieldName)
    {
        $this->parentFieldName = $parentFieldName;
        $this->childFieldName = $childFieldName;
    }

    public function GetParentFieldName() { return $this->parentFieldName; }

    public function GetChildFieldName() { return $this->childFieldName; }
}

class MultiLevelComboBoxLevelInfo
{
    private $name;
    private $dataUrl;
    private $parentEditor;
    private $dataset;
    private $idFieldName;
    private $captionFieldName;
    private $foreignKey;
    private $caption;

    private $value;
    private $displayValue;
    
    public function __construct($name, $dataUrl, $parentEditor,
        Dataset $dataset, $idFieldName, $captionFieldName, $caption, ForeignKeyInfo $foreignKey = null)
    {
        $this->name = $name;
        $this->dataUrl = $dataUrl;
        $this->parentEditor = $parentEditor;
        $this->dataset = $dataset;
        $this->idFieldName = $idFieldName;
        $this->captionFieldName = $captionFieldName;
        $this->foreignKey = $foreignKey;
        $this->caption = $caption;
    }

    public function GetName() { return $this->name; }

    public function SetName($value) { $this->name = $value; }
    
    public function GetDataUrl() { return $this->dataUrl; }

    public function GetParentEditor() { return $this->parentEditor; }
    public function SetParentEditor($value) { $this->parentEditor = $value; }

    public function GetDataset() { return $this->dataset; }

    public function GetIdFieldName() { return $this->idFieldName; }

    public function GetCaptionFieldName() { return $this->captionFieldName; }

    public function GetForeignKey() { return $this->foreignKey; }

    public function GetValue() { return $this->value; }

    public function SetValue($value) { $this->value = $value; }

    public function GetDisplayValue() { return $this->displayValue; }

    public function SetDisplayValue($displayValue) { $this->displayValue = $displayValue; }

    public function GetCaption() { return $this->caption; }
}

class MultiLevelComboBoxEditor extends CustomEditor
{
    /** @var MultiLevelComboBoxLevelInfo[] */
    private $levels = array();
    private $value;
    private $linkBuilder;

    public function __construct($name, LinkBuilder $linkBuilder) {
        parent::__construct($name);
        $this->linkBuilder = $linkBuilder;
    }

    public function SetName($value) {
        parent::SetName($value);

        $levelNumber = 0;
        foreach($this->GetLevels() as $level)
        {
            $level->SetName($this->GetEditorName($levelNumber));
            if ($levelNumber > 0)
                $level->SetParentEditor($this->GetEditorName($levelNumber - 1));
            $levelNumber++;
        }
    }

    private function GetEditorName($level) {
        return $this->GetName() . '_editor_level_' . $level;
    }

    public function AddLevel(Dataset $dataset, $idFieldName, $captionFieldName, $caption, ForeignKeyInfo $foreignKey = null) {
        $handlerName = $this->GetName() . $this->GetLevelCount() . '_h';
        
        GetApplication()->RegisterHTTPHandler(
            new MultiLevelSelectionHandler(
                $handlerName,
                $dataset, $idFieldName, $captionFieldName,
                $foreignKey == null ? null : $foreignKey->GetChildFieldName(),
                $this->GetSuperGlobals()
            )
        );

        $linkBuilder = $this->linkBuilder->CloneLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $handlerName);

        $this->levels[] = new MultiLevelComboBoxLevelInfo(
            $this->GetEditorName($this->GetLevelCount()),
            $linkBuilder->GetLink(),
            $this->GetLevelCount() == 0 ?
                null :
                $this->GetEditorName($this->GetLevelCount() - 1),
            $dataset, $idFieldName, $captionFieldName, $caption, $foreignKey        
        );

    }

    public function ProcessLevelValues()
    {
        /** @var $reversedLevels MultiLevelComboBoxLevelInfo[] */
        $reversedLevels = array_reverse($this->levels);

        $isFirstLevel = true;
        $parentIdFieldName = '';
        $parentIdValue = '';
        foreach($reversedLevels as $level)
        {
            $dataset = $level->GetDataset();

            if ($isFirstLevel)
            {
                $dataset->AddFieldFilter(
                    $level->GetIdFieldName(),
                    FieldFilter::Equals($this->value)
                );
                $isFirstLevel = false;
            }
            else
            {
                $dataset->AddFieldFilter(
                    $parentIdFieldName,
                    FieldFilter::Equals($parentIdValue)
                );
            }

            $dataset->Open();
            if ($dataset->Next())
            {
                $level->SetDisplayValue($dataset->GetFieldValueByName($level->GetCaptionFieldName()));
                $level->SetValue($dataset->GetFieldValueByName($level->GetIdFieldName()));
            }
            $dataset->Close();

            if ($level->GetForeignKey() != null)
            {
                $parentIdFieldName = $level->GetForeignKey()->GetParentFieldName();
                $parentIdValue = $dataset->GetFieldValueByName($level->GetForeignKey()->GetChildFieldName());
            }
        }
    }

    public function GetValue()
    {
        return $this->value;
    }

    public function SetValue($value)
    {
        $this->value = $value;
    }

    public function GetDataEditorClassName() {
        return 'MultiLevelAutocomplete';
    }

    /**
     * @return MultiLevelComboBoxLevelInfo[]
     */
    public function GetLevels()
    {
        return $this->levels;
    }
    
    public function GetLevelCount()
    {
        return count($this->levels);
    }

    public function Accept(Renderer $renderer)
    {
        $renderer->RenderMultiLevelComboBoxEditor($this);
    }

    public function ExtractsValueFromPost(&$changed)
    {
        $editorName = $this->GetEditorName($this->GetLevelCount() - 1);
        if ($this->GetSuperGlobals()->IsPostValueSet($editorName))
        {
            $changed = true;

            $value = GetApplication()->GetPOSTValue($editorName);
            return $value;
        }
        else
        {
            $changed = false;
            return null;
        }
    }
}

class MultiLevelSelectionHandler extends HTTPHandler
{
    private $dataset;
    private $idFieldName;
    private $captionFieldName;
    private $parentIdFieldName;
    private $globals;

    const SearchTermParamName = 'term';
    const ParentParamName = 'term2';

    public function __construct($name, Dataset $dataset,
        $idFieldName, $captionFieldName, $parentIdFieldName,
        SuperGlobals $globals)
    {
        parent::__construct($name);
        $this->dataset = $dataset;
        $this->idFieldName = $idFieldName;
        $this->captionFieldName = $captionFieldName;
        $this->parentIdFieldName = $parentIdFieldName;
        $this->globals = $globals;
    }

    public function Render(Renderer $renderer)
    {
        $result = array();

        $this->dataset->AddFieldFilter(
            $this->captionFieldName,
            FieldFilter::Contains(
                $this->globals->GetGetValue(self::SearchTermParamName)));

        $highLightCallback = Delegate::CreateFromMethod($this, 'ApplyHighlight')->Bind(array(
            Argument::$Arg3 => $this->captionFieldName,
            Argument::$Arg4 => $this->globals->GetGetValue(self::SearchTermParamName)
        ));

        if (!StringUtils::IsNullOrEmpty($this->parentIdFieldName) &&
            $this->globals->IsGetValueSet(self::ParentParamName))
            $this->dataset->AddFieldFilter(
                $this->parentIdFieldName,
                FieldFilter::Equals(
                    $this->globals->GetGetValue(self::ParentParamName)));

        $this->dataset->Open();
        while ($this->dataset->Next())
            $result[] = array(
                'id' => $this->dataset->GetFieldValueByName($this->idFieldName),
                'value' => $this->dataset->GetFieldValueByName($this->captionFieldName),
                'label' => $highLightCallback->Call(
                    $this->dataset->GetFieldValueByName($this->captionFieldName),
                    $this->captionFieldName)
            );
        $this->dataset->Close();

        
        echo SystemUtils::ToJSON($result);
    }

    public function ApplyHighlight($value, $currentFieldName, $displayFieldName, $term)
    {
        if ($currentFieldName == $displayFieldName && !StringUtils::IsNullOrEmpty($term))
        {
            $patterns = array();
            $patterns[0] = '/(' . preg_quote($term) . ')/i';
            $replacements = array();
            $replacements[0] = '<em class="highlight_autocomplete">' . '$1' . '</em>';
            return preg_replace($patterns, $replacements, $value);
        }
        else
            return $value;
    }
}
