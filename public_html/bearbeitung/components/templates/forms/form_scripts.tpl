<script>
    {literal}
        function {/literal}{$Grid.FormId}{literal}_initd(editors) {
            {/literal}{$Grid.ClientOnLoadScript}{literal}
        }
        function {/literal}{$Grid.FormId}{literal}Validation(fieldValues, errorInfo) {
            {/literal}{$Grid.ClientValidationScript}{literal}; return true;
        }
        function {/literal}{$Grid.FormId}{literal}_EditorValuesChanged(sender, editors) {
            {/literal}{$Grid.ClientValueChangedScript}{literal}
        }
        function {/literal}{$Grid.FormId}{literal}_CalculateControlValues(editors) {
        {/literal}{$Grid.ClientCalculateControlValuesScript}{literal}
    }
    {/literal}
</script>