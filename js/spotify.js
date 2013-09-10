(function($) {

	var settings = {
		url: "http://ws.spotify.com/lookup/1/.json"
	};

	$.fn.spotifyField = function(opts) {

		return this.each(function(){

			var input = $(this);
			var label = input.next(".spotify-response");
			var uri = input.val();
			var uriLength = uri.length;
			var uriSegments = uri.split(":");

			var lookup = function() {

				// API doesn't support playlists... sorry
				if (uriSegments[uriSegments.length-1] == "starred" || uriSegments[uriSegments.length-2] == "playlist")
				{
					updateLabel("Playlist","The Spotify API doesn't support reading playlist data. However, your widget will still work.");
					return false;
				}

				// Add loading spinner
				label.addClass("loading").html("");

				// Perform AJAX request
				var url = settings.url + "?uri=" + uri;
				var request = $.ajax(url);

				// Finished? Remove loading spinner
				request.always(function(data){
					label.removeClass("loading");
				});

				// Success? Generate the label
				request.done(function(data){
					var name, artist, year, text;
					var type = data.info.type;
					switch (type) {
						case "album":
							name = data.album.name;
							artist = data.album.artist;
							year = data.album.released;
							text = artist+" - "+name+" ("+year+")"; 
							break;
						case "artist":
							text = data.artist.name;
							break;
						case "track":
							name = data.track.name;
							artist = data.track.artists[0].name;
							text = artist+" - "+name;
							break;
					}
					updateLabel(type, text);
				});

				// Failed? Show an error
				request.fail(function(){
					label.html("<p>Nothing found.</p>");
				});		
			};

			var updateLabel = function(type, text) {
				function capitalize(str) {
					return str.substr(0, 1).toUpperCase() + str.substr(1);
				}
				label.html("<p><b>"+capitalize(type)+":</b> "+text+"</p>");
			}

			// perform the lookup on keypress
			// but only if the field has actually changed
			input.on("keyup", function(){
				var val = $(this).val();
				if (val.length != uriLength) {
					uri = val;
					uriSegments = uri.split(":");
					lookup();
					uriLength = val.length;
				}
			});

			// also do it on load
			lookup();

		});

	};

}(jQuery));