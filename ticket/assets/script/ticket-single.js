jQuery(document).ready(function($){
    $('.accordion-header').click(function(){
        $(this).toggleClass('active');
        $(this).next('.accordion-content').slideToggle();
    });
});
document.addEventListener('DOMContentLoaded', function() {
    // Get the submit button and form
    const submitButton = document.getElementById('submit');
    const form = submitButton.closest('form');

    // Add event listener to submit button
    submitButton.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the default button action

        // Get the loading spinner element
        const spinner = document.querySelector('.loading-spinner');
        const loading_overlay = document.getElementById("loading-overlay");

        // Show the loading spinner
        spinner.style.display = 'block';
        loading_overlay.style.display = 'block';
        // Perform AJAX request using jQuery
        $.ajax({
            type: form.getAttribute('method'),
            url: form.getAttribute('action'),
            data: $(form).serialize(),
            success: function(response) {
                location.reload(); // Reload the page on success
            },
            error: function(xhr, status, error) {
                if (xhr.status === 404) {
                    location.reload(); // Reload the page if 404 error
                } else {
                    alert('Error: ' + xhr.status + ' - ' + xhr.statusText);
                }
            },
            complete: function() {
                // Hide the loading spinner
                // spinner.style.display = 'none';
                // loading_overlay.style.display = 'none';
            }
        });
    });
});


function _togglePopUpTicket() {
    var div = document.getElementById("_ticket_popup");
    if (div.style.display === "none") {
        div.style.display = "block";
    } else {
        div.style.display = "none";
    }
}