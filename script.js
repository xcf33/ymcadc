(function ($) {
  $(function() {
    // Make secondary category work.
    $('#ddPrimaryCat').change(function(e) {
          var primary_option = $(this).val();
          var url = 'secondary_cat.php?cat=' + primary_option;
          $.post(url, function(data) {
            $( "#spnSecondaryCat" ).html(data);
          });
    })

    $('form#frmbranches').submit(function(e) {
      // Prevent the form from submitting
      e.preventDefault();

      // Building the URL
      var base_url = 'https://easytoenroll.ymcadc.org/register/easytoenroll/branches/branches?nsSubmit=Y&Submit=Y&chkAdvancedSearch=Y';

      var fields = [
      'ddBranches',
      'ddPrimaryCat',
      'ddSecondaryCat',
      'ddAllAge',
      'txtKeyword',
      'txtProgramNo',
      'txtFromdate',
      'txtToDate',
      'chkMon',
      'chkTue',
      'chkWed',
      'chkThu',
      'chkFri',
      'chkSat',
      'chkSun',
      'ddAllGender',
      'ddSortBy'
    ];

    var values = '';
    for (var i = 0; i < fields.length; i++) {
      var field_value = $('#' + fields[i]).val();
      values += '&' + fields[i] + '=' + field_value;
    }
    base_url += values;
    $('#url_builder').val(base_url);
    var iframe = '<iframe frameborder=0 width="1440" height="810" src="' + base_url + '"></iframe>';
    $('#frame_container').html(iframe);
    })


    // change the preview URL and reload the iframe
    // $('#preview').attr('src', base_url);
  });
})(jQuery);
