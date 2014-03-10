define([
    'jquery',
],
function ($) {

    var submitForm = function (evt) {
        evt.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: this.action,
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(data) {
                var response = $.parseJSON(data);
                if(response.code == "success") {
                    alert("Success!");
                } else if(response.code === "failure") {
                    alert(response.err);
                }
            }
        });
    };

    $('[data-lightbox]').on('click', function (evt) {
        evt.preventDefault();

        var url = this.dataset.lightbox;
        console.log(url);

        var $lightbox = $('#lightbox');

        if ($lightbox.length === 0) {
            $lightbox = $('<div class="container lightbox" id="lightbox" />');
            $lightbox.appendTo(document.body);
        }

        $lightbox.load(url, function (response) {
            var $this = $(this);
            $this.find('form').on('submit', submitForm);
            $this.addClass('open');
        });

    });

});