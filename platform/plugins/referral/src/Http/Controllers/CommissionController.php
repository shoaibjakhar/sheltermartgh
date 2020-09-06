<?php

namespace Botble\Referral\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Referral\Forms\CommissionForm;
use Botble\Referral\Tables\CommissionTable;
use Botble\Referral\Http\Requests\CommissionRequest;
use Botble\Referral\Repositories\Interfaces\CommissionInterface;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Illuminate\View\View;
use Botble\RealEstate\Models\Property;
use Botble\Referral\Models\Setting;
use Throwable;

class CommissionController extends BaseController
{

    /**
     * @var CommissionInterface
     */
    protected $commissionRepository;

    /**
     * @param CommissionInterface $commissionRepository
     */
    public function __construct(CommissionInterface $commissionRepository)
    {
        $this->commissionRepository = $commissionRepository;
    }

    /**
     * @param CommissionTable $dataTable
     * @return Factory|View
     *
     * @throws Throwable
     */
    public function index(CommissionTable $dataTable,Request $resquest)
    {
        $type=$resquest->input('type');
         page_title()->setTitle(_(($type=='pend')?'Pending':'Paid'.' Commissions List'));
        $dataTable->setPrivateType($type);
        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(_('Commission Calculater'));

        return $formBuilder->create(CommissionForm::class)->renderForm();
    }

    /**
     * @param CommissionRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(CommissionRequest $request, BaseHttpResponse $response)
    {

        $setting=Setting::where('id',$request->input('commission_id'))->first();
        $property=Property::find($request->input('property'));
       
        if($setting)
        {
            $data=[
                'property_id'=>$request->input('property'),
                'admin_commission'=>(float) $request->input('admin_commission'),
                'admin_comm_percentage'=>$setting->admin_comm,
                'client_commission'=> (float) $request->input('client_commission'),
                'client_comm_percentage'=>$setting->client_comm,
                'vendor_commission'=> (float) $request->input('vendor_commission'),
                'vendor_comm_percentage'=>$setting->vendor_comm,
                'property_price'=> (float) $request->input('price'),
                'commission'=> (float) $request->input('commission'),
                'percentage'=>$setting->comession,
                'reference_id'=>$property->author->id,
                'type'=>$request->input('property_type'),
            ]; 
            $commission = $this->commissionRepository->createOrUpdate(array_merge($data, [
                'author_id' => Auth::user()->getKey(),
            ]));
        }
        event(new CreatedContentEvent(COMMISSION_MODULE_SCREEN_NAME, $request, $commission));

        return $response
            ->setPreviousUrl(route('commission.index','type=pend'))
            ->setNextUrl(route('commission.index','type=pend'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit(Request $request, $id, FormBuilder $formBuilder)
    {
        $commission = $this->commissionRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $commission));

        page_title()->setTitle(_('Commission Edit') . ' "' . $commission->property->name . '"');

        return $formBuilder->create(CommissionForm::class, ['model' => $commission])->renderForm();
    }

    /**
     * @param int $id
     * @param CommissionRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, CommissionRequest $request, BaseHttpResponse $response)
    {
        $commission = $this->commissionRepository->findOrFail($id);

        if ($request->input('is_default')) {
            $this->commissionRepository->getModel()->where('id', '!=', $id)->update(['is_default' => 0]);
        }

        $commission->fill($request->input());

        $this->commissionRepository->createOrUpdate($commission);

        event(new UpdatedContentEvent(COMMISSION_MODULE_SCREEN_NAME, $request, $commission));

        return $response
            ->setPreviousUrl(route('categories.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
    public function setPaid(Request $request,BaseHttpResponse $response)
    {
        $id=$request->get('id');
        $commission = $this->commissionRepository->getModel()->where('id', $id)->update(['status' => 'clear']);
        return $response
            ->setPreviousUrl(route('commission.index','type=pend'))
            ->setNextUrl(route('commission.index','type=pend'))
            ->setMessage(_('Commission successfuly paid'));
        
    }
    /**
     * @param Request $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $commission = $this->commissionRepository->findOrFail($id);

            if (!$commission->is_default) {
                $this->commissionRepository->delete($commission);
                event(new DeletedContentEvent(COMMISSION_MODULE_SCREEN_NAME, $request, $commission));
            }

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
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
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $commission = $this->commissionRepository->findOrFail($id);
            if (!$commission->is_default) {
                $this->commissionRepository->delete($commission);

                event(new DeletedContentEvent(COMMISSION_MODULE_SCREEN_NAME, $request, $commission));
            }
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function calCommission(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('propertyId');
        $property=Property::find($ids);
        $data=[];
        if($property){
            if($request->input('propertyType'))
            $setting=Setting::where('property_type',$request->input('propertyType'))->first();
            else    
            $setting=Setting::where('property_type',$property->type)->first();
            if($request->input('propertyPrice')){
                $price=$request->input('propertyPrice');
                $data['property_price']=$price;
                $commission=round($price/100*$setting->comession,3);
                $data['property_price']=$price;
                $data['property_commission']=$commission;
                $data['commission_id']=$setting->id;
                $data['admin_commission']=round($commission/100*$setting->admin_comm,3);
                $commission=$commission-round($commission/100*$setting->admin_comm,3);
                $data['client_commission']=round($commission/100*$setting->client_comm,3);
                $data['vendor_commission']=round($commission/100*$setting->vendor_comm,3);
                $data['property_type']=$property->type;    
            }else{
                $data['property_price']=$property->price;
                $data['commission_id']=$setting->id;
                $commission=round($property->price/100*$setting->comession,3);
                $data['property_price']=$property->price;
                $data['property_commission']=$commission;
                $data['admin_commission']=round($commission/100*$setting->admin_comm,3);
                $commission=$commission-round($commission/100*$setting->admin_comm,3);
                $data['client_commission']=round($commission/100*$setting->client_comm,3);
                $data['vendor_commission']=round($commission/100*$setting->vendor_comm,3);
                $data['property_type']=$property->type;
            }
        }
        return json_encode($data);
    }
}
