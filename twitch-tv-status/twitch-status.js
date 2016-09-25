jQuery(function() {

var TwitchTVstatusUser = {};
jQuery(".twitchWidget > .user").each(function( index ) {
  TwitchTVstatusUser[index] = jQuery( this ).attr('class').substr(5);
});

jQuery.ajax({ 
  url: script.path,
  data: TwitchTVstatusUser,
  dataType: 'json',
  success: function(channel) { 
    
    for (var key in channel) {
      if (channel.hasOwnProperty(key)) {
        
        if (channel[key] == 0) jQuery('.user.' + key + ' > .symbol').addClass('offline-symbol');
        else jQuery('.user.' + key + ' > .symbol').addClass('online-symbol');
        
      }
    }
                
  },
  error: function() {}
});

});