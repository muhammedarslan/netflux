<?php

StaticFunctions::ajax_form('private');
StaticFunctions::new_session();

$Me = StaticFunctions::get_id();
$Random = StaticFunctions::random(12);


echo StaticFunctions::JsonOutput([
    'closeLabel' => StaticFunctions::lang('25_close'),
    'HtmlContent' => ' <table id="table_' . $Random . '" style="width:100%" class="table data-list-view">
                <thead>
                     <tr>
                     <th class="dt-center">#</th>
                     <th class="dt-center noexport">' . StaticFunctions::lang('26_payment') . '</th>
                     <th class="dt-center noexport">' . StaticFunctions::lang('445_subscription') . '</th>
                     <th class="dt-center noexport">' . StaticFunctions::lang('27_subscription') . '</th>
                     <th class="dt-center ">' . StaticFunctions::lang('28_invoice') . '</th>
                     <th class="dt-center ">' . StaticFunctions::lang('29_subscription-period') . '</th>
                     <th class="dt-center ">' . StaticFunctions::lang('30_subscription-term') . '</th>
                    </tr>
                </thead>
                      <tbody>     
                      </tbody>
                    </table>',
    'TableID' => 'table_' . $Random
]);