<?php

include_once dirname(__FILE__) . '/' . '../dataset/dataset.php';
include_once dirname(__FILE__) . '/' . 'string_utils.php';
include_once dirname(__FILE__) . '/' . 'system_utils.php';

class DatasetUtils
{
    public static function RowIterator(Dataset $dataSet)
    {

    }

    public static function SetDatasetFieldValue(Dataset $dataSet, $fieldName, $value)
    {
        if (!StringUtils::IsNullOrEmpty($fieldName))
            $dataSet->SetFieldValueByName($fieldName, $value);
    }

    public static function FormatDatasetFieldsTemplate(Dataset $dataset, $template, IDelegate $processValue = null)
    {
        $result = $template;
        foreach($dataset->GetFields() as $field)
        {
            if ($dataset->IsLookupField($field->GetNameInDataset()))
            {
                $result = StringUtils::ReplaceVariableInTemplate(
                    $result,
                    $field->GetAlias(),
                    $processValue == null ?
                        $dataset->GetFieldValueByName($field->GetAlias()) :
                        $processValue->Call($dataset->GetFieldValueByName($field->GetAlias()), $field->GetAlias())
                    );
            }
            else
            {
                $result = StringUtils::ReplaceVariableInTemplate(
                    $result,
                    $field->GetName(),
                    $processValue == null ?
                        $dataset->GetFieldValueByName($field->GetNameInDataset()) :
                        $processValue->Call($dataset->GetFieldValueByName($field->GetNameInDataset()), $field->GetNameInDataset())
                    );
            }
        }
        return $result;
    }
}

?>