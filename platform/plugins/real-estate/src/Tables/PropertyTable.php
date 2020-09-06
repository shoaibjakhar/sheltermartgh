<?php

namespace Botble\RealEstate\Tables;

use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\RealEstate\Models\Property;
use Botble\Vendor\Models\Vendor;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Throwable;
use Yajra\DataTables\DataTables;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
class PropertyTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;
    protected $fileRepository;
    protected $propertyType=null;

    /**
     * TagTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param PropertyInterface $propertyRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        PropertyInterface $propertyRepository,
        MediaFileInterface $fileRepository

    ) {
        $this->repository = $propertyRepository;
        $this->fileRepository=$fileRepository;
        $this->setOption('id', 'table-plugins-real-estate-property');
        parent::__construct($table, $urlGenerator);
    }

    /**
     * Display ajax response.
     *
     * @return JsonResponse
     * @since 2.1
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                return anchor_link(route('property.edit', $item->id), $item->name);
            })
            ->editColumn('image', function ($item) {
                return Html::image(get_object_image($item->image, 'thumb'), $item->name, ['width' => 50]);
            })
            ->editColumn('document', function ($item) {
                $documents=json_decode($item->document);
                $html='';
                if($documents){
                    foreach ( $documents as $key => $value) {
                    $files=$this->fileRepository->getWhere(['id'=>$value]);
                    $type=explode('/',$files->mime_type);
                    if($type[0]=='application')
                    {
                        $html.=anchor_link(route('media.download', 'file='.$files->id), $files->name.'.'.$type[1]).'<br>';
                    }
                    else
                    {
                        $html.='<a href="'.route('media.download', 'file='.$files->id).'" >'.Html::image(get_object_image($files->url, 'thumb'), $files->name, ['width' => 50]).'</a>';
                    }    
                    }
                    return $html;
                }
                return Html::image(get_object_image('', 'thumb'), 'avatar', ['width' => 50]);
            })
            ->editColumn('confirm_documnet', function ($item) {
                $documents=json_decode($item->confirm_documnet);
                $html='';
                if($documents){
                    foreach ( $documents as $key => $value) {
                    $files=$this->fileRepository->getWhere(['id'=>$value]);
                    $type=explode('/',$files->mime_type);
                    if($type[0]=='application')
                    {
                        $html.=anchor_link(route('media.download', 'file='.$files->id), $files->name.'.'.$type[1]).'<br>';
                    }
                    else
                    {
                        $html.='<a href="'.route('media.download', 'file='.$files->id).'" >'.Html::image(get_object_image($files->url, 'thumb'), $files->name, ['width' => 50]).'</a>';
                    }    
                    }
                    return $html;
                }
                return Html::image(get_object_image('', 'thumb'), 'avatar', ['width' => 50]);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->editColumn('moderation_status', function ($item) {
                return $item->moderation_status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return table_actions('property.edit', 'property.destroy', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Get the query object to be processed by table.
     *
     * @return \Illuminate\Database\Query\Builder|Builder
     * @since 2.1
     */
    public function setPrivateType($propertyType)
    {
        $this->propertyType=$propertyType;
    }
    public function query()
    {
        $model = $this->repository->getModel();
        $query = $model->select([
            're_properties.id',
            're_properties.name',
            're_properties.images',
            're_properties.document',
            're_properties.confirm_documnet',
            're_properties.status',
            're_properties.moderation_status',
        ]);
        if($this->propertyType)
        {
            $query->join('vendors',function($join){
                $join->on('vendors.id','=','re_properties.author_id');
            });
            $query->where('vendors.vendor_type','landlord');
        }  
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model));
    }

    /**
     * @return array
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id'                => [
                'name'  => 're_properties.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'image'             => [
                'name'       => 're_properties.image',
                'title'      => trans('core/base::tables.image'),
                'width'      => '60px',
                'class'      => 'no-sort',
                'orderable'  => false,
                'searchable' => false,
            ],
            'name'              => [
                'name'  => 're_properties.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'document'              => [
                'name'  => 're_properties.document',
                'title' => _('document'),
                'width'      => '100px',
                'class'      => 'no-sort',
                'orderable'  => false,
                'searchable' => false,
            ],
            'confirm_documnet'              => [
                'name'  => 're_properties.confirm_documnet',
                'title' => _('Confirm Documnet'),
                'width'      => '100px',
                'class'      => 'no-sort',
                'orderable'  => false,
                'searchable' => false,
            ],
            
            'status'            => [
                'name'  => 're_properties.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
            'moderation_status' => [
                'name'  => 're_properties.moderation_status',
                'title' => trans('plugins/real-estate::property.moderation_status'),
                'width' => '150px',
            ],
        ];
    }

    /**
     * @return array
     *
     * @throws Throwable
     * @since 2.1
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('property.create'), 'property.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Property::class);
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('property.deletes'), 'property.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            're_properties.name'              => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_properties.status'            => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => PropertyStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(PropertyStatusEnum::values()),
            ],
            're_properties.moderation_status' => [
                'title'    => trans('plugins/real-estate::property.moderation_status'),
                'type'     => 'select',
                'choices'  => ModerationStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', ModerationStatusEnum::values()),
            ],
            're_properties.created_at'        => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
