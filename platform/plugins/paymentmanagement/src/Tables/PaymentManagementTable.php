<?php

namespace Botble\Paymentmanagement\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Paymentmanagement\Models\PMMethod;
use Html;
use Illuminate\Support\Facades\Auth;
use Botble\Paymentmanagement\Repositories\Interfaces\PMInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class PaymentManagementTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * TagTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param PMInterface $tagRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, PMInterface $tagRepository)
    {
        $this->repository = $tagRepository;
        $this->setOption('id', 'table-paymentmethod');
        parent::__construct($table, $urlGenerator);

        if (Auth::user()->hasAnyPermission(['paymentmanagement.edit', 'paymentmanagement.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('user_id', function ($item) {
                

                return  $item->user->first_name.' '.$item->user->last_name;
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                if ($this->request()->input('action') === 'excel') {
                    return $item->status->getValue();
                }
                return $item->status->toHtml();
            });
        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            // ->addColumn('operations', function ($item) {
            //     return table_actions('paymentmanagement.edit', 'paymentmanagement.destroy', $item);
            // })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $query = $model
            ->select([
                'vendor_payment_method.id',
                'vendor_payment_method.first_name',
                'vendor_payment_method.last_name',
                'vendor_payment_method.user_id',
                'vendor_payment_method.account_holder',
                'vendor_payment_method.account_number',
                'vendor_payment_method.bank_name',
                'vendor_payment_method.bank_code',
                'vendor_payment_method.type',
                'vendor_payment_method.created_at',
                'vendor_payment_method.status',
            ]);  
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'vendor_payment_method.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'user_id'       => [
                'name'  => 'vendor_payment_method.user_id',
                'title' => _('Vendor'),
                'class' => 'text-left',
            ],
            'first_name'       => [
                'name'  => 'vendor_payment_method.first_name',
                'title' => _('First Name'),
                'class' => 'text-left',
            ],
            'last_name'       => [
                'name'  => 'vendor_payment_method.last_name',
                'title' => _('Last Name'),
                'class' => 'text-left',
            ],
            
            'account_holder'       => [
                'name'  => 'vendor_payment_method.account_holder',
                'title' => _('Account Holder'),
                'class' => 'text-left',
            ],
            'account_number'       => [
                'name'  => 'vendor_payment_method.account_number',
                'title' => _('Account Number'),
                'class' => 'text-left',
            ],
            'bank_name'       => [
                'name'  => 'vendor_payment_method.bank_name',
                'title' => _('Bank Name'),
                'class' => 'text-left',
            ],
            'bank_code'       => [
                'name'  => 'vendor_payment_method.bank_code',
                'title' => _('Bank Code'),
                'class' => 'text-left',
            ],
            'type'       => [
                'name'  => 'vendor_payment_method.type',
                'title' => _('Type'),
                'class' => 'text-left',
            ],
            'status'     => [
                'name'  => 'vendor_payment_method.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
            'created_at' => [
                'name'  => 'vendor_payment_method.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
        ];
    }

    // /**
    //  * {@inheritDoc}
    //  */
    // public function buttons()
    // {
    //     $buttons = $this->addCreateButton(route('tags.create'), 'tags.create');

    //     return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Tag::class);
    // }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('tags.deletes'), 'tags.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'vendor_payment_method.first_name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'vendor_payment_method.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'vendor_payment_method.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
