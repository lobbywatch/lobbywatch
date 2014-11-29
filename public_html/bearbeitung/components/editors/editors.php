<?php

include_once dirname(__FILE__) . '/' . '../component.php';
include_once dirname(__FILE__) . '/' . '../utils/file_utils.php';
include_once dirname(__FILE__) . '/' . '../utils/string_utils.php';
include_once dirname(__FILE__) . '/' . '../utils/html_utils.php';
include_once dirname(__FILE__) . '/' . 'validators.php';

// require_once 'components/component.php';
// require_once 'components/utils/file_utils.php';
// require_once 'components/utils/string_utils.php';
// require_once 'components/utils/html_utils.php';
// require_once 'components/editors/validators.php';


/**
 * @property mixed GetViewData
 */
abstract class CustomEditor extends Component {
    /** @var null|string */
    private $customAttributes;

    /** @var null|string */
    private $inlineStyles;

    /** @var boolean */
    private $readOnly;

    /** @var boolean */
    private $visible;

    /** @var boolean */
    private $enabled;


    /** @var \SuperGlobals */
    private $superGlobals;

    /** @var string */
    private $fieldName;

    /** @var \ValidatorCollection */
    private $validators;

    /**
     * @param string $name
     * @param string $customAttributes
     */
    public function __construct($name, $customAttributes = null) {
        parent::__construct($name);
        $this->customAttributes = $customAttributes;
        $this->inlineStyles = null;
        $this->readOnly = false;
        $this->visible = true;
        $this->enabled = true;
        $this->superGlobals = GetApplication()->GetSuperGlobals();
        $this->validators = new ValidatorCollection();
        $this->fieldName = null;
    }

    /**
     * @return bool
     */
    protected function SuppressRequiredValidation() {
        return false;
    }

    /**
     * @return string
     */
    public final function GetValidationAttributes() {
        return $this->GetValidatorCollection()->GetInputAttributes($this->SuppressRequiredValidation());
    }

    /**
     * @return ValidatorCollection
     */
    public final function GetValidatorCollection() {
        return $this->validators;
    }

    protected final function GetSuperGlobals() {
        return $this->superGlobals;
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept(Renderer $renderer) {
        assert(false);
    }

    /**
     * @param boolean $valueChanged
     * @return string
     */
    public function ExtractsValueFromPost(&$valueChanged) {
        $valueChanged = false;
        return '';
    }

    /**
     * @abstract
     * @return mixed
     */
    public abstract function GetValue();

    /**
     * @abstract
     * @param mixed $value
     * @return void
     */
    public abstract function SetValue($value);

    /**
     * @return null|string
     */
    public function GetDataEditorClassName() {
        return null;
    }

    /**
     * @return string
     */
    public function GetFieldName() {
        return $this->fieldName;
    }

    /**
     * @param string $value
     * @return void
     */
    public function SetFieldName($value) {
        $this->fieldName = $value;
    }

    /**
     * @return boolean
     */
    public function GetReadOnly() {
        return $this->readOnly;
    }

    /**
     * @param boolean $value
     * @return void
     */
    public function SetReadOnly($value) {
        $this->readOnly = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setCustomAttributes($value) {
        $this->customAttributes = $value;
    }

    /**
     * @return null|string
     */
    public function getCustomAttributes() {
        return $this->customAttributes;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setInlineStyles($value) {
        $this->inlineStyles = $value;
    }

    /**
     * @return null|string
     */
    public function getInlineStyles() {
        return $this->inlineStyles;
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function prepareValueForDataset($value) {
        return $value;
    }

    /**
     * @return bool
     */
    public function getVisible() {
        return $this->visible;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setVisible($value) {
        $this->visible = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getEnabled() {
        return $this->enabled;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setEnabled($value) {
        $this->enabled = $value;
        return $this;
    }
}

class TextAreaEdit extends CustomEditor {
    private $value;
    private $columnCount;
    private $rowCount;
    private $allowHtmlCharacters = true;
    /** @var string */
    private $placeholder = null;

    public function __construct($name, $columnCount = null, $rowCount = null, $customAttributes = null) {
        parent::__construct($name, $customAttributes);
        $this->columnCount = $columnCount;
        $this->rowCount = $rowCount;
    }

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function GetDataEditorClassName() {
        return 'TextArea';
    }

    #region Editor options

    public function SetColumnCount($value) {
        $this->columnCount = $value;
    }

    public function GetColumnCount() {
        return $this->columnCount;
    }

    public function SetRowCount($value) {
        $this->rowCount = $value;
    }

    public function GetRowCount() {
        return $this->rowCount;
    }

    public function GetAllowHtmlCharacters() {
        return $this->allowHtmlCharacters;
    }

    public function SetAllowHtmlCharacters($value) {
        $this->allowHtmlCharacters = $value;
    }

    public function getPlaceholder() {
        return $this->placeholder;
    }

    public function setPlaceholder($value) {
        $this->placeholder = $value;
        return $this;
    }

    #endregion

    public function ExtractsValueFromPost(&$valueChanged) {
        if (GetApplication()->IsPOSTValueSet($this->GetName())) {
            $valueChanged = true;
            $value = GetApplication()->GetPOSTValue($this->GetName());
            if (isset($value) && !$this->allowHtmlCharacters)
                $value = htmlspecialchars($value, ENT_QUOTES);
            return $value;
        } else {
            $valueChanged = false;
            return null;
        }
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderTextAreaEdit($this);
    }
}

class TextEdit extends CustomEditor {
    private $value;
    private $size = null;
    private $maxLength = null;
    private $allowHtmlCharacters = true;
    private $passwordMode;
    /** @var string */
    private $placeholder = null;
    /** @var string */
    private $prefix = null;
    /** @var string */
    private $suffix = null;

    public function __construct($name, $size = null, $maxLength = null, $customAttributes = null) {
        parent::__construct($name, $customAttributes);
        $this->size = $size;
        $this->maxLength = $maxLength;
        $this->passwordMode = false;
    }

    public function SetSize($value) {
        $this->size = $value;
    }

    public function GetSize() {
        return $this->size;
    }

    public function SetMaxLength($value) {
        $this->maxLength = $value;
    }

    public function GetMaxLength() {
        return $this->maxLength;
    }

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function GetDataEditorClassName() {
        return 'TextEdit';
    }

    public function GetPasswordMode() {
        return $this->passwordMode;
    }

    public function SetPasswordMode($value) {
        $this->passwordMode = $value;
    }

    public function GetHTMLValue() {
        return str_replace('"', '&quot;', $this->value);
    }

    public function GetAllowHtmlCharacters() {
        return $this->allowHtmlCharacters;
    }

    public function SetAllowHtmlCharacters($value) {
        $this->allowHtmlCharacters = $value;
    }

    public function getPlaceholder() {
        return $this->placeholder;
    }

    public function setPlaceholder($value) {
        $this->placeholder = $value;
        return $this;
    }

    public function getPrefix() {
        return $this->prefix;
    }

    public function setPrefix($value) {
        $this->prefix = $value;
        return $this;
    }

    public function getSuffix() {
        return $this->suffix;
    }

    public function setSuffix($value) {
        $this->suffix = $value;
        return $this;
    }

    public function ExtractsValueFromPost(&$valueChanged) {
        if (GetApplication()->IsPOSTValueSet($this->GetName())) {
            $valueChanged = true;
            $value = GetApplication()->GetPOSTValue($this->GetName());
            if (isset($value) && !$this->allowHtmlCharacters)
                $value = htmlspecialchars($value, ENT_QUOTES);
            return $value;
        } else {
            $valueChanged = false;
            return null;
        }
    }

    public function Accept(Renderer $Renderer) {
        $Renderer->RenderTextEdit($this);
    }
}

class TimeEdit extends CustomEditor {
    private $value;

    public function __construct($name) {
        parent::__construct($name);
    }

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function GetDataEditorClassName() {
        return 'TimeEdit';
    }

    public function Accept(Renderer $Renderer) {
        $Renderer->RenderTimeEdit($this);
    }

    public function ExtractsValueFromPost(&$valueChanged) {
        if (GetApplication()->IsPOSTValueSet($this->GetName())) {
            $valueChanged = true;
            $value = GetApplication()->GetPOSTValue($this->GetName());
            if (StringUtils::IsNullOrEmpty($value))
                return null;
            else
                return GetApplication()->GetPOSTValue($this->GetName());
        } else {
            $valueChanged = false;
            return null;
        }
    }
}

class MaskedEdit extends CustomEditor {
    private $value;
    private $mask;
    private $hint;

    /**
     * @param string $name
     * @param string $mask see http://digitalbush.com/projects/masked-input-plugin/ for details
     * @param string $hint
     */
    public function __construct($name, $mask, $hint = '') {
        parent::__construct($name, null);
        $this->mask = $mask;
        $this->hint = $hint;
    }

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function GetDataEditorClassName() {
        return 'MaskEdit';
    }

    public function Accept(Renderer $Renderer) {
        $Renderer->RenderMaskedEdit($this);
    }

    public function ExtractsValueFromPost(&$valueChanged) {
        if (GetApplication()->IsPOSTValueSet($this->GetName())) {
            $valueChanged = true;
            return GetApplication()->GetPOSTValue($this->GetName());
        } else {
            $valueChanged = false;
            return null;
        }
    }

    public function GetMask() {
        return $this->mask;
    }

    public function GetHint() {
        return $this->hint;
    }
}

class SpinEdit extends CustomEditor {
    private $value;
    private $useConstraints = false;
    private $minValue;
    private $maxValue;

    public function GetMaxValue() {
        return $this->maxValue;
    }

    public function SetMaxValue($value) {
        $this->maxValue = $value;
    }

    public function GetMinValue() {
        return $this->minValue;
    }

    public function SetMinValue($value) {
        $this->minValue = $value;
    }

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function GetDataEditorClassName() {
        return 'SpinEdit';
    }

    public function SetUseConstraints($value) {
        $this->useConstraints = $value;
    }

    public function GetUseConstraints() {
        return $this->useConstraints;
    }

    public function ExtractsValueFromPost(&$valueChanged) {
        if (GetApplication()->IsPOSTValueSet($this->GetName())) {
            $valueChanged = true;
            return GetApplication()->GetPOSTValue($this->GetName());
        } else {
            $valueChanged = false;
            return null;
        }
    }

    public function Accept(Renderer $Renderer) {
        $Renderer->RenderSpinEdit($this);
    }
}

class RangeEdit extends SpinEdit {

    public function GetDataEditorClassName() {
        return 'RangeEdit';
    }

    public function Accept(Renderer $Renderer) {
        $Renderer->RenderRangeEdit($this);
    }
}

class CheckBox extends CustomEditor {
    private $value;

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function GetDataEditorClassName() {
        return 'CheckBox';
    }

    protected function SuppressRequiredValidation() {
        return true;
    }

    public function ExtractsValueFromPost(&$valueChanged) {
        if ($this->GetReadOnly()) {
            $valueChanged = false;
            return null;
        } else {
            $valueChanged = true;
            return GetApplication()->IsPOSTValueSet($this->GetName()) ? '1' : '0';
        }
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderCheckBox($this);
    }

    public function Checked() {
        return (isset($this->value) && !empty($this->value));
    }
}

class ColorEdit extends CustomEditor {
    private $value;

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function GetDataEditorClassName() {
        return 'ColorEdit';
    }

    public function ExtractsValueFromPost(&$valueChanged) {
        if (GetApplication()->IsPOSTValueSet($this->GetName())) {
            $valueChanged = true;
            return GetApplication()->GetPOSTValue($this->GetName());
        } else {
            $valueChanged = false;
            return null;
        }
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderColorEdit($this);
    }
}

class DateTimeEdit extends CustomEditor {
    /** @var SMDateTime */
    private $value;
    private $showsTime;
    private $format;
    private $firstDayOfWeek;

    public function __construct($name, $showsTime = false, $format = null, $firstDayOfWeek = 0) {
        parent::__construct($name);
        $this->showsTime = $showsTime;

        if (!isset($format))
            $this->format = $this->showsTime ? 'Y-m-d H:i:s' : 'Y-m-d';
        else
            $this->format = $format;
        $this->firstDayOfWeek = $firstDayOfWeek;
    }

    public function GetFirstDayOfWeek() {
        return $this->firstDayOfWeek;
    }

    public function GetValue() {
        if (isset($this->value))
            return $this->value->ToString($this->format);
        else
            return '';
    }

    public function SetValue($value) {
        if (!StringUtils::IsNullOrEmpty($value))
            $this->value = SMDateTime::Parse($value, $this->format);
        else
            $this->value = null;
    }

    public function GetDataEditorClassName() {
        return 'DateTimeEdit';
    }

    public function GetFormat() {
        return DateFormatToOSFormat($this->format);
    }

    public function SetFormat($value) {
        $this->format = $value;
    }

    public function GetShowsTime() {
        return $this->showsTime;
    }

    public function SetShowsTime($value) {
        $this->showsTime = $value;
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderDateTimeEdit($this);
    }

    public function ExtractsValueFromPost(&$valueChanged) {
        if (GetApplication()->IsPOSTValueSet($this->GetName())) {
            $valueChanged = true;
            $value = GetApplication()->GetPOSTValue($this->GetName());
            if ($value == '')
                return null;
            else
                return $value;
                // return SMDateTime::Parse($value, $this->format);
        } else {
            $valueChanged = false;
            return null;
        }
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function prepareValueForDataset($value) {
        return SMDateTime::Parse($value, $this->format);
    }
}

class ComboBox extends CustomEditor {
    private $values;
    private $selectedValue;
    private $emptyValue;
    //
    private $mfuValues;
    private $preparedMfuValues;
    private $displayValues;

    public function __construct($name, $emptyValue = '') {
        parent::__construct($name);
        $this->values = array();
        $this->selectedValue = null;
        $this->emptyValue = $emptyValue;
        $this->values[''] = $emptyValue;
        $this->displayValues = array();
        $this->mfuValues = array();
        $this->preparedMfuValues = null;
    }

    public function GetSelectedValue() {
        return $this->selectedValue;
    }

    public function SetSelectedValue($selectedValue) {
        $this->selectedValue = $selectedValue;
    }

    public function AddValue($value, $name) {
        $this->values[$value] = $name;
        $this->displayValues[$value] = $name;
    }

    public function GetValues() {
        return $this->values;
    }

    public function GetDisplayValues() {
        return $this->displayValues;
    }

    public function ShowEmptyValue() {
        return true;
    }

    public function GetEmptyValue() {
        return $this->emptyValue;
    }

    public function AddMFUValue($value) {
        $this->mfuValues[] = $value;
    }

    private function PrepareMFUValues() {
        $this->preparedMfuValues = array();
        foreach ($this->mfuValues as $mfuValue) {
            if (array_key_exists($mfuValue, $this->values))
                $this->preparedMfuValues[$mfuValue] = $this->values[$mfuValue];
            elseif (in_array($mfuValue, $this->values)) {
                $key = array_search($mfuValue, $this->values);
                $this->preparedMfuValues[$key] = $mfuValue;
            }
        }
    }

    public function HasMFUValues() {
        return count($this->mfuValues) > 0;
    }

    public function GetMFUValues() {
        if ($this->HasMFUValues()) {
            if (!isset($this->preparedMfuValues))
                $this->PrepareMFUValues();
            return $this->preparedMfuValues;
        } else
            return array();
    }

    public function GetValue() {
        return $this->selectedValue;
    }

    public function SetValue($value) {
        $this->selectedValue = $value;
    }

    public function GetDataEditorClassName() {
        return 'ComboBox';
    }

    protected function DoSetAllowNullValue($value) {
        if ($value) {
            $this->values[''] = $this->emptyValue;
        } else {
            if (isset($this->values['']))
                unset($this->values['']);
        }
    }

    public function ExtractsValueFromPost(&$valueChanged) {
        if (GetApplication()->IsPOSTValueSet($this->GetName())) {
            $valueChanged = true;
            $value = GetApplication()->GetPOSTValue($this->GetName());
            if ($value == '')
                return null;
            else
                return $value;
        } else {
            $valueChanged = false;
            return null;
        }
    }

    public function CanSetupNullValues() {
        return false;
    }

    public function Accept(Renderer $Renderer) {
        $Renderer->RenderComboBox($this);
    }

    public function IsSelectedValue($value) {
        return (isset($this->selectedValue)) && ($this->GetSelectedValue() == $value);
    }

}

class AutocomleteComboBox extends CustomEditor {
    /** @var string */
    private $value;

    /** @var string */
    private $displayValue;

    /** @var string */
    private $handlerName;

    /** @var string */
    private $size;

    /** @var \LinkBuilder */
    private $linkBuilder;

    /**
     * @param string $name
     * @param LinkBuilder $linkBuilder
     */
    public function __construct($name, LinkBuilder $linkBuilder) {
        parent::__construct($name);
        $this->size = '260px';
        $this->linkBuilder = $linkBuilder;
    }

    /**
     * @return string
     */
    public function GetDisplayValue() {
        return $this->displayValue;
    }

    /**
     * @param string $value
     * @return void
     */
    public function SetDisplayValue($value) {
        $this->displayValue = $value;
    }

    /**
     * @return string
     */
    public function GetValue() {
        return $this->value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function SetValue($value) {
        $this->value = $value;
    }

    public function GetDataEditorClassName() {
        return 'Autocomplete';
    }

    #region Options

    /**
     * @return string
     */
    public function GetSize() {
        return $this->size;
    }

    /**
     * @param string $value
     * @return void
     */
    public function SetSize($value) {
        $this->size = $value;
    }

    #endregion

    public function ExtractsValueFromPost(&$valueChanged) {
        if ($this->GetSuperGlobals()->IsPostValueSet($this->GetName())) {
            $valueChanged = true;
            return $this->GetSuperGlobals()->GetPostValue($this->GetName());
        } else {
            $valueChanged = false;
            return null;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function SetHandlerName($value) {
        $this->handlerName = $value;
    }

    /**
     * @return string
     */
    public function GetHandlerName() {
        return $this->handlerName;
    }

    /**
     * @return string
     */
    public function GetDataUrl() {
        $linkBuilder = $this->linkBuilder->CloneLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->GetHandlerName());
        return $linkBuilder->GetLink();
    }

    /**
     * @param Renderer $Renderer
     * @return void
     */
    public function Accept(Renderer $Renderer) {
        $Renderer->RenderAutocompleteComboBox($this);
    }

    /**
     * @return bool
     */
    public function CanSetupNullValues() {
        return true;
    }

    /**
     * @return bool
     */
    public function GetSetToNullFromPost() {
        if (GetApplication()->IsPOSTValueSet($this->GetName()))
            return GetApplication()->GetPOSTValue($this->GetName()) == '';
        return true;
    }
}

class RadioEdit extends CustomEditor {
    /** @var array */
    private $values;
    /** @var null|string */
    private $selectedValue;
    /** @var int */
    private $displayMode;

    const StackedMode = 0;
    const InlineMode = 1;

    public function __construct($name) {
        parent::__construct($name);
        $this->values = array();
        $this->selectedValue = null;
        $this->displayMode = self::StackedMode;
    }

    public function GetSelectedValue() {
        return $this->selectedValue;
    }

    public function SetSelectedValue($selectedValue) {
        $this->selectedValue = $selectedValue;
    }

    public function AddValue($value, $name) {
        $this->values[$value] = $name;
    }

    public function GetValues() {
        return $this->values;
    }

    public function GetValue() {
        return $this->selectedValue;
    }

    public function SetValue($value) {
        $this->selectedValue = $value;
    }

    public function GetDataEditorClassName() {
        return 'RadioGroup';
    }

    public function GetDisplayMode() {
        return $this->displayMode;
    }

    public function SetDisplayMode($value) {
        $this->displayMode = $value;
    }

    /** @return bool */
    public function IsInlineMode() {
        return $this->displayMode == self::InlineMode;
    }

    public function ExtractsValueFromPost(&$valueChanged) {
        if (GetApplication()->IsPOSTValueSet($this->GetName())) {
            $valueChanged = true;
            return GetApplication()->GetPOSTValue($this->GetName());
        } else {
            $valueChanged = false;
            return null;
        }
    }

    public function Accept(Renderer $Renderer) {
        $Renderer->RenderRadioEdit($this);
    }

}

class CheckBoxGroup extends CustomEditor {
    /** @var array */
    private $values;
    /** @var array */
    private $selectedValues;
    /** @var int */
    private $displayMode;

    const StackedMode = 0;
    const InlineMode = 1;

    /** @param string $name */
    public function __construct($name) {
        parent::__construct($name);
        $this->values = array();
        $this->selectedValues = array();
        $this->displayMode = self::StackedMode;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function IsValueSelected($value) {
        return in_array($value, $this->selectedValues);
    }

    /**
     * @param string $value
     * @param string $name
     */
    public function AddValue($value, $name) {
        $this->values[$value] = $name;
    }

    /**
     * @return array
     */
    public function GetValues() {
        return $this->values;
    }

    /**
     * @return mixed|string
     */
    public function GetValue() {
        $result = '';
        foreach ($this->selectedValues as $selectedValue)
            AddStr($result, $selectedValue, ',');
        return $result;
    }

    /**
     * @param mixed $value
     */
    public function SetValue($value) {
        $this->selectedValues = explode(',', $value);
    }

    public function GetDataEditorClassName() {
        return 'CheckBoxGroup';
    }

    public function GetDisplayMode() {
        return $this->displayMode;
    }

    public function SetDisplayMode($value) {
        $this->displayMode = $value;
    }

    /** @return bool */
    public function IsInlineMode() {
        return $this->displayMode == self::InlineMode;
    }

    /**
     * @param bool $valueChanged
     * @return string
     */
    public function ExtractsValueFromPost(&$valueChanged) {
        if (GetApplication()->IsPOSTValueSet($this->GetName())) {
            $valueChanged = true;
            $valuesArray = GetApplication()->GetPOSTValue($this->GetName());
            $result = '';
            foreach ($valuesArray as $value)
                AddStr($result, $value, ',');
            return $result;
        } else {
            $valueChanged = true;
            return '';
        }
    }

    public function Accept(Renderer $Renderer) {
        $Renderer->RenderCheckBoxGroup($this);
    }
}

class ImageUploader extends CustomEditor {
    private $showImage;
    private $imageLink;

    public function __construct($name) {
        parent::__construct($name);
        $this->showImage = false;
    }

    public function GetShowImage() {
        return $this->showImage;
    }

    public function SetShowImage($value) {
        $this->showImage = $value;
    }

    public function GetLink() {
        return $this->imageLink;
    }

    public function SetLink($value) {
        $this->imageLink = $value;
    }

    public function ExtractImageActionFromPost() {
        if (GetApplication()->IsPOSTValueSet($this->GetName() . "_action"))
            return GetApplication()->GetPOSTValue($this->GetName() . "_action");
        else
            return KEEP_IMAGE_ACTION;
    }

    public function GetValue() {
        return null;
    }

    public function SetValue($value) {
        ;
    }

    public function ExtractsValueFromPost(&$valueChanged) {
        $action = $this->ExtractImageActionFromPost();

        if ($action == REMOVE_IMAGE_ACTION) {
            $valueChanged = true;
            return null;
        } elseif ($action == REPLACE_IMAGE_ACTION) {
            $filename = $_FILES[$this->GetName() . "_filename"]["tmp_name"];
            $valueChanged = true;
            return $filename;
        } else {
            $valueChanged = false;
            return null;
        }
    }

    public function ExtractFileTypeFromPost() {
        $action = $this->ExtractImageActionFromPost();

        if ($action == REMOVE_IMAGE_ACTION)
            return null;
        elseif ($action == REPLACE_IMAGE_ACTION) {
            $clientFileName = $_FILES[$this->GetName() . "_filename"]["name"];
            return Path::GetFileExtension($clientFileName);
        } else
            return null;
    }

    public function ExtractFileNameFromPost() {
        $action = $this->ExtractImageActionFromPost();

        if ($action == REMOVE_IMAGE_ACTION)
            return null;
        elseif ($action == REPLACE_IMAGE_ACTION) {
            $clientFileName = $_FILES[$this->GetName() . "_filename"]["name"];
            return Path::GetFileTitle($clientFileName);
        } else
            return null;
    }

    public function ExtractFileSizeFromPost() {
        $action = GetApplication()->GetPOSTValue($this->GetName() . "_action");
        $fileSize = $_FILES[$this->GetName() . "_filename"]["size"];

        if ($action == REMOVE_IMAGE_ACTION)
            return null;
        elseif ($action == REPLACE_IMAGE_ACTION)
            return $fileSize; else
            return null;
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderImageUploader($this);
    }
}

class HtmlWysiwygEditor extends CustomEditor {
    private $value;
    private $allowColorControls;
    private $columnCount;
    private $rowCount;

    public function __construct($name, $customAttributes = null) {
        parent::__construct($name, $customAttributes);
        $this->allowColorControls = false;
    }

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function GetDataEditorClassName() {
        return 'HtmlEditor';
    }

    public function SetColumnCount($value) {
        $this->columnCount = $value;
    }

    public function GetColumnCount() {
        return $this->columnCount;
    }

    public function SetRowCount($value) {
        $this->rowCount = $value;
    }

    public function GetRowCount() {
        return $this->rowCount;
    }

    #region WYSIWYG Editor Options

    public function GetAllowColorControls() {
        return $this->allowColorControls;
    }

    public function SetAllowColorControls($value) {
        $this->allowColorControls = $value;
    }

    #endregion

    public function ExtractsValueFromPost(&$valueChanged) {
        if (GetApplication()->IsPOSTValueSet($this->GetName())) {
            $valueChanged = true;
            return GetApplication()->GetPOSTValue($this->GetName());
        } else {
            $valueChanged = false;
            return null;
        }
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderHtmlWysiwygEditor($this);
    }

}
