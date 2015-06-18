<?php

namespace bupy7\grid;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class GridView extends \yii\grid\GridView
{
    const PANEL_DEFAULT = 'panel-default';
    const PANEL_INFO = 'panel-info';
    const PANEL_SUCCESS = 'panel-success';
    const PANEL_DANGER = 'panel-danger';
    const PANEL_WARNING= 'panel-warning';
    const PANEL_PRIMARY = 'panel-primary';
       
    /**
     * @var string|false
     */
    public $panel = self::PANEL_PRIMARY;
    /**
     * @var string HTML-template for layout of panel.
     * Allows uses:
     *      - `{type}` - the type of panel. See [[renderPanel]].
     *      - `{panelHeading}` - the heading of panel. See [[renderPanel]]. 
     *      - `{panelFooter}` - the footer of panel. See [[renderPanel]].
     *      - `{items}` - the summary section. See [[renderSummary()]].
     *      - `{summary}` - the summary section. See [[renderSummary()]].
     *      - `{pager}` - the pager. See [[renderPager()]].
     *      - `{pageSize}` - the page size. See [[renderPageSize]].
     *      - `{errors}` - the filter model error summary. See [[renderErrors()]].
     *      - `{sorter}` - the sorter. See [[renderSorter()]].
     * @see http://getbootstrap.com/components/#panels
     */
    public $panelTemplate = <<<HTML
<div class="panel {type}">
    {panelHeading}
    {items}
    {panelFooter}
</div>
HTML;
    /**
     * @var boolean Whether set 'true' will be displays heading of panel. 
     */
    public $panelHeading = true;
    /**
     * @var string HTML-template for heading of panel.
     * Allows uses:
     *      - `{items}` - the summary section. See [[renderSummary()]].
     *      - `{summary}` - the summary section. See [[renderSummary()]].
     *      - `{pager}` - the pager. See [[renderPager()]].
     *      - `{pageSize}` - the page size. See [[renderPageSize]].
     *      - `{errors}` - the filter model error summary. See [[renderErrors()]].
     *      - `{sorter}` - the sorter. See [[renderSorter()]].
     * @see http://getbootstrap.com/components/#panels-heading
     */
    public $panelHeadingTemplate = <<<HTML
    <div class="col-md-12 text-right">{pageSize}</div>
HTML;
    /**
     * @var boolean Whether set 'true' will be displays footer of panel.
     */
    public $panelFooter = true;
    /**
     * @var string HTML-template for footer of panel.
     * Allows uses:
     *      - `{items}` - the summary section. See [[renderSummary()]].
     *      - `{summary}` - the summary section. See [[renderSummary()]].
     *      - `{pager}` - the pager. See [[renderPager()]].
     *      - `{pageSize}` - the page size. See [[renderPageSize]].
     *      - `{errors}` - the filter model error summary. See [[renderErrors()]].
     *      - `{sorter}` - the sorter. See [[renderSorter()]].
     * @see http://getbootstrap.com/components/#panels-footer
     */
    public $panelFooterTemplate = <<<HTML
    <div class="row">
        <div class="col-md-6">{summary}</div>
    <div class="col-md-6 text-right">{pager}</div>
    </div>
HTML;
    /**
     * Tags to replace in the rendered layout. Enter this as `$key => $value` pairs, where:
     * - $key: string, defines the flag.
     * - $value: string|Closure, the value that will be replaced. You can set it as a callback
     *   function to return a string of the signature:
     *      function ($widget) { return 'custom'; }
     *
     * For example:
     * ['{flag}' => '<span class="glyphicon glyphicon-asterisk"></span']
     *
     * @var array
     */
    public $customTags = [];
    /**
     * @var array|string, configuration of additional header table rows that will be rendered before the default grid
     * header row. If set as a string, it will be displayed as is, without any HTML encoding. If set as an array, each
     * row in this array corresponds to a HTML table row, where you can configure the columns with these properties:
     * - columns: array, the header row columns configuration where you can set the following properties:
     *      - content: string, the table cell content for the column
     *      - tag: string, the tag for rendering the table cell. If not set, defaults to 'th'.
     *      - options: array, the HTML attributes for the table cell
     * - options: array, the HTML attributes for the table row
     */
    public $beforeHeader = [];
    /**
     * @var array|string, configuration of additional header table rows that will be rendered after default grid
     * header row. If set as a string, it will be displayed as is, without any HTML encoding. If set as an array, each
     * row in this array corresponds to a HTML table row, where you can configure the columns with these properties:
     * - columns: array, the header row columns configuration where you can set the following properties:
     *      - content: string, the table cell content for the column
     *      - tag: string, the tag for rendering the table cell. If not set, defaults to 'th'.
     *      - options: array, the HTML attributes for the table cell
     * - options: array, the HTML attributes for the table row
     */
    public $afterHeader = [];
    /**
     * @var array|string, configuration of additional footer table rows that will be rendered before the default grid
     * footer row. If set as a string, it will be displayed as is, without any HTML encoding. If set as an array, each
     * row in this array corresponds to a HTML table row, where you can configure the columns with these properties:
     * - columns: array, the footer row columns configuration where you can set the following properties:
     *      - content: string, the table cell content for the column
     *      - tag: string, the tag for rendering the table cell. If not set, defaults to 'th'.
     *      - options: array, the HTML attributes for the table cell
     * - options: array, the HTML attributes for the table row
     */
    public $beforeFooter = [];
    /**
     * @var array|string, configuration of additional footer table rows that will be rendered after the default grid
     * footer row. If set as a string, it will be displayed as is, without any HTML encoding. If set as an array, each
     * row in this array corresponds to a HTML table row, where you can configure the columns with these properties:
     * - columns: array, the footer row columns configuration where you can set the following properties:
     *      - content: string, the table cell content for the column
     *      - tag: string, the tag for rendering the table cell. If not set, defaults to 'th'.
     *      - options: array, the HTML attributes for the table cell
     * - options: array, the HTML attributes for the table row
     */
    public $afterFooter = [];
    /**
     * @var array the configuration for the page sizer widget. By default, `LinkPageSize` will be
     * used to render the page sizer. You can use a different widget class by configuring the "class" element.
     */
    public $pageSize = [];
    /**
     * @inheritdoc
     */
    public $tableOptions = ['class' => 'table table-striped table-hover table-bordered'];
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->initLayout();
        parent::run();
    }
    
    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{pageSize}':
                return $this->renderPageSize();
            default:
                return parent::renderSection($name);
        }
    }

    /**
     * Renders the page sizer.
     * @return string the rendering result
     */
    public function renderPageSize()
    {
        $pagination = $this->dataProvider->getPagination();
        if ($pagination === false || $this->dataProvider->getCount() <= 0) {
            return '';
        }
        /* @var $class LinkPageSizer */
        $pageSizer = $this->pageSize;
        $class = ArrayHelper::remove($pageSizer, 'class', LinkPageSize::className());
        $pageSizer['pagination'] = $pagination;
        $pageSizer['view'] = $this->getView();

        return $class::widget($pageSizer);
    }
    
    /**
     * Generation layout with panel if it uses.
     */
    public function renderPanel()
    {
        if ($this->panel === false) {
            return;
        }
        $this->layout = strtr(
            $this->panelTemplate,
            [
                '{panelHeading}' => $this->panelHeading !== false 
                    ? Html::tag('div', $this->panelHeadingTemplate, ['class' => 'panel-heading']) 
                    : '',
                '{type}' => $this->panel,
                '{panelFooter}' => $this->panelFooter !== false 
                    ? Html::tag('div', $this->panelFooterTemplate, ['class' => 'panel-footer']) 
                    : '',
            ]
        );
    }
    
    /**
     * Initialization layout of GridView.
     */
    public function initLayout()
    {
        $this->renderPanel();
        foreach ($this->customTags as $key => $value) {
            if ($value instanceof \Closure) {
                $value = call_user_func($value, $this);
            }
            $this->layout = str_replace($key, $value, $this->layout);
        }
    }
    
    /**
     * @inheritdoc
     */
    public function renderTableHeader()
    {
        $content = parent::renderTableHeader();
        return strtr(
            $content,
            [
                '<thead>' => "<thead>\n" . $this->generateRows($this->beforeHeader),
                '</thead>' => $this->generateRows($this->afterHeader) . "\n</thead>",
            ]
        );
    }
    
    /**
     * @inheritdoc
     */
    public function renderTableFooter()
    {
        $content = parent::renderTableFooter();
        return strtr(
            $content,
            [
                '<tfoot>' => "<tfoot>\n" . $this->generateRows($this->beforeFooter),
                '</tfoot>' => $this->generateRows($this->afterFooter) . "\n</tfoot>",
            ]
        );
    }
    
    /**
     * Generate HTML markup for additional table rows for header and/or footer
     * 
     * @param array|string $data the table rows configuration
     * @return string
     */
    protected function generateRows($data)
    {
        if (empty($data)) {
            return '';
        }
        if (is_string($data)) {
            return $data;
        }
        $rows = '';
        if (is_array($data)) {
            foreach ($data as $row) {
                if (empty($row['columns'])) {
                    continue;
                }
                $rowOptions = ArrayHelper::getValue($row, 'options', []);
                $rows .= Html::beginTag('tr', $rowOptions);
                foreach ($row['columns'] as $col) {
                    $colOptions = ArrayHelper::getValue($col, 'options', []);
                    $colContent = ArrayHelper::getValue($col, 'content', '');
                    $tag = ArrayHelper::getValue($col, 'tag', 'th');
                    $rows .= "\t" . Html::tag($tag, $colContent, $colOptions) . "\n";
                }
                $rows .= Html::endTag('tr') . "\n";
            }
        }
        return $rows;
    }
}
