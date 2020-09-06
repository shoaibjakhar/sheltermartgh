<?php

namespace Botble\Paymentmanagement\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Paymentmanagement\Repositories\Interfaces\PMInterface;

class PMRepository extends RepositoriesAbstract implements PMInterface
{

    /**
     * {@inheritDoc}
     */
    public function getDataSiteMap()
    {
        $data = $this->model
            ->with('slugable')
            ->select('vendor_payment_method.*')
            ->orderBy('vendor_payment_method.created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getPopularTags($limit)
    {
        $data = $this->model
            ->with('slugable')
            ->orderBy('vendor_payment_method.id', 'DESC')
            ->select('vendor_payment_method.*')
            ->limit($limit);

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getAllTags($active = true)
    {
        $data = $this->model->select('vendor_payment_method.*');
        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
