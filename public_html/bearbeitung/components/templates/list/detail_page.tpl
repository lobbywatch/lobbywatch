<div style="margin: 0px; font-size: 8pt; text-align: left;">

{if $DetailPage->GetFullRecordCount() < $DetailPage->GetRecordLimit()}
    {assign var="first_record_count" value=$DetailPage->GetFullRecordCount()}
{else}
    {assign var="first_record_count" value=$DetailPage->GetRecordLimit()}
{/if}

{assign var="total_record_count" value=$DetailPage->GetFullRecordCount()}

{assign var="shown_first_m_of_n_records" value=$Captions->GetMessageString('ShownFirstMofNRecords')}

{eval var=$shown_first_m_of_n_records}
{assign var="full_view_link" value=$DetailPage->GetFullViewLink()}
    ({eval var=$Captions->GetMessageString('FullView')})
</div>

{$Grid}