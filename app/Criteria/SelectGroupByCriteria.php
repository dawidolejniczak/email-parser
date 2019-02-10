<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class SelectGroupByCriteria.
 *
 * @package namespace App\Criteria;
 */
class SelectGroupByCriteria implements CriteriaInterface
{
    /**
     * @var string
     */
    private $field;

    /**
     * SelectGroupByCriteria constructor.
     * @param string $field
     */
    public function __construct(string $field)
    {
        $this->field = $field;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->select($this->field)->groupBy($this->field);
    }
}
