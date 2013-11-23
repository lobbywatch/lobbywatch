<?php

abstract class Validator
{
    private $message;

    public function __construct($message = null)
    {
        $this->message = $message;
    }

    public abstract function GetValidatorName();

    protected function DoGetAdditionalAttributes()
    {
        return '';
    }

    public function GetMessage()
    {
        return $this->message;
    }

    public function GetAdditionalAttributes()
    {
        $result = '';
        if (!StringUtils::IsNullOrEmpty($this->message))
            StringUtils::AddStr($result,
                StringUtils::Format('data-%s-error-message="%s"', $this->GetValidatorName(), $this->GetMessage()), ' '
            );
        StringUtils::AddStr($result, $this->DoGetAdditionalAttributes(), ' ');
        return $result;
    }
}

class RequiredValidator extends Validator
{
    public function GetValidatorName()
    {
        return 'required';
    }
}

class MaxValueValidator extends Validator
{
    private $maxValue;

    public function __construct($maxValue, $message = null)
    {
        parent::__construct($message);
        $this->maxValue = $maxValue;
    }

    public function GetMessage()
    {
        return StringUtils::Format(parent::GetMessage(), $this->maxValue);
    }

    public function GetValidatorName()
    {
        return 'max-value';
    }

    protected function DoGetAdditionalAttributes()
    {
        return StringUtils::Format('max-value="%d"', $this->maxValue);
    }
}

class MinValueValidator extends Validator
{
    private $minValue;

    public function __construct($minValue, $message = null)
    {
        parent::__construct($message);
        $this->minValue = $minValue;
    }

    public function GetMessage()
    {
        return StringUtils::Format(parent::GetMessage(), $this->minValue);
    }    

    public function GetValidatorName()
    {
        return 'min-value';
    }

    protected function DoGetAdditionalAttributes()
    {
        return StringUtils::Format('min-value="%d"', $this->minValue);
    }
}

class RangeValidator extends Validator
{
    private $minValue;
    private $maxValue;

    public function __construct($minValue, $maxValue, $message = null)
    {
        parent::__construct($message);
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
    }

    public function GetMessage()
    {
        return StringUtils::Format(parent::GetMessage(), $this->minValue, $this->maxValue);
    }

    public function GetValidatorName()
    {
        return 'range';
    }

    protected function DoGetAdditionalAttributes()
    {
        return StringUtils::Format('range-min-value="%d" range-max-value="%d"', $this->minValue, $this->maxValue);
    }
}

class RangeLengthValidator extends Validator
{
    private $minValue;
    private $maxValue;

    public function __construct($minValue, $maxValue, $message = null)
    {
        parent::__construct($message);
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
    }

    public function GetMessage()
    {
        return StringUtils::Format(parent::GetMessage(), $this->minValue, $this->maxValue);
    }

    public function GetValidatorName()
    {
        return 'rangelength';
    }

    protected function DoGetAdditionalAttributes()
    {
        return StringUtils::Format('range-min-length="%d" range-max-length="%d"', $this->minValue, $this->maxValue);
    }
}

class MaxLengthValidator extends Validator
{
    private $maxValue;

    public function __construct($maxValue, $message = null)
    {
        parent::__construct($message);
        $this->maxValue = $maxValue;
    }

    public function GetMessage()
    {
        return StringUtils::Format(parent::GetMessage(), $this->maxValue);
    }

    public function GetValidatorName()
    {
        return 'max-length';
    }

    protected function DoGetAdditionalAttributes()
    {
        return StringUtils::Format('max-length="%d"', $this->maxValue);
    }
}

class MinLengthValidator extends Validator
{
    private $minValue;

    public function __construct($minValue, $message = null)
    {
        parent::__construct($message);
        $this->minValue = $minValue;
    }

    public function GetMessage()
    {
        return StringUtils::Format(parent::GetMessage(), $this->minValue);
    }

    public function GetValidatorName()
    {
        return 'min-length';
    }

    protected function DoGetAdditionalAttributes()
    {
        return StringUtils::Format('min-length="%d"', $this->minValue);
    }
}

class CreditCardNumberValidator extends Validator
{
    public function GetValidatorName()
    {
        return 'creditcard';
    }
}

class NumberValidator extends Validator
{
    public function GetValidatorName()
    {
        return 'number';
    }
}

class DigitsValidator extends Validator
{
    public function GetValidatorName()
    {
        return 'digits';
    }
}

class UrlValidator extends Validator
{
    public function GetValidatorName()
    {
        return 'url';
    }
}

class EMailValidator extends Validator
{
    public function GetValidatorName()
    {
        return 'email';
    }
}
class CustomRegExpValidator extends Validator
{
    private $regExp;

    public function __construct($regExp, $message = null)
    {
        parent::__construct($message);
        $this->regExp = $regExp;
    }

    protected function DoGetAdditionalAttributes()
    {
        return StringUtils::Format('regexp="%s"', $this->regExp);
    }

    public function GetValidatorName()
    {
        return 'regexp';
    }
}

class ValidatorCollection
{
    /**
     * @var Validator[]
     */
    private $list;

    public function __construct()
    {
        $this->list = array();
    }

    public function AddValidator(Validator $validator)
    {
        $this->list[] = $validator;
    }

    /**
     * @param bool $suppressRequiredValidation
     * @return string
     */
    public function GetInputAttributes($suppressRequiredValidation = false)
    {
        $result = '';
        $validationAttr = '';

        foreach($this->list as $validator)
        {
            if ($suppressRequiredValidation && (SMReflection::ClassName($validator) == 'RequiredValidator'))
                continue;

            StringUtils::AddStr($validationAttr, $validator->GetValidatorName(), ' ');
            StringUtils::AddStr($result, $validator->GetAdditionalAttributes(), ' ');
        }

        if (!StringUtils::IsNullOrEmpty($validationAttr))
            StringUtils::AddStr($result, StringUtils::Format('data-validation="%s"', $validationAttr), ' ');

        return $result;
    }
}
