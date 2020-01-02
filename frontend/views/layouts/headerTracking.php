<?php
use common\models\SiteParam;

foreach (SiteParam::findAllByNames([SiteParam::HEADER_TRACKING_CODE]) as $headerTrackingCode) {
    echo $headerTrackingCode->value;
}