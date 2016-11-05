<?php

class CompareGridState extends GridState
{
    public function ProcessMessages()
    {
        $dataset = $this->grid->getDataset();
        $recordsKeys = ArrayWrapper::createGetWrapper()->getValue('keys', array());

        $primaryFields = array();
        foreach ($dataset->GetPrimaryKeyFieldNames() as $fieldName) {
            $primaryFields[] = $dataset->GetFieldInfoByName($fieldName);
        }

        $resultFilter = new CompositeFilter('OR');
        foreach ($recordsKeys as $primaryKeysValues) {
            $recordFilter = new CompositeFilter('AND');

            foreach ($primaryKeysValues as $i => $value) {
                $recordFilter->addFilter($primaryFields[$i], FieldFilter::Equals($value));
            }

            $resultFilter->addFilter(null, $recordFilter);
        }

        $dataset->getSelectCommand()->AddCompositeFilter($resultFilter);

    }
}
