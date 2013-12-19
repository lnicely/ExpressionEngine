<?php
namespace EllisLab\ExpressionEngine\Model;

use EllisLab\ExpressionEngine\Core\Dependencies;
use EllisLab\ExpressionEngine\Model\Query\Query;

class ModelBuilder {

	private $di;
	private $model_namespace_aliases = array(
		'Template'       => '\EllisLab\ExpressionEngine\Model\Template\Template',
		'TemplateGroup'  => '\EllisLab\ExpressionEngine\Model\Template\TemplateGroup',
		'TemplateGateway' => '\EllisLab\ExpressionEngine\Model\Gateway\TemplateGateway',
		'TemplateGroupGateway' => '\EllisLab\ExpressionEngine\Model\Gateway\TemplateGroupGateway',
		'Channel' => '\EllisLab\ExpressionEngine\Module\Channel\Model\Channel',
		'ChannelEntry' => '\EllisLab\ExpressionEngine\Module\Channel\Model\ChannelEntry',
		'ChannelGateway' => '\EllisLab\ExpressionEngine\Module\Channel\Model\Gateway\ChannelGateway',
		'ChannelTitleGateway' => '\EllisLab\ExpressionEngine\Module\Channel\Model\Gateway\ChannelTitleGateway',
		'ChannelDataGateway' => '\EllisLab\ExpressionEngine\Module\Channel\Model\Gateway\ChannelDataGateway',
		'Member' => '\EllisLab\ExpressionEngine\Module\Member\Model\Member',
		'MemberGroup' => '\EllisLab\ExpressionEngine\Module\Member\Model\MemberGroup',
		'MemberGateway' => '\EllisLab\ExpressionEngine\Module\Member\Model\Gateway\MemberGateway',
		'MemberGroupGateway' => '\EllisLab\ExpressionEngine\Module\Member\Model\Gateway\MemberGroupGateway',
		'Category' => '\EllisLab\ExpressionEngine\Model\Category\Category',
		'CategoryFieldDataGateway' => '\EllisLab\ExpressionEngine\Model\Gateway\CategoryFieldDataGateway',
		'CategoryGateway' => '\EllisLab\ExpressionEngine\Model\Gateway\CategoryGateway',
		'CategoryGroup' => '\EllisLab\ExpressionEngine\Model\Category\CategoryGroup',
		'CategoryGroupGateway'=> '\EllisLab\ExpressionEngine\Model\Gateway\CategoryGroupGateway'
	);

	public function __construct(Dependencies $di)
	{
		$this->di = $di;
	}

	public function get($model_name, $ids = NULL)
	{
		$query = new Query($this, $model_name);

		if (isset($ids))
		{
			if (is_array($ids))
			{
				$query->filter($model_name, 'IN', $ids);
			}
			else
			{
				$query->filter($model_name, $ids);
			}
		}

		return $query;
	}

	public function make($model, array $data = array())
	{
		$class = $this->getRegisteredClass($model);

		if ( ! is_a($class, '\EllisLab\ExpressionEngine\Model\Model', TRUE))
		{
			throw new \InvalidArgumentException('Can only create Models.');
		}

		return new $class($this->di, $data);
	}

	/**
	 * Create a gateway instance
	 *
	 * @param String $alias  Name to use when interacting with the query builder
	 * @param String $fully_qualified_name  Fully qualified class name of the model to use
	 * @return void
	 */
	public function makeGateway($gateway, $data = array())
	{
		$class = $this->getRegisteredClass($model);

		if ( ! is_a($class, '\EllisLab\ExpressionEngine\Model\Gateway\RowDataGateway', TRUE))
		{
			throw new \InvalidArgumentException('Can only create Models.');
		}

		return new $class($this->di, $data);
	}

	/**
	 * Register a model under a given alias.
	 *
	 * @param String $alias  Name to use when interacting with the query builder
	 * @param String $fully_qualified_name  Fully qualified class name of the model to use
	 * @return void
	 */
	public function registerClass($class_name, $fully_qualified_name)
	{
		if (array_key_exists($alias, $this->model_namespace_aliases))
		{
			throw new \OverflowException('Model name has already been registered: '. $model);
		}

		$this->model_namespace_aliases[$alias] = $fully_qualified_name;
	}

	/**
	 * Get an alias's full qualified name.
	 *
	 * @param String $name Name of the model
	 * @return String Fully qualified name of the class
	 */
	public function getRegisteredClass($class_name)
	{
		return $this->model_namespace_aliases[$class_name];
	}
}
