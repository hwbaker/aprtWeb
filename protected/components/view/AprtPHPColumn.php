<?php
/**
 * CJMPHPColumn class file.
 *
 * @author Jayson Xu <superjavason@gmail.com>
 */

Yii::import('zii.widgets.grid.CGridColumn');

/**
 * CLinkColumn represents a grid view column that renders a hyperlink in each of its data cells.
 *
 * The {@link label} and {@link url} properties determine how each hyperlink will be rendered.
 * The {@link labelExpression}, {@link urlExpression} properties may be used instead if they are available.
 * In addition, if {@link imageUrl} is set, an image link will be rendered.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CLinkColumn.php 2799 2011-01-01 19:31:13Z qiang.xue $
 * @package zii.widgets.grid
 * @since 1.1
 */
class AprtPHPColumn extends CGridColumn
{
	public $content = '';

	public $type = '';
	/**
	 * @var array the HTML options for the data cell tags.
	 */
	public $htmlOptions = array('style' => 'width:50px');
	/**
	 * @var array the HTML options for the header cell tag.
	 */
	public $headerHtmlOptions = array('style' => 'width:50px');
	/**
	 * @var array the HTML options for the footer cell tag.
	 */
	public $footerHtmlOptions = array('style' => 'width:50px');

	/**
	 * Renders the data cell content.
	 * This method renders a hyperlink in the data cell.
	 * @param integer $row the row number (zero-based)
	 * @param mixed $data the data associated with the row
	 */
	protected function renderDataCellContent($row, $data)
	{
		if ($this->type == 'raw')
			echo $this->content;
		else {
			echo $this->evaluateExpression($this->content, array('data' => $data, 'row' => $row));
		}

	}
}
