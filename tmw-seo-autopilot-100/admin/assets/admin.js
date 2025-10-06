(function ($) {
    'use strict';

    function postJSON(endpoint, data) {
        return wp.apiRequest({
            path: endpoint,
            method: 'POST',
            data: data,
        });
    }

    $(function () {
        var keywordForm = $('#tmw-sa100-keyword-form');
        var contentForm = $('#tmw-sa100-content-form');
        var settingsForm = $('#tmw-sa100-settings-form');

        if (keywordForm.length) {
            keywordForm.on('submit', function (event) {
                event.preventDefault();

                var keywords = keywordForm.find('textarea[name="keywords"]').val().split('\n').filter(Boolean);
                var locale = keywordForm.find('select[name="locale"]').val();
                var output = keywordForm.find('.tmw-sa100-output');

                output.text(keywordForm.data('loading'));

                postJSON('tmw-sa100/v1/keyword-plan', {
                    keywords: keywords,
                    locale: locale,
                })
                    .done(function (response) {
                        output.text(JSON.stringify(response, null, 2));
                    })
                    .fail(function (error) {
                        output.text(error.responseJSON && error.responseJSON.message ? error.responseJSON.message : error.statusText);
                    });
            });
        }

        if (contentForm.length) {
            contentForm.on('submit', function (event) {
                event.preventDefault();

                var topic = contentForm.find('input[name="topic"]').val();
                var outline = contentForm.find('textarea[name="outline"]').val().split('\n').filter(Boolean);
                var output = contentForm.find('.tmw-sa100-output');

                output.text(contentForm.data('loading'));

                postJSON('tmw-sa100/v1/content-draft', {
                    topic: topic,
                    outline: outline,
                })
                    .done(function (response) {
                        output.text(response);
                    })
                    .fail(function (error) {
                        output.text(error.responseJSON && error.responseJSON.message ? error.responseJSON.message : error.statusText);
                    });
            });
        }

        if (settingsForm.length) {
            settingsForm.on('submit', function (event) {
                event.preventDefault();

                var data = {
                    serper_api_key: settingsForm.find('input[name="serper_api_key"]').val(),
                    openai_api_key: settingsForm.find('input[name="openai_api_key"]').val(),
                    default_locale: settingsForm.find('select[name="default_locale"]').val(),
                };

                settingsForm.find('.tmw-sa100-status').text(settingsForm.data('loading'));

                wp.apiRequest({
                    path: 'tmw-sa100/v1/settings',
                    method: 'POST',
                    data: data,
                })
                    .done(function () {
                        settingsForm.find('.tmw-sa100-status').text(settingsForm.data('success'));
                    })
                    .fail(function (error) {
                        settingsForm.find('.tmw-sa100-status').text(error.responseJSON && error.responseJSON.message ? error.responseJSON.message : error.statusText);
                    });
            });
        }
    });
})(jQuery);
