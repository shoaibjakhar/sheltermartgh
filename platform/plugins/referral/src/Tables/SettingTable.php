<?php

namespace Botble\Referral\Tables;

use Illuminate\Support\Facades\Auth;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Referral\Exports\PostExport;
use Botble\Referral\Models\Setting;
use Botble\Referral\Repositories\Interfaces\CategoryInterface;
use Botble\Referral\Repositories\Interfaces\SettingInterface;
use Botble\Table\Abstracts\TableAbstract;
use Carbon\Carbon;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class SettingTable extends TableAbstract
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
     * @var string
     */
    protected $exportClass = PostExport::class;

    /**
     * @var int
     */
    protected $defaultSortColumn = 6;

    /**
     * PostTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param SettingInterface $settingRepository
     * @param CategoryInterface $categoryRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        SettingInterface $settingRepository
    ) {
        $this->repository = $settingRepository;
        $this->setOption('id', 'table-posts');
        parent::__construct($table, $urlGenerator);

        if (Auth::check() && !Auth::user()->hasAnyPermission(['referral.edit', 'referral.destroy'])) {
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
            ->editColumn('admin_comm', function ($item) {
                if (Auth::check() && !Auth::user()->hasPermission('referral.edit')) {
                    return $item->admin_comm;
                }

                return $item->admin_comm;
            })
            ->editColumn('client_comm', function ($item) {
                return $item->client_comm;
            })
            ->editColumn('vendor_comm', function ($item) {
                return $item->vendor_comm;
            })
            ->editColumn('property_type', function ($item) {
                return $item->property_type;
            })
            
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('updated_at', function ($item) {
                return date_from_database($item->updated_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('author_id', function ($item) {
                return $item->author ? $item->author->getFullName() : null;
            })
            ->editColumn('status', function ($item) {
                if ($this->request()->input('action') === 'excel') {
                    return $item->status->getValue();
                }
                return $item->status->toHtml();
            });
        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return table_actions('posts.edit', 'posts.destroy', $item);
            })
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
                'referral_commission.id',
                'referral_commission.admin_comm',
                'referral_commission.client_comm',
                'referral_commission.vendor_comm',
                'referral_commission.property_type',
                'referral_commission.status',
                'referral_commission.updated_at',
                'referral_commission.author_id',
                'referral_commission.author_type',
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
                'name'  => 'referral_commission.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'admin_comm'      => [
                'name'  => 'referral_commission.admin_comm',
                'title' => _('Admin Commession'),
                'class' => 'text-left',

            ],
            'client_comm'       => [
                'name'  => 'referral_commission.client_comm',
                'title' => _('Client Commession'),
                'class' => 'text-left',
            ],
            'vendor_comm' => [
                'name'      => 'referral_commission.vendor_comm',
                'title'     => _('Vendor Commession'),
                'class' => 'text-left',
            ],
            'property_type'  => [
                'name'      => 'referral_commission.property_type',
                'title'     => _('Property Type'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'referral_commission.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'referral_commission.status',
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
        $buttons = $this->addCreateButton(route('referral.create'), 'referral.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Post::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('posts.deletes'), 'posts.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'posts.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'posts.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'category'         => [
                'title'    => trans('plugins/blog::posts.category'),
                'type'     => 'select-search',
                'validate' => 'required',
                'callback' => 'getCategories',
            ],
            'posts.created_at' => [
                'title'    => trans('core/base::tables.created_at'),
                'type'     => 'date',
                'validate' => 'required',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function applyFilterCondition($query, string $key, string $operator, ?string $value)
    {
        switch ($key) {
            case '.created_at':
                $value = Carbon::createFromFormat('Y/m/d', $value)->toDateString();
                return $query->whereDate($key, $operator, $value);
        }

        return parent::applyFilterCondition($query, $key, $operator, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function saveBulkChangeItem($item, string $inputKey, ?string $inputValue)
    {
        if ($inputKey === 'category') {
            $item->categories()->sync([$inputValue]);
            return $item;
        }

        return parent::saveBulkChangeItem($item, $inputKey, $inputValue);
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultButtons(): array
    {
        return ['excel', 'reload'];
    }
}
