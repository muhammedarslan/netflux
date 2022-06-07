<?php

class NetfluxDataTable
{
    private $Options;
    private $Headers;
    private $TableID;
    private $TableTitle;

    private $cssFiles = [
        '/assets/netflux/datatable/datatables.min.css',
        '/assets/netflux/datatable/responsive.dataTables.min.css'
    ];

    private $jsFiles = [
        '?load=options',
        '/assets/netflux/datatable/datatables.min.js',
        '/assets/netflux/datatable/datatables.buttons.min.js',
        '/assets/netflux/datatable/datatables.bootstrap4.min.js',
        '/assets/netflux/datatable/buttons.bootstrap.min.js',
        '/assets/netflux/datatable/dataTables.select.min.js',
        '/assets/netflux/datatable/dataTables.responsive.min.js',
        '/assets/netflux/datatable/table.js'
    ];

    public function GetContent($db)
    {
        header("Content-type: application/json; charset=utf-8");
        $TableID = $this->TableID;
        if (file_exists(CORE_DIR . '/tables/content.' . $TableID . '.php')) :
            require_once CORE_DIR . '/tables/content.' . $TableID . '.php';
            echo $DataJson;
        else :
            echo json_encode([
                'data' => []
            ]);
        endif;
        exit;
    }

    public function CheckAjax()
    {
        $Return = false;
        if (isset($_GET['load']) && StaticFunctions::clear($_GET['load']) == 'table' && StaticFunctions::AjaxCheck()) :
            StaticFunctions::ajax_form('private');
            $Return = true;
        endif;

        return $Return;
    }

    public function CheckOptions()
    {
        $Return = false;
        if (isset($_GET['load']) && StaticFunctions::clear($_GET['load']) == 'options' && StaticFunctions::AjaxCheck()) :
            StaticFunctions::ajax_form('private');
            $Return = true;
        endif;

        return $Return;
    }

    public function GetOptions()
    {
        $ColumDefTexts = '';
        $N = 0;
        $ArrayOrders = [];
        $ExportedColumsArray = [];
        foreach ($this->Headers as $key => $Header) {

            $FuncName = $this->RenderColumn($Header);
            $Classes = '';
            $Pr = 5;
            if ($Header['TextCenter']) $Classes = ' text-center ';
            if ($Header['HideMobile']) $Pr      = '555';
            if ($Header['AlwaysShow']) $Pr      = '1';
            if ($Header['Export']) array_push($ExportedColumsArray, $N);

            $SingleColumn = [
                'render' => "#!!function (data, type, row) {return " . $FuncName . "(data);}!!#",
                'orderable' => $Header['Orderable'],
                'className' => $Classes,
                'responsivePriority' => $Pr,
                'targets' => $N
            ];
            array_push($ArrayOrders, $SingleColumn);
            $N++;
        }

        $ExportedColums = json_encode($ExportedColumsArray);

        $ColumDefTexts = StaticFunctions::ApiJson($ArrayOrders);
        $ColumDefTexts = str_replace('"#!!', '', $ColumDefTexts);
        $ColumDefTexts = str_replace('!!#"', '', $ColumDefTexts);
        $ColumDefTexts = str_replace('\r\n', "\n", $ColumDefTexts);

        $JsContent = '
        var TableTitle = "' . $this->TableTitle . '";
        var SomeText = {
            ExportText:"' . StaticFunctions::lang('13_export') . '",
            Print:"' . StaticFunctions::lang('14_print') . '",
            Pdf:"' . StaticFunctions::lang('15_pdf') . '",
            Excel:"' . StaticFunctions::lang('16_excel') . '",
            Csv:"' . StaticFunctions::lang('17_csv') . '"
        };
        var Options = {
            Search : ' . StaticFunctions::BoolText($this->Options['Search']) . ',
            Export : ' . StaticFunctions::BoolText($this->Options['Export']) . ',
            PageLength : ' . $this->Options['PageLength']['Start'] . ',
            PageMenu : ' . $this->Options['PageLength']['Menu'] . ',
            Order : [' . $this->Options['Order']['Order'] . ', "' . $this->Options['Order']['Type'] . '"]
        };
        var ExportedColumns = ' . $ExportedColums . ';
        var ColumnDefsOptions = ' . $ColumDefTexts . ';
      ';

        return $JsContent;
    }

    public function PageContent($ID = null)
    {

        if ($ID == null) $ID = StaticFunctions::random(15);

        $Content = '<table id="' . $ID . '" style="width:100%" class="data-table display">
                <thead>
                     <tr>';

        foreach ($this->Headers as $key => $header) {
            $Class = '';
            if (!$header['Export']) $Class .= ' noexport ';
            if (!$header['TextCenter']) $Class .= ' dt-center ';
            $Content .= '<th class="' . $Class . '">' . stripslashes($header['Name']) . '</th>' . "\n";
        }

        $Content .= '</tr>
                </thead>
                      <tbody>     
                      </tbody>
                    </table>
                        ';

        return $Content;
    }

    public function setCss($Array)
    {
        return array_merge($Array, $this->cssFiles);
    }

    public function setJs($Array)
    {
        return array_merge($Array, $this->jsFiles);
    }

    public function setID($Id)
    {
        $this->TableID = $Id;
    }

    public function setTitle($Title)
    {
        $this->TableTitle = $Title;
    }

    public function setOptions($Options)
    {
        $this->Options = $Options;
    }

    public function setHeaders($Headers)
    {
        $this->Headers = $Headers;
    }

    public function RenderColumn($Header)
    {
        switch ($Header['Render']) {
            case 'normal':
                return $this->NormalContent();
                break;
            case 'bold':
                return $this->BoldContent();
                break;
            case 'bar':
                return $this->BarContent();
                break;
            case 'status':
                return $this->StatusContent();
                break;
            case 'actions_edit_delete':
                return $this->ActionEditDeleteContent();
                break;
            case 'actions_edit':
                return $this->ActionEditContent();
                break;
            case 'actions_delete':
                return $this->ActionDeleteContent();
                break;
            default:
                return $this->NormalContent();
                break;
        }
    }

    public function NormalContent()
    {
        return 'RenderNormal';
    }

    public function BoldContent()
    {
        return 'RenderBold';
    }

    public function BarContent()
    {
        return 'RenderBar';
    }

    public function StatusContent()
    {
        return 'RenderStatus';
    }

    public function ActionEditDeleteContent()
    {
        return 'RenderActionEditDelete';
    }

    public function ActionEditContent()
    {
        return 'RenderEdit';
    }

    public function ActionDeleteContent()
    {
        return 'RenderDelete';
    }
}