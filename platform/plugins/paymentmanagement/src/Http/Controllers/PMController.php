<?php

namespace Botble\Paymentmanagement\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\Paymentmanagement\Forms\PaymentManagementForm;
use Botble\Paymentmanagement\Http\Requests\PaymentManagementRequest;
use Botble\Vendor\Models\PaymentMethod;
use Botble\Paymentmanagement\Models\DirectPaymentMethod;
use Botble\Paymentmanagement\Repositories\Interfaces\PMInterface;
use Botble\Paymentmanagement\Tables\DPMTable;
use Botble\Paymentmanagement\Tables\PaymentManagementTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Illuminate\View\View;
use Throwable;

class PMController extends BaseController
{

    use HasDeleteManyItemsTrait;

    /**
     * @var PostInterface
     */
    protected $paymentManagementRepository;

    
    /**
     * @param PostInterface $paymentManagementRepository
     * @param TagInterface $tagRepository
     * @param CategoryInterface $categoryRepository
     */
    public function __construct(
        PMInterface $paymentManagementRepository
       
    ) {
        $this->paymentManagementRepository = $paymentManagementRepository;
    }

    /**
     * @param PostTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(PaymentManagementTable $dataTable)
    {
        page_title()->setTitle(_('User Payment List'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(DPMTable $dataTable)
    {
        page_title()->setTitle(_('Payment Method List'));

        return $dataTable->renderTable();
    }

    /**
     * @param PostRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(
        PostRequest $request,
        StoreTagService $tagService,
        StoreCategoryService $categoryService,
        BaseHttpResponse $response
    ) {
        /**
         * @var Post $post
         */
        $post = $this->paymentManagementRepository->createOrUpdate(array_merge($request->input(), [
            'author_id' => Auth::user()->getKey(),
        ]));

        event(new CreatedContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        return $response
            ->setPreviousUrl(route('posts.index'))
            ->setNextUrl(route('posts.edit', $post->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $post = $this->paymentManagementRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $post));

        page_title()->setTitle(trans('plugins/blog::posts.edit') . ' "' . $post->name . '"');

        return $formBuilder->create(PostForm::class, ['model' => $post])->renderForm();
    }

    /**
     * @param int $id
     * @param PostRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update(
        $id,
        PostRequest $request,
        StoreTagService $tagService,
        StoreCategoryService $categoryService,
        BaseHttpResponse $response
    ) {
        $post = $this->paymentManagementRepository->findOrFail($id);

        $post->fill($request->input());

        $this->paymentManagementRepository->createOrUpdate($post);

        event(new UpdatedContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        return $response
            ->setPreviousUrl(route('posts.index'))
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
            $post = $this->paymentManagementRepository->findOrFail($id);
            $this->paymentManagementRepository->delete($post);

            event(new DeletedContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

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
        return $this->executeDeleteItems($request, $response, $this->paymentManagementRepository, POST_MODULE_SCREEN_NAME);
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
        $posts = $this->paymentManagementRepository->getModel()
            ->orderBy('posts.created_at', 'desc')
            ->paginate($limit);

        return $response
            ->setData(view('plugins/blog::posts.widgets.posts', compact('posts', 'limit'))->render());
    }
}
