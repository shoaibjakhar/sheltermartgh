<?php

namespace Botble\Referral\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\Referral\Forms\SettingForm;
use Botble\Referral\Http\Requests\SettingRequest;
use Botble\Referral\Models\Setting;
use Botble\Referral\Repositories\Interfaces\CategoryInterface;
use Botble\Referral\Repositories\Interfaces\SettingInterface;
use Botble\Referral\Tables\SettingTable;
use Botble\Referral\Tables\CommissionTable;
use Botble\Referral\Repositories\Interfaces\TagInterface;
use Botble\Referral\Services\StoreCategoryService;
use Botble\Referral\Services\StoreTagService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Botble\Base\Events\CreatedContentEvent;
// use Botble\Base\Events\DeletedContentEvent;
// use Botble\Base\Events\UpdatedContentEvent;
use Illuminate\View\View;
use Throwable;

class ReferralController extends BaseController
{

    use HasDeleteManyItemsTrait;

    /**
     * @var SettingInterface
     */
    protected $SettingRepository;

    /**
     * @param SettingInterface $SettingRepository
     */
    public function __construct(
        SettingInterface $SettingRepository
    ) {
        
        $this->SettingRepository = $SettingRepository;
    }

    /**
     * @param SettingTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(SettingTable $dataTable)
    {
        page_title()->setTitle(_('Referral Commission List'));
        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder,BaseHttpResponse $response ,Request $request)
    {
        $type=$request->input('type');
        $post = $this->SettingRepository->getWhereRow(['property_type'=>$type]);
        if($post)
        {
            return $response
            ->setPreviousUrl(route('referral.create'))
            ->setNextUrl(route('referral.edit', $post->id));
        }    
        
        page_title()->setTitle(_('Referral '.ucwords($type).' Setting'));
        return $formBuilder->create(SettingForm::class,[],['type'=>$type])->renderForm();
    }

    /**
     * @param SettingRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(
        SettingRequest $request,
        BaseHttpResponse $response
    ) {
        /**
         * @var Setting $setting
         */
        $dataPost=  [
                        'comession'=>$request->input('comession'),
                        'vendor_comm'=>$request->input('vendor_commission'),
                        'client_comm'=>$request->input('Userclient_commission'),
                        'admin_comm'=>$request->input('admin_commission'),
                        'property_type'=>$request->input('property_type'),
                    ];    
        $post = $this->SettingRepository->createOrUpdate(array_merge($dataPost, [
            'author_id' => Auth::user()->getKey(),
        ]));


        return $response
            ->setPreviousUrl(route('referral.index'))
            ->setNextUrl(route('referral.edit', $post->id))
            ->setMessage(_('Referral Commission add successfuly!'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $post = $this->SettingRepository->getWhereRow(['id'=>$id]);
        page_title()->setTitle(_('Edit Referral Commission '.ucwords($post->property_type).' Setting
'));

        return $formBuilder->create(SettingForm::class, ['model' => $post])->renderForm();
    }

    /**
     * @param int $id
     * @param SettingRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update(
        $id,
        SettingRequest $request,
        BaseHttpResponse $response
    ) {
        $dataPost=  [
                        'comession'=>$request->input('comession'),
                        'vendor_comm'=>$request->input('vendor_commission'),
                        'client_comm'=>$request->input('Userclient_commission'),
                        'admin_comm'=>$request->input('admin_commission'),
                        'property_type'=>$request->input('property_type'),
                    ];  
        $this->SettingRepository->Update(['id'=>$id],$dataPost);
        return $response
            ->setPreviousUrl(route('referral.edit',$id))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @return BaseHttpResponse
     */
    public function destroy($id, Request $request, BaseHttpResponse $response)
    {
        try {
            $post = $this->SettingRepository->findOrFail($id);
            $this->SettingRepository->delete($post);

//            event(new DeletedContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

            return $response
                ->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->SettingRepository, POST_MODULE_SCREEN_NAME);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function getWidgetRecentPosts(Request $request, BaseHttpResponse $response)
    {
        $limit = $request->input('paginate', 10);
        $posts = $this->SettingRepository->getModel()
            ->orderBy('posts.created_at', 'desc')
            ->paginate($limit);

        return $response
            ->setData(view('plugins/blog::posts.widgets.posts', compact('posts', 'limit'))->render());
    }
}
