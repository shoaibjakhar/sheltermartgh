@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        <div {!! $options['wrapperAttrs'] !!}>
            @endif
            @endif

            @if ($showLabel && $options['label'] !== false && $options['label_show'])
                {!! Form::customLabel($name, $options['label'], $options['label_attr']) !!}
            @endif

            @if ($showField)
                {!! Form::textarea($name, $options['value'], $options['attr']) !!}
                @include('core/base::forms.partials.help_block')
            @endif

            @include('core/base::forms.partials.errors')

            @if ($showLabel && $showField)
                @if ($options['wrapper'] !== false)
        </div>
    @endif
@endif

@push('scripts')
    <script>
        "use strict";
        function setImageValue(file) {
            $('.mce-btn.mce-open').parent().find('.mce-textbox').val(file);
        }
    </script>
    <iframe id="form_target" name="form_target" style="display:none"></iframe>
    <form id="upload_form" action="{{ route('public.vendor.upload-from-editor') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0;height:0;overflow:hidden;display: none;">
        {{ csrf_field() }}
        <input name="upload" id="upload_file" type="file" onchange="$('#upload_form').submit();this.value='';">
    </form>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('body').on('keyup', '#price-number', function(event) {
            $this=$(this);
            var amount=$(this).val();  
              
            var type=$('body').find('select[name="type"]').val();  
            $.ajax({
                url: '{{ url('account/properties/commission') }}',
                type: 'POST',
                dataType: 'json',
                data: {type:type,amount:amount},
            })
            .done(function(response) {
            var html='';
            $('body').find('.row-cal-com').remove();
            html='<div class="row row-cal-com" style="margin:10px auto !important">';
             if(response.comession){   
             html+='<div class="form-group col-md-3"><label for="comession" class="control-label">Comession</label> <input id="comession" name="comession" type="text" class="form-control input-mask-number is-valid" im-insert="true" aria-invalid="false" value="'+response.comession+'" ></div>';
            }
            if(response.admin_commes){   
             html+='<div class="form-group col-md-3"><label for="admin_commes" class="control-label">Admin Comession</label> <input id="admin_commes" name="admin_commes" type="text" class="form-control input-mask-number is-valid" im-insert="true" aria-invalid="false" value="'+response.admin_commes+'" ></div>';
            }
            if(response.client_commes){   
             html+='<div class="form-group col-md-3"><label for="client_commes" class="control-label">Client Comession</label> <input id="client_commes" name="client_commes" type="text" class="form-control input-mask-number is-valid" im-insert="true" aria-invalid="false" value="'+response.client_commes+'" ></div>';
            }
            if(response.vendor_commes){   
             html+='<div class="form-group col-md-3"><label for="vendor_commes" class="control-label">Vendor Comession</label> <input id="vendor_commes" name="vendor_commes" type="text" class="form-control input-mask-number is-valid" im-insert="true" aria-invalid="false" value="'+response.vendor_commes+'" ></div>';
            }
            html+='</row>';
            $this.closest('.row').append( html);
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            
            });
        });
        var imgUpload = document.getElementById('document')
          , imgPreview = document.getElementById('img_preview')
          , totalFiles
          , previewTitle
          , previewTitleText
          , img;

        imgUpload.addEventListener('change', previewImgs, false);
        

        function previewImgs(event) {
        imgPreview.innerHTML='';    
        totalFiles = imgUpload.files.length;
        imgPreview.classList.remove('quote-imgs-thumbs--hidden');  
          // if(!!totalFiles) {
          //   imgPreview.classList.remove('quote-imgs-thumbs--hidden');
          //   previewTitle = document.createElement('p');
          //   previewTitle.style.fontWeight = 'bold';
          //   previewTitleText = document.createTextNode(totalFiles + ' Total Images Selected');
          //   previewTitle.appendChild(previewTitleText);
          //   imgPreview.appendChild(previewTitle);
          // }
          
          for(var i = 0; i < totalFiles; i++) {
            var extention=event.target.files[i].type;
            var res = extention.split("/");
            if(res[0]=='image'){
            img = document.createElement('img');
            img.src = URL.createObjectURL(event.target.files[i]);
            img.classList.add('img-preview-thumb');
            imgPreview.appendChild(img);
            }else{
            imgPreview.innerHTML += '<div style="margin:10px"><i class="fas fa-file"></i>'+event.target.files[i].name+'</div>';
            }
            
          }
        }
    </script>
    <style type="text/css">
        .quote-imgs-thumbs {
          background: #eee;
          border: 1px solid #ccc;
          border-radius: 0.25rem;
          margin: 1.5rem 0;
          padding: 0.75rem;
        }
        .quote-imgs-thumbs--hidden {
          display: none;
        }
        .img-preview-thumb {
          background: #fff;
          border: 1px solid #777;
          border-radius: 0.25rem;
          box-shadow: 0.125rem 0.125rem 0.0625rem rgba(0, 0, 0, 0.12);
          margin-right: 1rem;
          max-width: 140px;
          padding: 0.25rem;
        }
    </style>
@endpush
