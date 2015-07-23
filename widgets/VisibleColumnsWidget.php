<?php

namespace bupy7\grid\widgets;

use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use bupy7\grid\components\GridSettings;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/**
 * Generation of modal window with list of available columns for selecting need to visible.
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.0.0
 */
class VisibleColumnsWidget extends Widget
{
    /**
     * @var mixed Uniqal ID of grid. You can uses not only string, but also other types of variable.
     * Example:
     * ~~~
     * 'main-grid'
     * ~~~
     */
    public $gridId;
    /**
     * @var array|string|GridSettings the grid settings used for set/get actual visible columns of $gridId.
     */
    public $gridSettings = 'gridSettings';
    /**
     * @var array Modal window widget options.
     * @see \yii\bootstrap\Modal
     */
    public $modalOptions = [];
    /**
     * @var string|array Action URL of form.
     * @see Html::beginForm()
     */
    public $actionForm = '';
    /**
     * @var string Method of form.
     * @see Html::beginForm()
     */
    public $methodForm = 'post';
    /**
     * @var array Options of form.
     * @see Html::beginForm()
     */
    public $formOptions = [];
    /**
     * @var array List of available columns in grid.
     */
    public $columnsList = [];
    /**
     * @var string Label of submit button.
     * @see Html::submitButton()
     */
    public $submitBtnLabel = 'Apply';
    /**
     * @var array Options of submit button.
     * @see Html::submitButton()
     */
    public $submitBtnOptions = ['class' => 'btn btn-primary'];
    
    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty($this->gridId) || empty($this->gridSettings)) {
            throw new InvalidConfigException('Property "gridId" and "gridSettings" must be specified.');
        }
        $this->gridSettings = Instance::ensure($this->gridSettings, GridSettings::className());
    }
    
    /**
     * Display modal window with form for selecting visible columns.
     */
    public function run()
    {
        $visibleColumns = $this->gridSettings->getVisibleColumns($this->gridId);
        if ($visibleColumns === false) {
            $visibleColumns = array_keys($this->columnsList);
        }       
        Modal::begin($this->modalOptions);
        echo Html::beginForm($this->actionForm, $this->methodForm, $this->formOptions);
        echo Html::checkboxList('columns', $visibleColumns, $this->columnsList);
        echo Html::beginTag('div', ['class' => 'form-group']);
        echo Html::submitButton($this->submitBtnLabel, $this->submitBtnOptions);
        echo Html::endTag('div');
        echo Html::endForm();
        Modal::end();
    }
}

