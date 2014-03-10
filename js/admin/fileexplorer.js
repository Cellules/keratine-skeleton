define([
    'jquery',
    'Class',
],
function ($) {

    var FileExplorer = Class.extend({
        el: null,
        options: {
            title: 'File explorer',
            width: 800,
            height: 600,
        },
        init: function(el, options){
            var self = this;
            this.el = $(el);
            var opt = this.options = $.extend(true, {}, this.options, options);
            var input = this.el.find('input');
            var id = input.attr('id');
            input.on('change', function (evt, file) {
                // console.log(file);
                self.setValue(file.path);
            });
            this.el.find('button').on('click', function () {
                var btn = $(this);
                window.open(btn.attr('data-explorer-uri')+'&caller='+id, opt.title, 'width='+opt.width+', height='+opt.height+', location=false, toolbar=false, scrollbars=false');
            });
        },
        setValue: function(value){
            this.el.find('input').val(value);
        },
        getValue: function(){
            return this.el.find('input').val();
        }
    });

    $(window).ready(function() {

        $('.fileexplorer').each(function () {
            new FileExplorer(this);
        });

        $(document.body).on('DOMNodeInserted', function(e) {
            $(e.target).find('.fileexplorer').each(function () {
                new FileExplorer(this);
            });
        });

    });

    return FileExplorer;

});