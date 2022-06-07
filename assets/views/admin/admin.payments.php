<?php

$PageCss = [];
$PageJs = [
    '/assets/console/js/table.js'
];


require_once StaticFunctions::View('V' . '/admin.header.php');


?>

<div class="content">

    <div class="row">
        <div style="display: inline-block;" class="col-6">
            <h1><?= StaticFunctions::lang('226_payment') ?></h1>
        </div>
    </div>



    <br />
    <div class="table table-datatable">
        <table id="DataTableD" data-source="payments" class="data-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= StaticFunctions::lang('257_user') ?></th>
                    <th><?= StaticFunctions::lang('258_payment') ?></th>
                    <th><?= StaticFunctions::lang('259_user') ?></th>
                    <th><?= StaticFunctions::lang('260_payment') ?></th>
                    <th><?= StaticFunctions::lang('261_payment') ?></th>
                    <th><?= StaticFunctions::lang('262_status') ?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

</div>


<?php

require_once StaticFunctions::View('V' . '/admin.footer.php');
