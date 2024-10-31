jQuery(document).ready(function($) {

  'use strict';

  /**
   * Handles the clicks on the eye icon used to display or hide the password.
   * Note that eye icon is present in input field of a restriction of type
   * "Password".
   */
  jQuery(document).on('click', '.daextrebl-password-toggle', function() {

    'use strict';

    const inputElement = $(this).prev();
    const passwordToggle = $(this);
    const eyeIconVisible = passwordToggle.find('.daextrebl-eye-visible-svg');
    const eyeIconInvisible = passwordToggle.find('.daextrebl-eye-invisible-svg');

    //Toggle the class used to control the visibility of the two svg icons
    eyeIconVisible.toggleClass('daextrebl-display-none');
    eyeIconInvisible.toggleClass('daextrebl-display-none');

    //Toggle the input type
    if (inputElement.attr('type') == 'password') {
      inputElement.attr('type', 'text');
    } else {
      inputElement.attr('type', 'password');
    }

  });

  /**
   * Handles the clicks on the submit button associated with the restriction of
   * type "Password".
   */
  jQuery(document).on('click', '.daextrebl-password-form-submit', function() {

    'use strict';

    const restrictionId = parseInt(
        $(this).parent().parent().attr('data-restriction-id'), 10);
    const passwordElement = $(this).parent().prev().children()[1];
    const password = $(passwordElement).val();
    const passwordInvalidMessageElement = $(this).parent().next().children();
    let passwordIsValid = null;

    /**
     * Perform an ajax request to verify if the submitted password is valid.
     */
    let data = {
      'action': 'daextrebl_verify_password',
      'security': window.DAEXTREBL_PARAMETERS.nonce,
      'restriction_id': restrictionId,
      'password': password,
    };

    //Send ajax request
    $.post(window.DAEXTREBL_PARAMETERS.ajax_url, data, function(jsonData) {

      //Convert the retrieved JSON data into an array
      let response = JSON.parse(jsonData);
      const passwordIsValid = parseInt(response['valid'], 10) === 1 ? true : false;
      const cookieValue = response['cookie_value'];


      //Verify if the password is valid
      if (passwordIsValid) {

        //Set cookie with value equal to 1
        setCookie('daextrebl-password-' + restrictionId, cookieValue, window.DAEXTREBL_PARAMETERS.cookieExpiration);

        //Reload the page
        window.location.reload(false);

      } else {

        //Set cookie with value equal to 1
        setCookie('daextrebl-password-' + restrictionId, 0, window.DAEXTREBL_PARAMETERS.cookieExpiration);

        //Display the invalid password message
        passwordInvalidMessageElement.show();

      }

    });

  });

  /**
   * Set a cookie based on the provided parameters.
   *
   * @param name The name of the cookie.
   * @param value The value of the cookie.
   * @param The expiration in seconds.
   */
  function setCookie(name, value, expiration) {

    'use strict';

    const now = new Date();
    const time = now.getTime();
    const expireTime = time + (expiration * 1000);
    now.setTime(expireTime);
    const formattedExpiration = now.toUTCString();
    document.cookie = name + '=' + value + '; expires=' + formattedExpiration +
        '; path=/';

  }

});