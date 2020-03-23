jQuery(document).ready(function($){
    $(document).on('change', '[data-key="field_medline_code_type"] .selected input, #acf-field_medline_code_id', function() {
        type = $('[data-key="field_medline_code_type"] .selected input').val();
        if ('icd' == type){
            typeid = '2.16.840.1.113883.6.90';
        } else if ('ndc' == type) {
            typeid = '2.16.840.1.113883.6.69';
        } else if ('lonic' == type) {
            typeid = '2.16.840.1.113883.6.1';
        }
  
        code = $('#acf-field_medline_code_id').val();
  
        url = 'https://connect.medlineplus.gov/demo/service?';
        type = 'mainSearchCriteria.v.cs=' + typeid;
        code = 'mainSearchCriteria.v.c=' + code;
  
        _href = url + type + '&' + code;
  
        $("#view-medline-data").attr("href",_href);
    });
  });