<?php

namespace Botble\Referral\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\Fields\TagField;
use Botble\Base\Forms\FormAbstract;
use Botble\Referral\Forms\Fields\CategoryMultiField;
use Botble\Referral\Http\Requests\SettingRequest;
use Botble\Referral\Models\Setting;
use Botble\Referral\Repositories\Interfaces\CategoryInterface;

class SettingForm extends FormAbstract
{

    /**
     * @var string
     */
    protected $template = 'core/base::forms.form-tabs';
    protected $type=null;
    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function buildForm()
    {
        $data=$this->getModel();

        $this
            ->setupModel(new Setting)
            ->setValidatorClass(SettingRequest::class)
            ->withCustomFields()
            ->addCustomField('tags', TagField::class);
        if($data){
                $this->add('comession', 'text', [
                        'label'      => _('Comession'),
                        'label_attr' => ['class' => 'control-label required'],
                        'attr'       => [
                            'placeholder'  => _('total commission in percentage...'),
                            'data-counter' => 120,
                            
                        ],
                        'value'=>$data->comession,
                    ]);
                $this->add('vendor_commission', 'text', [
                        'label'      => _('Vendor commission'),
                        'label_attr' => ['class' => 'control-label required'],
                        'attr'       => [
                            'placeholder'  => _('Vendor commission in percentage...'),
                            'data-counter' => 120,
                            
                        ],
                        'value'=>$data->vendor_comm,
                    ]);
                $this->add('Userclient_commission', 'text', [
                        'label'      => _('User/Client Commission'),
                        'label_attr' => ['class' => 'control-label required'],
                        'attr'       => [
                            'placeholder'  => _('User/Client commission in percentage...'),
                            'data-counter' => 120,
                            
                        ],
                        'value'=>$data->client_comm,
                    ]);
                
                $this->add('admin_commission', 'text', [
                        'label'      => _('Admin Commission'),
                        'label_attr' => ['class' => 'control-label required'],
                        'attr'       => [
                            'placeholder'  => _('Admin commission in percentage...'),
                            'data-counter' => 120,
                           
                        ],
                        'value'=>$data->admin_comm,
                    ]);
                $this->remove('property_type');
                $this->add('property_type', 'hidden', [
                'value' => $data->property_type,
            ]);
                
            
        }else{
             $this->add('comession', 'text', [
                        'label'      => _('Comession'),
                        'label_attr' => ['class' => 'control-label required'],
                        'attr'       => [
                            'placeholder'  => _('total commission in percentage...'),
                            'data-counter' => 120,
                            
                        ],
                        
                    ]);
            $this->add('vendor_commission', 'text', [
                        'label'      => _('Vendor commission'),
                        'label_attr' => ['class' => 'control-label required'],
                        'attr'       => [
                            'placeholder'  => _('Vendor commission in percentage...'),
                            'data-counter' => 120,
                        ],
                    ]);
            $this->add('Userclient_commission', 'text', [
                    'label'      => _('User/Client Commission'),
                    'label_attr' => ['class' => 'control-label required'],
                    'attr'       => [
                        'placeholder'  => _('User/Client commission in percentage...'),
                        'data-counter' => 120,
                    ],
                ]);
            $this->add('admin_commission', 'text', [
                    'label'      => _('Admin Commission'),
                    'label_attr' => ['class' => 'control-label required'],
                    'attr'       => [
                        'placeholder'  => _('Admin commission in percentage...'),
                        'data-counter' => 120,
                    ],
                ]);
            $this->remove('property_type');
                $this->add('property_type', 'hidden', [
                'value' => $this->getData('type'),
            ]);
        }
        $this->setBreakFieldPoint('status');
    }
}
