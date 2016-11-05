<?php

class InsertGridState extends GridState {
    public function ProcessMessages()
    {
        foreach ($this->grid->GetInsertColumns() as $column) {
            $column->ProcessMessages();
        }
    }
}
