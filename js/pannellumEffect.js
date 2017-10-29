
(function($) {

  /**
   * Initialize pannellum functionality.
   */
  Drupal.behaviors.pannellum = {
    attach: function(context, drupalSettings) {
      $('.panorama').once('pannellum').each(function(index) {

      var id = $(this).attr('id');
      var effectType = drupalSettings.pannellum[index].type;
      var imgSrc = drupalSettings.pannellum[index].src;
      var autoload = drupalSettings.pannellum[index].autoload == 1 ? true : false;

      switch (effectType) {

        case 'equirectangular' :
        pannellum.viewer(id, {
          "type": effectType,
          "panorama": imgSrc,
          "autoLoad": autoload,
        });
        break;

        // In progress this options.

        // case 'multires':
        //   pannellum.viewer(id, {
        //     "type": "multires",
        //     "multiRes" : {
        //       "basePath": imgSrc,
        //       "path": "/%l/%s%y_%x",
        //       "fallbackPath": "/fallback/%s",
        //       "extension": "jpg",
        //       "tileResolution": 512,
        //       "maxLevel": 6,
        //       "cubeResolution": 8432
        //      }
        //   });
        //   break;

        // case 'cubemap' :
        //   pannellum.viewer(id, {
        //     "type": effectType,
        //     "cubeMap": [
        //       imgSrc,
        //       imgSrc,
        //       imgSrc,
        //       imgSrc,
        //       imgSrc,
        //       imgSrc
        //       ],
        //     "autoLoad": autoload,
        //   });
        //   break;
        }
      });
    }
  }

})(jQuery, Drupal);
