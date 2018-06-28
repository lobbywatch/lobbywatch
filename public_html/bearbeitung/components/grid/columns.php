<?php

include_once dirname(__FILE__) . '/../utils/system_utils.php';

function GetOrderTypeCaption($orderType)
{
    global $orderTypeCaptions;
    return $orderTypeCaptions[$orderType];
}

include(dirname(__FILE__) . '/columns/column_visibility.php');
include(dirname(__FILE__) . '/columns/abstract_wrapped_dataset_field_view_column.php');
include(dirname(__FILE__) . '/columns/text_view_column.php');
include(dirname(__FILE__) . '/columns/date_time_view_column.php');
include(dirname(__FILE__) . '/columns/checkbox_view_column.php');
include(dirname(__FILE__) . '/columns/number_view_column.php');
include(dirname(__FILE__) . '/columns/currency_view_column.php');
include(dirname(__FILE__) . '/columns/string_transform_view_column.php');
include(dirname(__FILE__) . '/columns/percent_view_column.php');
include(dirname(__FILE__) . '/columns/download_data_column.php');
include(dirname(__FILE__) . '/columns/download_external_data_column.php');
include(dirname(__FILE__) . '/columns/external_audio_view_column.php');
include(dirname(__FILE__) . '/columns/external_video_view_column.php');
include(dirname(__FILE__) . '/columns/external_image_view_column.php');
include(dirname(__FILE__) . '/columns/blob_image_view_column.php');
include(dirname(__FILE__) . '/columns/embedded_video_view_column.php');
include(dirname(__FILE__) . '/columns/detail_column.php');
