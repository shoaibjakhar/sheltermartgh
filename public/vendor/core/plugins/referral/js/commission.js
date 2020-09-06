jQuery(document).ready(function($) {
  $('body').on('change', '.property-class', function(event) {
    $.ajax({
      url: $(this).data('url'),
      type: 'POST',
      dataType: 'json',
      data: {propertyId: $(this).val()},
    })
    .done(function(response) {
      $('body').find('input[name="commission_id"]').val(response.commission_id);
      $('body').find('.property-type').val(response.property_type);
      $('body').find('.property-type').select2().trigger('change');
      $('body').find('.property-price').val(response.property_price);
      $('body').find('.property-commission').val(response.property_commission);
      $('body').find('.prop-vendor-commission').val(response.vendor_commission);
      $('body').find('.prop-client-commission').val(response.client_commission);
      $('body').find('.prop-admin-commission').val(response.admin_commission);
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
    
  });
  $('body').on('change', '.property-type', function(event) {
    $.ajax({
      url: $('body').find('.property-class').data('url'),
      type: 'POST',
      dataType: 'json',
      data: {propertyId: $('body').find('.property-class').val(),propertyType:$(this).val()},
    })
    .done(function(response) {
      $('body').find('.property-price').val(response.property_price);
      $('body').find('input[name="commission_id"]').val(response.commission_id);

      $('body').find('.property-commission').val(response.property_commission);
      $('body').find('.prop-vendor-commission').val(response.vendor_commission);
      $('body').find('.prop-client-commission').val(response.client_commission);
      $('body').find('.prop-admin-commission').val(response.admin_commission);
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
    
  });
  $('body').on('keyup', '.property-price', function(event) {
    $.ajax({
      url: $('body').find('.property-class').data('url'),
      type: 'POST',
      dataType: 'json',
      data: {propertyId: $('body').find('.property-class').val(),propertyType: $('body').find('.property-type').val(),propertyPrice:$(this).val()},
    })
    .done(function(response) {
      $('body').find('input[name="commission_id"]').val(response.commission_id);
      $('body').find('.property-commission').val(response.property_commission);
      $('body').find('.prop-vendor-commission').val(response.vendor_commission);
      $('body').find('.prop-client-commission').val(response.client_commission);
      $('body').find('.prop-admin-commission').val(response.admin_commission);
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
    
  });

});