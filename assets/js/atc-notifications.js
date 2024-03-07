/**
 * Handle displaying the notifications in the frontend
 * Uses https://www.npmjs.com/package/@wordpress/eslint-plugin package with "custom" ruleset
 */

// Check if document is ready.
(function ($) {
  let atcNotification_wrapper = $("#atc-notification");

  /**
   * Generate the notification html and display it
   * @param {*} fragments 
   */
  function atcGenerateNotification(fragments) {
    // Check if the fragments are available
    if (fragments && fragments.product_data) {
      let title = fragments.product_data.name,
        price_html = fragments.product_data.price_html,
        image = fragments.product_data.image,
        url = fragments.product_data.url;

      // Check if the notification container is available
      if (atcNotification_wrapper.length) {
        atcCloseNotification();

        // Get the html for the notification from localizations and replace the placeholders on all the occurrences
        let notification_html = ATCNotifications.layout;
        // replace all the placeholders
        notification_html = notification_html.replace(/{title}/g, title);
        notification_html = notification_html.replace(/{price_html}/g,price_html);
        notification_html = notification_html.replace(/{image}/g, image);
        notification_html = notification_html.replace(/{url}/g, url);

		// Empty the wrapper
		atcNotification_wrapper.empty();

        // Append the notification to the container
        atcNotification_wrapper.html(notification_html);

		// Display the notification
        atcDisplayNotification();
      }
    }
  }

  /**
   * Display the notification
   */
  function atcDisplayNotification() {

    // Add the class to display the notification
    atcNotification_wrapper.addClass("show");

    // Set a timeout to remove the class after 5 seconds
    setTimeout(function () {
      atcNotification_wrapper.removeClass("show");
    }, ATCNotifications.close_after * 1000);
  }

  /**
   * Close the notification
   */
  function atcCloseNotification() {
    atcNotification_wrapper.removeClass("show");
  }

  // Add event listener for closing the notification
  $(document).on("click", ".atc-notification-close-btn", function () {
    atcCloseNotification();
  });

  // Add event listener for displaying the notification
  $(document).on("added_to_cart", function (event, fragments) {
    atcGenerateNotification(fragments);
  });

})(jQuery);
