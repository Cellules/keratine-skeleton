define([
	'jquery',
	'jquery.i18n',
	'jquery.i18n.messages',
	'jquery.i18n.fallbacks',
	'jquery.i18n.parser',
	'jquery.i18n.emitter',
	'jquery.i18n.language',
    'jquery-ui',
	'ckeditor',
    'chosen',
    'lightbox',
    'bootstrap-collection',
    'fileexplorer',
    'TextboxList.Autocomplete'
],
function ($) {

    // jQuery browser fallback
    if (typeof $.browser === 'undefined') {
        $.browser = {
            version: -1,
            msie: function () {
                if (navigator.appName == 'Microsoft Internet Explorer') {
                    var ua = navigator.userAgent;
                    var re  = new RegExp("MSIE ([0-9]{1,}[.0-9]{0,})");
                    if (re.exec(ua) !== null) {
                        $.browser.version = parseFloat( RegExp.$1 );
                    }
                    return true;
                }
                return false;
            }
        };
    }

    var addStyleLink = function (href) {
        var link = document.createElement('link');
        link.href = window.basePath +'/'+ href;
        link.rel  = 'stylesheet';
        link.type = 'text/css';
        document.getElementsByTagName('head')[0].appendChild(link);
    };

	var initAjaxlinks = function () {
		$('a.ajax').on('click', function (event) {
			event.preventDefault();

			var target = this;
			var callback = new Function('data', $(this).attr('data-callback') );

			$.ajax(this.href)
				.done(function (data, textStatus, jqXHR) {
					if (callback) {
						callback.call(target, data);
					}
				})
				.fail(function (jqXHR, textStatus, errorThrown) {
					alert(textStatus);
				});

			return false;
		});
	};

    var initSortable = function () {
        $('.sortable').sortable({
            items: '> tbody > tr',
            update: function( event, ui ) {
                var uri = ui.item.parents('.sortable').attr('data-sortable-uri');
                if (!uri) return;
                uri = uri.replace(/__id__/g, ui.item.attr('data-entity-id')).replace(/__position__/g, ui.item.index());
                $.post(uri)
                    .fail(function (error) {
                        console.log(error);
                        alert($.i18n('An error occured'));
                    });
            }
        });
        $('.sortable').disableSelection();
    };

	var initForm = function () {
		createFieldset( $('.content').find('.control-group') );
		// initFormPrototypes();
        initChosen();
        initTextboxList();
		initCKEditor();
	};

	var createFieldset = function ($el) {
		$el.has('.control-group')
		// create fieldset for embeded forms
		.addClass('form-fieldset')
		// toogle open/close fieldset
		.find('> label').each(function () {
			var $el = $(this);
			$el.after(
				$('<input type="checkbox" checked="checked">')
			);
			$el.on('click', function (event) {
				var $checkbox = $(this).next('input[type="checkbox"]');
				if ($checkbox.is(':checked')) {
					$checkbox.prop('checked', false);
				}
				else {
					$checkbox.prop('checked', true);
				}
			});
		});
	};

	var addForm = function ($collection) {
		var prototype = $collection.attr('data-prototype');
		var count = $collection.children().length;
		var newForm = prototype.replace(/__name__label__/g, count).replace(/__name__/g, count);
		var $newForm = $('<div class="control-group"></div>').append(newForm);
		createFieldset( $newForm );
		createFieldset( $newForm.find('.control-group') );
		addRemoveLink($newForm);
		$collection.append($newForm);
	};

	var addRemoveLink = function ($item) {
		var $removeLink = $('<button class="btn btn-danger remove_link"><i class="icon-trash"></i> '+ $.i18n('Remove') +'</button>');
		$item.append($removeLink);
		$removeLink.on('click', function (e) {
			e.preventDefault();
			if (confirm($.i18n('Are yout sure?'))) {
				$(this).parents('.control-group').eq(0).remove();
			}
		});
	};

	var initFormPrototypes = function () {
		$('form').find('[data-prototype]').each(function () {
			var $collection = $(this);
			// add link
			var $addLink = $('<button class="btn add_link"><i class="icon-plus"></i> '+ $.i18n('Add') +'</button>');
			$collection.after($addLink);
			$addLink.on('click', function (e) {
				e.preventDefault();
				addForm($collection);
			});
			// remove link
			$collection.children('.control-group').each(function () {
				addRemoveLink($(this));
			});
		});
	};

    var initChosen = function () {
        $(".chosen-select").chosen({});
    };

    var initTextboxList = function () {
        addStyleLink('/js/vendor/TextboxList/TextboxList.css');
        addStyleLink('/js/vendor/TextboxList/TextboxList.Autocomplete.css');

        $('.textbox-list').each(function () {
            var t = new $.TextboxList(this, {
                plugins: {
                    autocomplete: {}
                }
            });
            t.plugins['autocomplete'].setValues([
                // [31, 'value'],
            ]);
            t.getContainer().addClass('form-control');
        });
    };

	var initCKEditor = function () {
		CKEDITOR.replaceAll(function (textarea, config) {
            if (false === textarea.classList.contains('rich')) return false;
            config.language = $.i18n().locale;
            config.uiColor = '#f5f5f5';
            config.toolbar = 'Basic';
            config.baseHref = window.basePath;
            // http://docs.ckeditor.com/#!/guide/dev_file_browse_upload
            config.filebrowserBrowseUrl = window.basePath + '/js/vendor/elfinder/elfinder.html';
            // config.filebrowserUploadUrl = '/uploader/upload.php';
            config.enterMode = CKEDITOR.ENTER_BR;
            config.shiftEnterMode = CKEDITOR.ENTER_P;
        });
	};

	getUserLocale = function () {
		var locale = typeof navigator === "undefined" ? false : (navigator.language || navigator.userLanguage).toLowerCase();
		locale = locale.substr(0, 2); // keep only lang information
		return locale;
	};

	var initI18n = function () {
		// retrieve user locale
		var locale = getUserLocale();
		console.log('user locale is', locale);
		// set DOM locale
		document.documentElement.lang = locale;
		// init jQuery I18n
		// $.i18n.debug = true;
		window.i18n = $.i18n({ locale: locale });
		// This will return a jQuery.Promise
		return $.i18n().load({
			'en': window.basePath + '/js/admin/locale/en.json',
			'fr': window.basePath + '/js/admin/locale/fr.json',
		});
	};

	return {
		initialize: function () {
			var deferred = initI18n();
			deferred.always(function () {
				initAjaxlinks();
                initSortable();
				initForm();
			});
		}
	};

});
