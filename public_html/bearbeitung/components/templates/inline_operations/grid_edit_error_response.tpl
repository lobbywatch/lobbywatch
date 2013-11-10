<?xml version="1.0" encoding="utf-8" ?>
<errorinfo>
    <errormessage><![CDATA[{$ErrorMessage}]]></errormessage>
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
</errorinfo>

