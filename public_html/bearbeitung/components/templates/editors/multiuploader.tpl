<input
    type="file" multiple
    class="multi_upload"
    {include file="editors/editor_options.tpl" Editor=$Editor}
    data-min-file-size="{$Editor->getMinFileSize()}"
    data-max-file-size="{$Editor->getMaxFileSize()}"
    data-allowed-file-types='{$Editor->getAllowedFileTypesAsJson()}'
    data-allowed-file-extensions='{$Editor->getAllowedFileExtensionsAsJson()}'
>
