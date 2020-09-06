<?php

namespace Botble\Referral\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Referral\Http\Requests\CommissionRequest;
use Botble\Referral\Models\Commission;
use Botble\RealEstate\Models\Property;
use Assets;

class CommissionForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        Assets::addScriptsDirectly('vendor/core/plugins/referral/js/commission.js');
        $Property=Property::get();
        $arrayProperty=[];
        $arrayProperty['']=['select property'];
        foreach ($Property as $key => $value) {
         $arrayProperty[$value->id]=$value->name;  
        }
        $this
            ->setupModel(new Commission)
            ->setValidatorClass(CommissionRequest::class)
            ->withCustomFields()
            ->add('commission_id', 'hidden')
            ->add('property', 'customSelect', [
                'label'      => _('Property'),
                'label_attr' => ['class' => 'control-label required'],
                 'attr'       => [
                    'class' => 'form-control select-full property-class',
                    'data-url'=>route('commission.calculate'),
                ],
                'choices'    =>$arrayProperty,
            ])
            ->add('property_type', 'customSelect', [
                'label'      => _('Property Type'),
                'label_attr' => ['class' => 'control-label required'],
                 'attr'       => [
                    'class' => 'form-control select-full property-type',
                    'data-url'=>route('commission.calculate'),
                ],
                'choices'    => [''=>'select','rent'=>'Rent','sale'=>'Sale'],
            ])
            ->add('price', 'text', [
                'label'      =>_('Price'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => _('Property price... '),
                    'class' => 'form-control property-price',
                ],

            ])
            ->add('commission', 'text', [
                'label'      =>_('Commission'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => _('Property commission... '),
                    'class' => 'form-control property-commission',
                ],

            ])
            ->add('admin_commission', 'text', [
                'label'      =>_('Admin Commission'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => _('Admin commission... '),
                    'class' => 'form-control prop-admin-commission',
                ],

            ])
            ->add('client_commission', 'text', [
                'label'      =>_('Client Commission'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => _('Client commission... '),
                    'class' => 'form-control prop-client-commission',
                ],

            ])
            ->add('vendor_commission', 'text', [
                'label'      =>_('Vendor Commission'),
                'label_attr' => ['class' => 'control-label '],
                'attr'       => [
                    'placeholder'  => _('Vendor commission... '),
                    'class' => 'form-control prop-vendor-commission',
                ],

            ]);
    }
}
