      $(document).ready(function() {
        year = parseInt(location.pathname.slice(1)) || 2018;
        $.getJSON(`/api/images?year=${year}`, function(data) {
          $.each(data, function(key, img) {

            $('<div>', {
                class: 'col-lg-3 col-md-4 col-xs-6',
                html: $('<a>', {
                  href: `/api/image?year=${year}&img=${img}`,
                  class: 'd-block mb-4 h-100',
                  html: $('<img>', {
                    class: 'img-fluid img-thumbnail',
                    src: `/api/image?year=${year}&img=${img}`,
                    alt: ''
                  })
                })
             }).appendTo($('#gallery'));;
          });
        }).fail(function() { location = '/login';});
      });