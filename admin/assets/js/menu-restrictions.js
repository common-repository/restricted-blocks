jQuery(document).ready(function($) {

  'use strict';

  //Init datetimepicker --------------------------------------------------------

  //create array with the available steps of the day
  let allowTimesArray = Array();

  for (let hour = 0; hour <= 23; hour++) {

    let formatted_hour = null;
    let formatted_minute = null;
    let minute = 0;

    while (minute <= 50) {

      if (hour <= 9) {
        formatted_hour = '0' + hour;
      } else {
        formatted_hour = hour;
      }

      if (minute <= 9) {
        formatted_minute = '0' + minute;
      } else {
        formatted_minute = minute;
      }

      allowTimesArray.push(formatted_hour + ':' + formatted_minute);

      minute = minute + 10;

    }

  }

  //init datatimepicker
  jQuery('#start-date').datetimepicker({
    format: 'Y-m-d H:i',
    allowTimes: allowTimesArray,
  });

  jQuery('#end-date').datetimepicker({
    format: 'Y-m-d H:i',
    allowTimes: allowTimesArray,
  });

  //Init select2 ---------------------------------------------------------------

  $('#type').select2();
  $('#device').select2();
  $('#output-behavior').select2();
  $('#mode').select2();

  removeBorderLastTableTr();

  $(document.body).on('click', '.group-trigger', function() {

    'use strict';

    //open and close the various sections of the tables area
    const target = $(this).attr('data-trigger-target');
    $('.' + target).toggle();

    $(this).find('.expand-icon').toggleClass('arrow-down');

    removeBorderLastTableTr();

  });

  $(document.body).on('click', '#cancel', function(event) {

    'use strict';

    //reload the Restrictions menu
    event.preventDefault();
    window.location.replace(window.DAEXTREBL_PARAMETERS.adminUrl +
        'admin.php?page=daextrebl-restrictions');

  });

  /*
 Remove the bottom border on the last visible tr included in the form
 */
  function removeBorderLastTableTr() {

    'use strict';

    $('table.daext-form-table tr > *').css('border-bottom-width', '1px');
    $('table.daext-form-table tr:visible:last > *').
        css('border-bottom-width', '0');

  }

  //Dialog Confirm ---------------------------------------------------------------------------------------------------
  window.DAEXTREBL = {};
  $(document.body).on('click', '.menu-icon.delete', function(event) {

    'use strict';

    event.preventDefault();
    window.DAEXTREBL.restrictionToDelete = $(this).prev().val();
    $('#dialog-confirm').dialog('open');

  });

  /**
   * Dialog confirm initialization.
   */
  $(function() {

    'use strict';

    $('#dialog-confirm').dialog({
      autoOpen: false,
      resizable: false,
      height: 'auto',
      width: 340,
      modal: true,
      buttons: {
        [objectL10n.deleteText]: function() {

          'use strict';

          $('#form-delete-' + window.DAEXTREBL.restrictionToDelete).submit();

        },
        [objectL10n.cancelText]: function() {

          'use strict';

          $(this).dialog('close');

        },
      },
    });

  });

});