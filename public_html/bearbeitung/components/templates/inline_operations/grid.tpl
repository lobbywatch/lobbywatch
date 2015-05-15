<?xml version="1.0" {if $encoding}encoding="{$encoding}"{/if}?>
<editors>
<namesuffix>{$EditorsNameSuffix}</namesuffix>
{foreach from=$ColumnEditors key=name item=editor name=Editors}
    <editor name="{$name}">
        <html>
            <![CDATA[
                {$editor.Html}
            ]]>
        </html>
        <script>
            <![CDATA[
                {$editor.Script}
            ]]>
        </script>
    </editor>
{/foreach}
</editors>