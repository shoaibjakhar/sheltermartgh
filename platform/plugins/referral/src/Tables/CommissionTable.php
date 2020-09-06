<?php

namespace Botble\Referral\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Referral\Models\Commission;
use Html;
use Illuminate\Support\Facades\Auth;
use Botble\Referral\Repositories\Interfaces\CommissionInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class CommissionTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $useDefaultSorting = false;

    /**
     * @var bool
     */
    protected $hasFilter = true;
    protected $typeStatus ='clear';

    /**
     * CommissionTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param CommissionInterface $commissionRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, CommissionInterface $commissionRepository)
    {
        $this->repository = $commissionRepository;
        $this->setOption('id', 'table-commission');
        parent::__construct($table, $urlGenerator);

        // if (!Auth::user()->hasAnyPermission(['commission.edit', 'commission.destroy'])) {
        //     $this->hasOperations = false;
        //     $this->hasActions = false;
        // }
    }
    public function setPrivateType($type)
    {
        if($type=='pend')
        $this->typeStatus='pending';
        if($type=='paid')   
        $this->typeStatus='clear'; 
    }
    /**
     * {@inheritDoc}
     */
    public function ajax()
    {


        $data = $this->table
            ->of($this->query())
            ->editColumn('property_id', function ($item) {
                return $item->property->name;
            })
            ->editColumn('type', function ($item) {
                return ($item->type=='sale')?'Sale':'Rent';
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
            })
            ->removeColumn('is_default');

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return view('plugins/referral::commission.actions', compact('item'))->render();
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $statusType=$this->typeStatus;
        return collect(get_commession([],$this->repository,['status'=>$statusType]));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'property_id'       => [
                'name'  => 'property',
                'title' => _('Property'),
                'class' => 'text-left',
            ],
            'property_price'       => [
                'name'  => 'property_price',
                'title' => _('Price'),
                'class' => 'text-left',
            ],
            'commission'       => [
                'name'  => 'commission',
                'title' => _('Commission'),
                'class' => 'text-left',
            ],
            'admin_commission'       => [
                'name'  => 'admin_commission',
                'title' => _('Admin Commission'),
                'class' => 'text-left',
            ],
            'client_commission'       => [
                'name'  => 'client_commission',
                'title' => _('Client Commission'),
                'class' => 'text-left',
            ],
            'vendor_commission'       => [
                'name'  => 'vendor_commission',
                'title' => _('Vendor Commission'),
                'class' => 'text-left',
            ],
            'type'       => [
                'name'  => 'type',
                'title' => _('Property Type'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('categories.create'), 'categories.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Commission::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('categories.deletes'), 'categories.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'categories.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'categories.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'categories.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
