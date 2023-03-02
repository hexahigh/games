//<![CDATA[

// source, version originale : http://overpass-api.de/overpass.js

      OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {
	
        genericUrl: "",
	tolerance: 0.0,
	map: "",
      
	defaultHandlerOptions: {
	  'single': true,
	  'double': false,
	  'pixelTolerance': 0,
	  'stopSingle': false,
	  'stopDouble': false
	},
	
	initialize: function(url, tolerance, map) {
	  this.genericUrl = url;
	  this.tolerance = tolerance;
	  this.map = map;
	
	  this.handlerOptions = OpenLayers.Util.extend(
	    {}, this.defaultHandlerOptions
	  );
	  OpenLayers.Control.prototype.initialize.apply(
	    this, arguments
	  ); 
	  this.handler = new OpenLayers.Handler.Click(
	    this, {
	      'click': this.trigger
	    }, this.handlerOptions
	  );
	}, 
	
	trigger: function(evt) {
	  var lonlat = map.getLonLatFromPixel(evt.xy)
	      .transform(new OpenLayers.Projection("EPSG:900913"),
		         new OpenLayers.Projection("EPSG:4326"));
	      
	  var popup = new OpenLayers.Popup("location_info",
                   new OpenLayers.LonLat(lonlat.lon, lonlat.lat)
		       .transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")),
		   null,
                   "Chargement ...",
	           true);
	  
	  popup.contentSize = new OpenLayers.Size(300, 200);
	  popup.closeOnMove = true;
	  map.addPopup(popup);

	  var rel_tolerance = this.tolerance * map.getScale();
	  if (rel_tolerance > 0.01)
	    rel_tolerance = 0.01;

		var request = OpenLayers.Request.GET({
	      url: this.genericUrl + "&bbox="
	          + (lonlat.lon - rel_tolerance) + "," + (lonlat.lat - rel_tolerance) + ","
	          + (lonlat.lon + rel_tolerance) + "," + (lonlat.lat + rel_tolerance),
	      async: false
          });
	  
	  map.removePopup(popup);
	  popup.contentHTML = request.responseText;
	  map.addPopup(popup);
	}
	
      });

// ----------------------------------------------------------------------------
      
      function setStatusText(text)
      {
	  var html_node = document.getElementById("statusline");
	  if (html_node != null)
	  {
            var div = html_node.firstChild;
            div.deleteData(0, div.nodeValue.length);
            div.appendData(text);
	  }
      }

      var zoom_valid = true;
      var load_counter = 0;      
      
      function make_features_added_closure(layer) {
	  var layer_ = layer;
          return function(evt) {
	      setStatusText("Displaying " + layer_.features.length + " features ...");
          };
      }

      ZoomLimitedBBOXStrategy = OpenLayers.Class(OpenLayers.Strategy.BBOX, {

          zoom_data_limit: 13,

          initialize: function(zoom_data_limit) {
              this.zoom_data_limit = zoom_data_limit;
          },

          update: function(options) {
	      this.ratio = this.layer.ratio;
              var mapBounds = this.getMapBounds();
              if (this.layer && this.layer.map && this.layer.map.getZoom() < this.zoom_data_limit) {
                  setStatusText("Svp, faire un zoom pour voir les données détaillées.");
                  zoom_valid = false;

                  this.bounds = null;
              }
              else if (mapBounds !== null && ((options && options.force) ||
                                         this.invalidBounds(mapBounds))) {
                  ++load_counter;
                  setStatusText("Chargement des données ...");
                  zoom_valid = true;

                  this.calculateBounds(mapBounds);
                  this.resolution = this.layer.map.getResolution();
                  this.triggerRead(options);
              }
          },

          CLASS_NAME: "ZoomLimitedBBOXStrategy"
      });
      
      OSMTimeoutFormat = OpenLayers.Class(OpenLayers.Format.OSM, {
	
	  initialize: function(strategy) {
	      this.strategy = strategy;
	  },
	
	  read: function(doc) {
	      var feat_list = OpenLayers.Format.OSM.prototype.read.apply(this, [doc]);
	      
              if (typeof doc == "string") {
		  doc = OpenLayers.Format.XML.prototype.read.apply(this, [doc]);
	      }
	      
	      if (this.strategy)
	      {
		  var node_list = doc.getElementsByTagName("remark");
		  if (node_list.length > 0)
		  {
		      setStatusText("Svp, faire un zoom pour voir les données détaillées.");
		      this.strategy.bounds = null;
		  }
	      }
	      
	      return feat_list;
	  },
	  
	  strategy: null,

          CLASS_NAME: "OSMTimeoutFormat"
      });

      function make_large_layer(data_url, desc, color, zoom, stylemap_layer) {

		var strategy = new ZoomLimitedBBOXStrategy(zoom);
        var layer = new OpenLayers.Layer.Vector(desc, {
              strategies: [strategy, new OpenLayers.Strategy.Cluster()],
              protocol: new OpenLayers.Protocol.HTTP({
                  url: data_url,
                  format: new OSMTimeoutFormat(strategy)
              }),
                styleMap: stylemap_layer,
              projection: new OpenLayers.Projection("EPSG:4326"),
              ratio: 1.0
          });

        layer.events.register("featuresadded", layer, make_features_added_closure(layer));

        return layer;
      }

        function onPopupClose(evt) {
            select.unselectAll();
        }
 
      function make_layer(data_url, desc, color,zoom, stylemap_layer) {
	  return make_large_layer(data_url, desc, color, zoom, stylemap_layer);
      }

//]]>	  