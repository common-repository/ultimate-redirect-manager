jQuery(document).ready(function($) {
    // Handle tab navigation
    $('.nav-tab-wrapper a').click(function(e) {
        e.preventDefault();
        var tabId = $(this).attr('href'); // Get the href attribute of the clicked tab
        $('.nav-tab-wrapper a').removeClass('nav-tab-active'); // Remove active class from all tabs
        $(this).addClass('nav-tab-active'); // Add active class to the clicked tab
        $('.ultimate-404-tab-content').hide(); // Hide all tab contents
        $(tabId).show(); // Show the tab content corresponding to the clicked tab
        // Update the URL slug based on the tab
        var currentSlug = window.location.href.split('#')[0]; // Get the current URL without the hash
        var newSlug = currentSlug + tabId; // Append the tabId to the current URL
        history.pushState({}, '', newSlug); // Update the URL without reloading the page
        
        // Store the active tab ID in local storage
        localStorage.setItem('activeTabId', tabId);
    });

    // Trigger click event on the tab specified in the URL
    var activeTabId = window.location.hash; // Get the tab ID from the URL hash
    if (activeTabId) {
        $('.nav-tab-wrapper a[href="' + activeTabId + '"]').trigger('click'); // Trigger click event on the tab with the specified ID
    } else {

        // If no tab ID in the URL, trigger click event on the stored active tab or the first tab
        var storedActiveTabId = localStorage.getItem('activeTabId');
        if (storedActiveTabId) {
            $('.nav-tab-wrapper a[href="' + storedActiveTabId + '"]').trigger('click'); // Trigger click event on the stored active tab
        } else {
            $('.nav-tab-wrapper a:first').trigger('click'); // Trigger click event on the first tab
        }
    }

    $('.delete-row').on('click', function() {
        var rowId = $(this).data('rule-id');
        var data = {
            'action': 'delete_rule',
            'rule_id': rowId
        };

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            success: function(response) {
                // Handle success
                alert('Row deleted successfully');
                // Optionally, you can remove the deleted row from the table
                // $(this).closest('tr').remove();
                // Reload the page to reflect the changes
                location.reload();
            },
            error: function(xhr, status, error) {
                // Handle error
                alert('Error deleting row: ' + error);
            }
        });
    });
});