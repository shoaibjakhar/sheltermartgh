!function(e){var t={};function n(a){if(t[a])return t[a].exports;var r=t[a]={i:a,l:!1,exports:{}};return e[a].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,a){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:a})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(n.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(a,r,function(t){return e[t]}.bind(null,r));return a},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=315)}({315:function(e,t,n){e.exports=n(316)},316:function(e,t){var n=function(){new RvMediaStandAlone(".js-btn-trigger-add-image",{onSelectFiles:function(e,t){var n=t.closest(".object-images-wrapper").find(".images-wrapper .list-gallery-media-images");n.removeClass("hidden"),$(".default-placeholder-object-image").addClass("hidden"),_.forEach(e,(function(e){var a=$(document).find("#object_select_image_template").html().replace(/__name__/gi,t.attr("data-name")),r=$('<li class="object-image-item-handler">'+a+"</li>");r.find(".image-data").val(e.url),r.find(".preview_image").attr("src",e.thumb).show(),n.append(r)}))}}),new RvMediaStandAlone(".images-wrapper .btn-trigger-edit-object-image",{onSelectFiles:function(e,t){var n=_.first(e),a=t.closest(".object-image-item-handler").find(".image-box"),r=t.closest(".list-gallery-media-images");a.find(".image-data").val(n.url),a.find(".preview_image").attr("src",n.thumb).show(),_.forEach(e,(function(e,t){if(t){var n=$(document).find("#object_select_image_template").html().replace(/__name__/gi,a.find(".image-data").attr("name")),i=$('<li class="object-image-item-handler">'+n+"</li>");i.find(".image-data").val(e.url),i.find(".preview_image").attr("src",e.thumb).show(),r.append(i)}}))}})};$(document).ready((function(){n(),$("body").on("click",".list-gallery-media-images .btn_remove_image",(function(e){e.preventDefault(),$(this).closest("li").remove()})),$(document).on("click",".btn-trigger-remove-object-image",(function(e){e.preventDefault(),$(this).closest(".object-image-item-handler").remove(),0===$(".list-gallery-media-images").find(".object-image-item-handler").length&&$(".default-placeholder-object-image").removeClass("hidden")})),$(document).on("change","#type",(function(e){"rent"===$(e.currentTarget).val()?$("#period").closest(".form-group").removeClass("hidden").fadeIn():$("#period").closest(".form-group").addClass("hidden").fadeOut()})),$(document).on("change","#never_expired",(function(e){!0===$(e.currentTarget).is(":checked")?$("#auto_renew").closest(".form-group").addClass("hidden").fadeOut():$("#auto_renew").closest(".form-group").removeClass("hidden").fadeIn()}))}))}});
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