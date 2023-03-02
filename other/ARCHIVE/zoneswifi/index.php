<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /><meta name="referrer" content="origin">
<title>WIFIzones : Map of free Wifi HotSpots</title>
<meta name="viewport" content="width=device-width" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="author" content="Pierre Béland">
<meta name="description" content="This map locates the free Wifi HotSpot zones from OpenStreetMap data (OSM)." />
<meta name="referrer" content="origin">
<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
<script src="jquery.min.js"></script>
<script src="bootstrap.min.js"></script>
<script src="OpenLayers.js" type="text/javascript"></script>
<!--
  <script src="http://openlayers.org/api/2.13/Geolocation.js"></script>
  <script type="application/javascript" src="http://openlayers.org/api/2.13/OpenLayers.mobile.js"></script>
-->
<script type="application/javascript" src="fr.js"></script>
<script type="application/javascript" src="OpenStreetMap.js"></script>
<script type="application/javascript" src="overpass.js"></script>
<script type="application/javascript" src="OSMMeta.js"></script>
<script type="application/javascript" src="LoadingPanel.js"></script>
<script type="application/javascript">
//<![CDATA[
var isMobile = navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry)/);
//OpenLayers.ImgPath = "../img/";
// traduction
var lang="en";
// debug console.log("lang="+lang);
var self="/zoneswifi/index.php";
msg_wifi_point=" Wifi Points - Zoom-in to locate these Wifi points.";
points_wifi="Wifi HotSpots";
// debug console.log("debut Js lang="+lang);
var markers = new OpenLayers.Layer.Markers( "Markers" );
var POI = new OpenLayers.Layer.Vector("Loc.");


var geoloc = null;
//var lat = 45.5222;
//var lon = -73.7186;
//var zoom = 10;
// départ - selection wifi pour sherbrooke
//selection_wifi_lonlat(lon=-72.1527,lat=45.3965,zoom=10);
// départ - Geoloc, sinon selection wifi pour quebec
var lon=-71.1598; var lat=46.8226;var zoom=12;var startZoom=zoom;
//getLocation();
// debug console.log("Apres getLocation lat="+lat+" lon="+lon);

var url = window.location.href
// debug console.log("url="+url);
var uri_hash = window.location.hash
// debug console.log("hash="+uri_hash);
var uriHash = uri_hash.replace(/^#/, "") //.split(";");
var debug= "debug, uriHash = " +uriHash;
var kvPair = uriHash.split("/");
if (kvPair.length>2) {
startZoom = kvPair[0];
zoom=startZoom;
lat = kvPair[1];
lon = kvPair[2];
// debug console.log("Uri="+kvPair);
// debug console.log("Init", startZoom, lat, lon);
}
var  LonLat = new OpenLayers.LonLat( lon,lat).transform(new OpenLayers.Projection("EPSG:4326"),"EPSG:900913");
var url_lonlat;
var map;
var fenetre_popup;
// controle affichage des marqueurs
// nb dans cercles à partir de zoom=13
var zoom_nb=3;
// icones plus gros et plus clair à partir de zoom=16
var zoom_icone_plus=5;
var wifi_region;
var largeur_popup,hauteur_popup;
// panneau glissant gauche affiché  au départ
var showpanel=0;

if (document.body)
{
var largeur_ecran = (document.body.clientWidth);
var hauteur_ecran = (document.body.clientHeight);
} 	
if (largeur_ecran<=640) {
	largeur_popup=250;hauteur_popup=150;
	}
	else {largeur_popup=300;hauteur_popup=200;
	}	
	
    function fenetre_popup_info() {
		var fenetre_popup=window.open("zoneswifi_info.html",
		"pop1","width=200,height=200");
		fenetre_popup.document.close();
		fenetre_popup.focus();
		onblur="window.focus()";
    }
	
function sel_lang (form) {
    $lang = form.inputbox.value;
    alert ("lang= " + $lang);
}	
	
// apres recherche nominatimm, - demande extraction selon lon,lat,zoom --> bbox calculé
function selection_wifi_lonlat(lon,lat,zoom) {

	// debug console.log("sel_wifi lon,lat,zoom "+lon+", "+lat+",",+zoom);
	// ferme fenêtre de résultats Nominatim;
	cname_result=document.getElementById('result').className
	document.getElementById('result').className='hidden';
	zoom=zoom-10;if (zoom<0) {zoom=0;}
	for (nb in map.layers) {
		if (nb > 0) {
			// debug console.log("enlève couche wifi précédente, no "+nb);
			map.layers[nb].setVisibility(false);
			map.removeLayer(map.layers[nb], false);
		}
	}
    var lonlat_reg = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
    map.setCenter (lonlat_reg, zoom);
    //bounds.left, .bottom, .right, .top
    var bounds = new OpenLayers.Bounds();
	bounds=map.getExtent();
	var WGS84 = new OpenLayers.Projection("EPSG:4326");
	bounds.transform(map.getProjectionObject(), WGS84);
	var bbox=String(bounds.bottom.toFixed(5)+","+bounds.left.toFixed(5)+","+bounds.top.toFixed(5)+","+bounds.right.toFixed(5));
	// debug console.log("bounds "+ bounds+" -> l "+bounds.left+",b "+bounds.bottom+",r "+bounds.right+",t "+bounds.top);
	// debug console.log("bbox "+ bbox);
	var url1="http://overpass-api.de/api/interpreter?data=[timeout:30];node[internet_access=wlan](";
	var url3=");out+meta;(way[internet_access=wlan](";
	var url5=");node(w););out+meta;";
	url_lonlat=url1+bbox+url3+bbox+url5;
	// debug console.log("url_lonlat "+url_lonlat);
	//----------	
	wifi_layer_request_hotspots (couche=wifi_region,
	desc="Points Wifi", url=url_lonlat, affichage=true);
	
}

function lien(nom) {
 var url ="Erreur - fonction lien";
 if (nom=="osm") {
	url="http://www.openstreetmap.org/?lat="+lat+"&lon="+lon+"&zoom="+zoom;}
 else if (nom=="osm_copyright") {
	url="http://www.openstreetmap.org/copyright";}
 else if (nom=="nominatim_mapquest") {
	url="http://www.mapquest.com";}
 
 window.open(url," ","left=20;width=90%;height=90%");
 }
 
function afficher_contribuez() {
	var contribuez=document.getElementById("contribuez");
	// debug console.log("contribuez="+contribuez.className);
	if (contribuez.className=="hidden") {contribuez.className="cadre l100";}
	else {contribuez.className="hidden";}
	// debug console.log("contribuez=>"+contribuez.className);
}
		
function icone(feature,nb) {
	img="img/wifi_32.png";
	// img for  operators Mtl ile-sans-fil,zap,etc. 
	// (first version of the software was to show Mtl - Quebec operators)
	wifi_operator=getAttrib(feature.cluster[nb].attributes,"internet_access:operator").toLowerCase();
	wifi_operator=wifi_operator.replace(/î/gi, "i");
	wifi_operator=wifi_operator.replace(/é/gi, "e");
	wifi_operator=wifi_operator.replace(/-/g, " ");
	if (wifi_operator == "ile sans fil")
		{img="img/wifi_ilesansfil.png";}
    else if (wifi_operator.indexOf("zap") ==0) {
		img="img/wifi_zap.png"; 
	}
// // debug console.log(wifi_operator+' img='+img)
return img;
}
var style_wifi_couche = new OpenLayers.Style({
	pointRadius: "${radius}",
	label:" ${nombre}",
	fontColor: "${couleur}",
	fontSize: "${police}",
	fillColor: "# 033FF",
	fillOpacity: "${fillOpacity}",
	strokeColor: "#330099",
	strokeWidth: "${strokeWidth}",
	strokeOpacity: 1,
	graphicWidth: 32,
	graphicHeigth: 32,
	externalGraphic: "${externalGraphic}",
	graphicWidth: "${graphicWidth}"	
},
{
	context: {
		externalGraphic:  function(feature) {
			zoom=map.getZoom();
			// debug console.log("img zoom="+zoom+" zoom_nb="+zoom_nb);
			if (feature.attributes.count==1 &&zoom>=zoom_nb) {
			for (elem in feature.attributes)
				// debug console.log("externalgraphiq attrib elem="+elem);
			//for (elem in feature) console.log("externalgraphiq feature elem="+elem);
			//console.log("externalgraphiq attributes ="+feature.attributes);
			//// debug console.log("externalgraphiq layer ="+feature.layer);
			//// debug console.log("externalgraphiq lonlat ="+feature.lonlat);
			//// debug console.log("externalgraphiq data ="+feature.data);
			//// debug console.log("externalgraphiq geometry ="+feature.geometry);

			img=icone(feature,0);
			//if (zoom>zoom_icone_plus && img=="img/wifi_bleu.png") {img="img/wifi_bleu_48.png";}
			}
			else {img="";}
			// console.log(wifi_operator+' img='+img)
			return img;
		},
		graphicWidth: function(feature) {
			zoom=map.getZoom();
			//echelle=map.getScale()
			//console.log("Dimension graphique zoom="+zoom+" zoom_icone_plus="+zoom_icone_plus);
			if (zoom>zoom_icone_plus) {return 48;}
			else {return 24;} 
		},
		graphicXOffset: function(feature) {
		var XOffset = 7;
		var YOffset = -20;
		var Resolution = map.getResolution();
			XOffset= XOffset * Resolution / map.getResolution();
			// debug console.log("y zoom="+zoom+" XOffset="+XOffset);
            return XOffset
		},
		graphicYOffset: function(feature) {
			var XOffset = 7;
			var YOffset = -20;
			var Resolution = map.getResolution();
			YOffset= YOffset * Resolution / map.getResolution();
			// debug console.log("y zoom="+zoom+" YOffset="+YOffset);
            return YOffset
		},
		fillOpacity:  function(feature) {
			zoom=map.getZoom();
			if (feature.attributes.count==1 &&zoom>=zoom_nb) {
				//console.log("fill zoom="+zoom);
				if (zoom>zoom_icone_plus) {return 0.8;}
				else {return 1;}
			}
			else {return 0.3;}
		},
		radius: function(feature) {
			zoom=map.getZoom();
			nb=Math.floor(feature.attributes.count*2/5);
			rayon=Math.min(nb, 12 )+10;
			if (zoom<zoom_nb) {rayon=rayon/1.2}
			else if (zoom<zoom_icone_plus) {rayon=rayon/1.1}
			return rayon;
		},
		strokeWidth: function(feature) {
			zoom=map.getZoom();
			if (zoom<zoom_nb) {return 1;}
			else {return 2;}
		},	
		nombre: function(feature) {
			zoom=map.getZoom();
			var nb=feature.attributes.count;
			//blanc=String.fromCharCode("\\u00A0");
			blanc=".";
			if (nb==1 || zoom<zoom_nb) {return blanc;}
			else {return nb;}
		},	
		couleur: function(feature) {
			zoom=map.getZoom();
			var nb=feature.attributes.count;
			if (nb==1 || zoom<zoom_nb) {return "blue";}
			else {return "navy";}
		},	
		police: function(feature) {
			zoom=map.getZoom();
			var nb=feature.attributes.count;
			if (nb==1 || zoom<zoom_nb) {return "1px";}
			else {return "1em";}
		}	
	}
});


var styleMap_wifi_couche = new OpenLayers.StyleMap({
	"default": style_wifi_couche,
	"select": {
		fillColor: "#8aeeef",
		strokeColor: "#32a8a9",
		fillOpacity: 0.6
	}
});

var texte,operator,building,amenity,shop,leisure,tourism,cuisine,adresse, 
	 addr_no, addr_address,addr_city,addr_suburb,addr_postcode,tel,
	 wifi_operator,wifi_ssid;

var selectedfeature, 
    popup, 
    field;
		
//++++++++++++++++++++++++++++++++++++++++++++++++++++++
	
function getAttrib(variable,cle) {
	if (variable.hasOwnProperty(cle)) {
		valeur=variable[cle];
	}
	else {
	valeur="";
		}
	return valeur;
}
	
function onLoadEnd  () {
    // debug console.log("Points Wifi, Download complete");
	InfoLoadingPanel.deactivate();
	InfoLoadingPanel.destroy();
}	

function featureHighlighted(evt) {
	var feature = evt.feature;
	nb_objets=feature.cluster.length
	if (nb_objets>1) {
		texte = "<div>"+nb_objets+" Points Wifi</div>";
	}
	else {
		name=getAttrib(feature.cluster[0].attributes,"name");
		texte=name;
		}
	// debug console.log("highlight texte="+texte);
    hover = new OpenLayers.Popup.FramedCloud("Popup",
        new OpenLayers.LonLat(5.6, 50.6),
        null,
        "<div>"+texte+"</div>",
        null,
        false);
    //map.addPopup(hover);
}

function featureunhighlighted(evt) {
	hover.hide();
}

// Usefull for interaction - not to visualize data
function onPopupClose(evt) {
	// 'this' is the popup.
	var feature = this.feature;
	if (feature.layer) { // Cet objet n'est pas détruit
		selector.unselect(feature);
	} else { // After "moveend" or "refresh" events on POIs layer all 
			 //     features have been destroyed by the Strategy.BBOX
		this.destroy();
	}
}

function onFeatureUnselect(evt) {
	var feature = evt.feature;
	if (feature.popup) {
		map.removePopup(feature.popup);
		feature.popup.destroy();
		feature.popup = null;
	}
}

function onFeatureSelect(event) {

	feature=event.feature;
    selectedfeature = feature;

	attribs="Attribs";
	for (var cl in feature.cluster) {
		for (var cle in feature.cluster[cl].attributes) {
		  attribs+="<br/>"+ cle + "="+feature.cluster[cl].attributes[cle];
		}
	}
	nb_objets=feature.cluster.length
	if (nb_objets>1) {
		texte = "<div>"+nb_objets+ msg_wifi_point+"</div><div class=\"lieu\">";
		titre = +nb_objets+" msg_wifi_point";
	}
	else {texte="";titre=points_wifi;}
	for (var nb = 0; nb < nb_objets; nb++) {
	
		img=icone(feature,nb);
		name=getAttrib(feature.cluster[nb].attributes,"name");
		highway=getAttrib(feature.cluster[nb].attributes,"highway");
		operator=getAttrib(feature.cluster[nb].attributes,"operator");
		amenity=getAttrib(feature.cluster[nb].attributes,"amenity");
		building=getAttrib(feature.cluster[nb].attributes,"building");
		shop=getAttrib(feature.cluster[nb].attributes,"shop");
		leisure=getAttrib(feature.cluster[nb].attributes,"leisure");
		tourism=getAttrib(feature.cluster[nb].attributes,"tourism");
		cuisine=getAttrib(feature.cluster[nb].attributes,"cuisine");
		tel=getAttrib(feature.cluster[nb].attributes,"phone");
		wifi_operator=getAttrib(feature.cluster[nb].attributes,"internet_access:operator");
		wifi_ssid=getAttrib(feature.cluster[nb].attributes,"internet_access:ssid");
		addr_no=getAttrib(feature.cluster[nb].attributes,"addr:housenumber");
		addr_street=getAttrib(feature.cluster[nb].attributes,"addr:street");
		addr_city=getAttrib(feature.cluster[nb].attributes,"addr:city");
		addr_suburb=getAttrib(feature.cluster[nb].attributes,"addr:suburb");
		addr_postcode=getAttrib(feature.cluster[nb].attributes,"addr:postcode");

		if (name=="") {
			if (operator!="") {
				name=operator;
			}
			else {name="Unknown facility";}
		}

		if (lang=="fr")
		{
			if (amenity!="") {
				if (amenity.toLowerCase()=="library") {amenity="Bibliothèque";}
				else if (amenity.toLowerCase()=="townhall") {amenity="Hotel de ville";}
				else if (amenity.toLowerCase()=="community_centre") {amenity="Centre communautaire";}
				else if (amenity.toLowerCase()=="social_centre") {amenity="Centre de services sociaux";}
				else if (amenity.toLowerCase()=="cafe") {amenity="Café";}
				else if (amenity.toLowerCase()=="place_of_worship") {amenity="Lieux de culte";}
				else if (amenity.toLowerCase()=="hospital") {amenity="Hopital";}
				else if (amenity.toLowerCase()=="pharmacy") {amenity="Pharmacie";}
				else if (amenity.toLowerCase()=="clinic") {amenity="Clinique médicale";}
				else if (amenity.toLowerCase()=="doctors") {amenity="Médecins";}
				else if (amenity.toLowerCase()=="post_office") {amenity="Poste";}
				else if (amenity.toLowerCase()=="marina") {amenity="Marina";}
				else if (amenity.toLowerCase()=="fast_food") {amenity="Resto Rapide";}
				else if (amenity.toLowerCase()=="ice_cream") {amenity="Crème Glacée";}
				}
			if (shop!="") {
				if (shop.toLowerCase()=="supermarket") {shop="Supermarché";}
				else if (shop.toLowerCase()=="mall") {shop="Centre commercial";}
				else if (shop.toLowerCase()=="department_store") {shop="Magasin";}
				else if (shop.toLowerCase()=="clothes") {shop="Vêtements";}
				else if (shop.toLowerCase()=="stationery") {shop="Articles de bureau";}
				else if (shop.toLowerCase()=="bakery") {shop="Boulangerie";}
			}
			if (leisure!="") {
				if (leisure.toLowerCase()=="park") {leisure="Parc";}
				else if (leisure.toLowerCase()=="sports_centre") {leisure="Centre sportif";}
				else if (leisure.toLowerCase()=="marina") {leisure="Marina";}
				else if (leisure.toLowerCase()=="cinema") {leisure="Cinéma";}
			}
			if (tourism!="") {
				if (tourism.toLowerCase()=="camp_site") {tourism="Camping";}
				else if (tourism.toLowerCase()=="hotel") {tourism="Hotel";}
				else if (tourism.toLowerCase()=="hostel") {tourism="Auberge";}
				else if (tourism.toLowerCase()=="guest_house") {tourism="Chambre d'hôte";}
			}
			if (highway!="") {
				if (highway.toLowerCase()=="rest_area") {highway="Halte routière";}
			}
			if (building!="") {
				if (building.toLowerCase()=="hospital") {building="Hopital";}
				else if (building.toLowerCase()=="school") {building="École";}
				else if (building.toLowerCase()=="public_building") {amenity="Immeuble public";}
			}

			if (cuisine!="") {
				// trad
			}
		}	
		
		if (tel!="") {
			if (tel.indexOf("+")<0) {tel="+"+tel;}

	}

	type_osm="";
	if (amenity!="") {
		type_osm=amenity
		}
		else if (shop!="") {
			type_osm=shop;
		}
		else if (leisure!="") {
			type_osm=leisure;
		}
		else if (tourism!="") {
			type_osm=tourism;
		}
		else if (highway!="") {
			type_osm=highway;
		}
		else if (building!="yes") {
			type_osm=building;
		}

	adresse="";
	if (addr_no != "") {adresse+=addr_no +", ";}
	if (addr_street != "") {adresse+=addr_street;}
	if (addr_suburb != "") {adresse+="<br/>"+addr_suburb;}
	if (addr_city != "") {adresse+="<br/>"+addr_city;}
	if (addr_postcode != "") {adresse+=" "+addr_postcode;}
	if (tel != "") {adresse+="<br/>"+tel;}

	wifi="";
	if (wifi_operator != "") {wifi+="<em>Opérateur Wifi</em>: "+wifi_operator+"<br/>";}
	if (wifi_ssid != "") {wifi+="SSID: "+wifi_ssid;}
	if (wifi!="") {wifi="<div>"+wifi+"</div>";}

	if (nb_objets>5) {
		if (nb<16) {texte += "<h3 class=\"popup2\">"+name+" &nbsp; <em>"+type_osm+"</em></h3>";}
		else if (nb==16) {texte+="<h3 class=\"popup\"> ... </h3>"} 
	}
	else {
		texte += "<div class=\"lieu\">\n<img class=\"gauche\" src=\""+img+"\"/> <h3 class=\"popup\">"+name+"<br/><br/></h3><div class=\"sautgauche\">"+type_osm+"</div>";
		texte+="<div>"+adresse+"</div><div class=\"wifi\">"+wifi+"</div>\n</div>\n";
	}
		// console.log("texte "+texte);
	}
	if (nb_objets>1) {
		texte +="</div>\n";
		titre = nb_objets+msg_wifi_point;
	}
	// console.log("texte "+texte);
	
    popup = new OpenLayers.Popup.FramedCloud(
        "wifiPopup",
        feature.geometry.getBounds().getCenterLonLat(),
        new OpenLayers.Size(largeur_popup,hauteur_popup),
        texte,
        null,
        true,onPopupClose
    );
	feature.popup = popup;
	popup.feature = feature;
	popup.autoSize = false;
	map.addPopup(popup, true);
	
}

function wifi_layer_request_hotspots (couche,desc,url_couche,affichage) {
	// url - Overpass-API Service, dynamic query to base OSM
	// debug console.log("fonction wifi_layer_request_hotspots desc="+desc+", url_couche="+url_couche);
    couche = new OpenLayers.Layer.Vector(desc, {
	strategies: [new OpenLayers.Strategy.Fixed(), new OpenLayers.Strategy.Cluster() /*,new OpenLayers.Strategy.BBOX({resFactor: 1.1})*/ ],
        styleMap: styleMap_wifi_couche,
        protocol: new OpenLayers.Protocol.HTTP({
            url: url_couche,
            format: new OpenLayers.Format.OSMMeta()
        }),
		forceFixedZoomLevel: true, numZoomLevels: null, MinZoom: 10, MaxZoom: 17,
        projection: new OpenLayers.Projection("EPSG:4326")
    });

	couche.visibility= true;
    map.addLayer(couche);
	// debug console.log("fonction wifi_layer_request_hotspots - couche wifi ajoutée");
    //controls      
    selector = new OpenLayers.Control.SelectFeature(couche);
    map.addControl(selector);
    selector.activate();

	couche.events.on({
            "featureselected": onFeatureSelect,
            "featureunselected": onFeatureUnselect,
			"loadend": onLoadEnd
        });

	var highlightCtrl = new OpenLayers.Control.SelectFeature(couche, {
			hover: true,
			highlightOnly: true,
			renderIntent: "temporary",
			eventListeners: {
				featurehighlighted: featureHighlighted,
				featureunhighlighted: featureunhighlighted
			}
		});

}
  
function req_localiser () {
// Recenter on user position
navigator.geolocation.getCurrentPosition(function(position) {       
    //   document.getElementById('info').innerHTML
       //     = " Latitude: " + 
       //         position.coords.latitude + 
       //       " Longitude: " +
       //         position.coords.longitude;

        lat=position.coords.latitude;
        lon=position.coords.longitude;
		if (zoom<10) {zoom=zoom+10;}
		if (zoom<12) {zoom=12;}
		if (zoom>17) {zoom=17;}
        console.log("Geoloc Latitude: " + 
                position.coords.latitude + 
              " Longitude: " +
                position.coords.longitude);
        lonLat = new OpenLayers.LonLat(position.coords.longitude,
        position.coords.latitude)
        .transform(
            new OpenLayers.Projection("EPSG:4326"), //transform from WGS 1984
            map.getProjectionObject() //to Spherical Mercator Projection
            );
	//	map.setCenter(lonLat, zoom);	 

		var loc_feature = new OpenLayers.Feature.Vector(
				new OpenLayers.Geometry.Point( lon, lat  ).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject()),
				{description:"My Location"} ,
				{externalGraphic: '../ol2/img/marker.png', graphicHeight: 25, graphicWidth: 21, graphicXOffset:-12, graphicYOffset:-25  }
			);    
		
		selection_wifi_lonlat(lon=lon,lat=lat,zoom=zoom);
		POI = new OpenLayers.Layer.Vector("Loc.");
		POI.addFeatures(loc_feature);	
		map.addLayer(POI);
	
	});
}
	
function init_map(){

	
    var options = {
		projection: new OpenLayers.Projection("EPSG:900913"),
		displayProjection: new OpenLayers.Projection("EPSG:4326"),
		numZoomLevels: 20,
		controls: [
				new OpenLayers.Control.Navigation(),
				new OpenLayers.Control.ZoomPanel(),
				new OpenLayers.Control.ZoomIn(),
				new OpenLayers.Control.ZoomOut(),
				new OpenLayers.Control.ScaleLine(),
				new OpenLayers.Control.KeyboardDefaults(),
				new OpenLayers.Control.Attribution({
				div: document.getElementById('map_attribution') }),
				new OpenLayers.Control.Scale()
		]
	};
	
	OpenLayers.Lang.setCode("en");
	OpenLayers.ProxyHost = "proxy-overpass.php?url=";

	map = new OpenLayers.Map({
		div:"map",
		projection: new OpenLayers.Projection("EPSG:900913"),
		displayProjection: new OpenLayers.Projection("EPSG:4326"),
		numZoomLevels: 20,
		controls: [
				new OpenLayers.Control.Navigation(),
				new OpenLayers.Control.ZoomIn(),
				new OpenLayers.Control.ZoomOut(),
				new OpenLayers.Control.ScaleLine(),
				new OpenLayers.Control.KeyboardDefaults(),
				new OpenLayers.Control.Attribution({
				div: document.getElementById('map_attribution') }),
				new OpenLayers.Control.Scale()
		]
	}	);	  
	map.numZoomLevels = null;
    layerMapnik = new OpenLayers.Layer.OSM.Mapnik("OpenStreetMap",
		{attribution: "&copy; <a href=\'http://www.openstreetmap.org/copyright\'>OpenStreetMap contributors</a>",
	zoomOffset:10,
	minZoomLevel: 10,
	resolutions: [152.87405654907226, 76.4370282714844,38.2185141357422,19.1092570678711,9.55462853393555,4.77731426696777,2.38865713348389,1.19432856674194]
	});
    map.addLayer(layerMapnik);
	InfoLoadingPanel=new OpenLayers.Control.LoadingPanel();
	map.addControl(InfoLoadingPanel); 
	// gold();
	
	map.addLayer(markers);
	var lonLat;

	// Minimize server request - minimum zoom at 12 
	if (zoom<12) {zoom=12;}
	selection_wifi_lonlat(lon=lon,lat=lat,zoom=zoom);					

	function handleZoom(event) {
		var map = event.object;
		resolution=map.getResolution();
		echelle=map.getScale();
		minzoom=map.getMinZoom();
		// debug console.log("movestart zoom="+map.getZoom()+" resolution="+resolution+" echelle="+echelle+" minzoom="+minzoom)
		if (map.getZoom() < 11) {
			OpenLayers.Event.stop(event);
	 }
	}
	
	var updateUrlHash = function () {
		//this is bound to the map, so:
		//var zoom = this.getZoom();
		var zoom = map.getZoom();
		zoom=zoom+10;
		//zoomChanged();
		// debug console.log("upU zoom", zoom);
		var lonLat = this.getCenter().transform("EPSG:900913", "EPSG:4326")
		var lat=lonLat.lat.toFixed(6);
		var lon=lonLat.lon.toFixed(6);
		window.location.hash = zoom + '/' + lat + '/' + lon;
	}

	var zoomChanged = function ()
	{
	  var zoom = map.getZoom();
	  // debug console.log("zoomChanged", zoom);
	}
	
	//register the moveend event on the map (also catches zoomend)
	map.events.register('moveend', map, updateUrlHash);
	map.events.register("zoomend", map, zoomChanged);
	
	map.events.register('movestart', map, handleZoom)

    }  // fin init_map


	// ************ NOMINATIM change your country code for language localisation
	//  var lang="ar" , "en" or "fr" modified by html language selection button;
       var lang="en";
	
	
   
	/*========================================================================
	  NOMINATIM SEARCH functions
	  source of functions http://wiki.openstreetmap.org/wiki/User:SunCobalt/OpenLayers_Suche
	  ========================================================================
	*/
	  
    function fragmapquest(){

// ************ change your country code for language localisation
	//  var lang="ar" , "en" or "fr" modified by html language selection button;
	var urlnominatim="http://nominatim.openstreetmap.org/search.php";
	var urlmapquest="http://pierzen.dev.openstreetmap.org/hot/openlayers/nominatim/mapquestjs.php";
	   search_query=document.getElementById("nominatim_query").value;
	   exclude_place_ids="state,region,administrative";
       url=urlmapquest+"?q="+search_query+"&limit=8"+"&lang="+lang;
	   // debug console.log("Nominatim, url="+url);
       var http = new XMLHttpRequest();
       http.open("GET",url,false);    
       http.send(null);
       nominatim_line=http.responseText.split("\n");
       resultdiv = document.getElementById("result");
	   resultdiv.className="displayblock";
	   if (lang=="fr")
	   {
		   i18n_info_enter_locality_above="Spécifiez une localité ci-dessus, et appuyez sur le bouton Rechercher.";
		   i18n_info_no_search_results_for="Aucun résultat pour";
		   i18n_info_search_results_for="Résultats de recherche pour";
	   }
	   else
	   {
		   i18n_info_enter_locality_above="Spécify a locality above and click on the Search button.";
		   i18n_info_no_search_results_for="No result for ";
		   i18n_info_search_results_for="Search results for ";
	   }
	   result_close="<a id='result_close' class='right_panel' href='#' onclick=\"document.getElementById('result').className='hidden';\" title='Fermer le panneau des Résultats'><button class='btn small'><img src='img/close.png' class='right_panel' /></button></a><br />";
	   resultdiv.innerHTML=result_close+resultdiv.innerHTML;		 
	   
	   if (search_query.length==0) {
	    msg_search_results=i18n_info_enter_locality_above;
		 resultdiv.innerHTML=result_close+msg_search_results;		 
		}
	   else  {
		msg_search_results=i18n_info_no_search_results_for+" \""+search_query+"\"<br /><ul>";
       if(nominatim_line.length<=3){
        resultdiv.innerHTML="<br />"+msg_search_results+" \""+search_query+"\"";
		resultdiv.innerHTML=result_close+resultdiv.innerHTML;		 
       }else{
	    search_results_for=i18n_info_search_results_for;
        resultdiv.innerHTML=search_results_for +" \""+search_query+"\"";
        i=0;
        for(i=0;i<nominatim_line.length;i++){
         nominatim_col=nominatim_line[i].split("\t");
		 for (col in nominatim_col) {
			console.log("Nominatim col "+col + ", " +nominatim_col[col]);
		}
         if((nominatim_col[0]*nominatim_col[0]>0)||(nominatim_col[1]*nominatim_col[1]>0)){
          if(i==0){selection_wifi_lonlat(nominatim_col[0],nominatim_col[1]),11;}
          displaytext=nominatim_col[2];
          resultdiv.innerHTML=resultdiv.innerHTML+"<font size=2><li><a href=# onmouseup=\"selection_wifi_lonlat("+nominatim_col[0]+","+nominatim_col[1]+",11);\" onclick='//vectorLayer.visibility=false;'>"+displaytext+"</a></li><br>"; 
          }
         }
         resultdiv.innerHTML=resultdiv.innerHTML+"</ul>";
		 resultdiv.innerHTML=result_close+resultdiv.innerHTML;		 

        }
		}
        return false;
       } 
	   
	/*========================================================================
	    NOMINATIM SEARCH functions end
	  ========================================================================*/

//]]>	  
</script>
<link rel="stylesheet" href="style.css" type="text/css">
<style type="text/css">

	html, body {
	width:100%; height: 100%;
	}
	body {
        margin: 0.1em;
        padding: 0;
		font-family: Verdana,Arial,Helvetica,sans-serif;
		font-size:2em;
  }
	header {width:100%; height:6%;  line-height:5%;padding-top:0.6%;
		  font-family:"Comic Sans MS",  Times, serif; color:beige;
		background-color: #202080;
	  }
	#sect2 {float:left;margin-righ:2em; line-height:100%; color:white; font-size: 1em; padding-left:0.5em; clear:right;}
	div.olPopupCloseBox
	{
	  background-image: url("img/close.png");
	  background-color: rgba(255, 0, 255, 0.4);
	}
	 .l25{
        width : 24.9%;
	 }
	 .l75{
        width : 74.9%;
	 }
 .l90{
	width:90%;
	margin:0.5em;
 }
 .l100{
	width:99%;
 }
 .hidden{
 	display:none;
 }
 #section_info {
		font-size:85%;
	position:absolute; left:+5%; top:+15%; width:55%;
	z-index:2;
  }
    #section_principale {
		float: right;
		clear: right;
		z-index:0;
    }
	#map {
		width:100%;height:90%;  
		clear:both; overflow:hidden;
		background-color:#3F99AA;
	}	
	#legende {
        width : 40%;
		float: left;
		background-color:#EEEAEE;
	}
	#legende img {
		height:22px;
	}
	#naviguer {
		clear:right;
	}
	a.blanc
	  { height:100%; color:#eaeade; text-decoration:none; font-size:0.8em;
	  }
    footer {
		bottom: 0;
		width:100%; height: 5%;
		background-color: #202080;
		color:white;
        font-family: Verdana;
		font-size: 95%;
		display: block;
      }	 
	#credit
	  {float:left;width:16%; color:white;
		  text-align:left; padding-left:1.2em;
		  display: block;
		  margin:0.3em;
		  font-size:1em;
	  }
	#options {
		float: right;
		margin-right:8%;
    }
	#map_attribution {
		float: right;
		margin-right:3%;
		padding-left:2em;padding-right:2em;
		color:#daeade;
    }
	.ico {width:1.4em;}
	 #section_info {
			font-size:95%;
		position:absolute; left:+5%; top:+15%; width:65%;
		z-index:2;
	  }
	.logos {
		text-align:center;height:60px;
	}
	.gauche {
		float: left;
    }	
	img.gauche {
		width:18px; margin:0.4em;
    }
	.sautgauche {
			clear: left;
    }	.bleu {
		color:navy; font-size:1.1em;
	}
	.infos {color:navy;background:white;font-size:80%;}
	hr.popup {
		color:#0099CC;
		background-color:#0099CC;
	}
	#sect2 h1 {
		font-size:1.2em;text-align:center;color:white;
		line-height:1.3em;margin:0;padding:0;
		display:inline;
	}
	#search_panel form h1 {
		display:inline;color:white;
		font-family: cursive, fantasy, verdana;
		font-style:italic;font-size:1.15em;margin:0.2em;
		}
	#contribuez {
		margin: 0.4em ;
		padding-top 2em;
	}
	#naviguer h1, #contribuez  h1 {
		text-align:left
		font-size:1.2em;color:purple;
		line-height:1.3em;margin:0;padding:0;
	}
	#localiser
	  { float:left;line-height:100%;
		margin-right:4em;
	  }
	a#localiser
	  { height:100%; color:#ffdade; text-decoration:none; font-size:0.9em;
	  }
	a#localiser:hover
	  { color:#dadeff;
	  }
	h2 {
		font-size:1.2em;
	}
	.lieu {
		border: 2px solid #3399FF;
		border-radius: 0.6em;
		margin:0.1em;padding:0.2em;
		font-size:0.9em;
		font-weight:normal;
	}
	h3.popup {
		clear:right;
		color:navy;
		background-color:#ccddcc;
		margin-top:0.3em;
		margin-bottom:0.3em;
		font-size:1.05em;
	}
	h3.popup2 {
		 font-size:0.8em;
	 }
	h4.popup {
		background-color:#DADEDE;
	}
	.gras {font-weight:bold;}
	.centre {text-align:center;}

.olControlLoadingPanel {
	background-image:url(img/loading.gif);
	position: relative;
	width: 110%;
	height:110%;
	background-position:center;
	background-repeat:no-repeat;
	display: none; 
}
.olControlAttribution
{
  font-size: .8em;
  right: 0;
  bottom: 0;
  padding: 0.3em 2em 0.3em 2em;
  background-color: rgba(17, 51, 51, 0.8);
}
select#lang {color:#99aa99;}
div#credit a, div#map_attribution a
	  { height:100%; color:#bbcccc; text-decoration:none;
	  }
div#credit a:hover, div#credit a:focus, div#map_attribution a:hover, div#map_attribution a:focus
	  { color:white;
	  }	  	
#sect1 {
	float:left;
	margin-left:35%;
	line-height:100%;
	}
#search_panel {
	float : left; 
	position:relative;
/*	width:65%;
	height:1.7em; */
	text-align:left;
	font-size:85%;
  }
#search_panel img, #search_panel form, #search_panel fieldset {
	display:inline;
  }
#result {
	border: 2px #039 solid;
	background-color:#fff8c6;
	color:#039;
	padding-bottom:0.3em;
  }
$('.bloc_contribuez').click(afficher_contribuez());
/*$('.bloc_contribuez').click(function(){
	document.getElementById("contribuez").className="l100";
});*/
 .btnsearch{
  background:#fef url(img/search.jpg) no-repeat left top;margin-left:0.2em;padding:0.3em;text-decoration:none;
}
.arrondi {
    width: 120px;
    height: 50px;
    border: 2px solid #000;
    -webkit-border-radius: 12px / 24px;
    -moz-border-radius:    12px / 24px; 
    border-radius:         12px / 24px; 
}
.cercle {
    width: 120px;
    height:160px;
	font-size:140%;
    border: 2px solid #000;
    -webkit-border-radius: 50px / 80px;
    -moz-border-radius:    10px / 80px; 
    border-radius:         100px / 80px; 
}
.ovale {
    width: 200px;
    height: 320px;
    background: #9a4;
    -webkit-border-radius: 100px / 160px;
    -moz-border-radius:    100px / 160px; 
    border-radius:         100px / 160px; 
}

.btn.error{background-color:#c43c35;background-repeat:repeat-x;background-image:-moz-linear-gradient(top, #ee5f5b, #c43c35);background-image:-ms-linear-gradient(top, #ee5f5b, #c43c35);background-image:-webkit-gradient(linear, left top, left bottom, color-stop(0%, #ee5f5b), color-stop(100%, #c43c35));background-image:-webkit-linear-gradient(top, #ee5f5b, #c43c35);background-image:-o-linear-gradient(top, #ee5f5b, #c43c35);background-image:linear-gradient(top, #ee5f5b, #c43c35);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ee5f5b', endColorstr='#c43c35', GradientType=0);text-shadow:0 -1px 0 rgba(0, 0, 0, 0.25);border-color:#c43c35 #c43c35 #882a25;border-color:rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);}
.btn.success{background-color:#57a957;background-repeat:repeat-x;background-image:-khtml-gradient(linear, left top, left bottom, from(#62c462), to(#57a957));background-image:-moz-linear-gradient(top, #62c462, #57a957);background-image:-ms-linear-gradient(top, #62c462, #57a957);background-image:-webkit-gradient(linear, left top, left bottom, color-stop(0%, #62c462), color-stop(100%, #57a957));background-image:-webkit-linear-gradient(top, #62c462, #57a957);background-image:-o-linear-gradient(top, #62c462, #57a957);background-image:linear-gradient(top, #62c462, #57a957);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#62c462', endColorstr='#57a957', GradientType=0);text-shadow:0 -1px 0 rgba(0, 0, 0, 0.25);border-color:#57a957 #57a957 #3d773d;border-color:rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);}
.btn:hover,.btn.primary{background-color:#339bb9;background-repeat:repeat-x;background-image:-khtml-gradient(linear, left top, left bottom, from(#5bc0de), to(#339bb9));background-image:-moz-linear-gradient(top, #5bc0de, #339bb9);background-image:-ms-linear-gradient(top, #5bc0de, #339bb9);background-image:-webkit-gradient(linear, left top, left bottom, color-stop(0%, #5bc0de), color-stop(100%, #339bb9));background-image:-webkit-linear-gradient(top, #5bc0de, #339bb9);background-image:-o-linear-gradient(top, #5bc0de, #339bb9);background-image:linear-gradient(top, #5bc0de, #339bb9);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#5bc0de', endColorstr='#339bb9', GradientType=0);text-shadow:0 -1px 0 rgba(0, 0, 0, 0.25);border-color:#339bb9 #339bb9 #22697d;border-color:rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);}
.btn.info{text-align:left;color:#ffffff;background-color:#0064cd;background-repeat:repeat-x;background-image:-khtml-gradient(linear, left top, left bottom, from(#049cdb), to(#0064cd));background-image:-moz-linear-gradient(top, #049cdb, #0064cd);background-image:-ms-linear-gradient(top, #049cdb, #0064cd);background-image:-webkit-gradient(linear, left top, left bottom, color-stop(0%, #049cdb), color-stop(100%, #0064cd));background-image:-webkit-linear-gradient(top, #049cdb, #0064cd);background-image:-o-linear-gradient(top, #049cdb, #0064cd);background-image:linear-gradient(top, #049cdb, #0064cd);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#049cdb', endColorstr='#0064cd', GradientType=0);text-shadow:0 -1px 0 rgba(0, 0, 0, 0.25);border-color:#0064cd #0064cd #003f81;border-color:rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);}
.btn{box-shadow:2px 2px 3px dimgray inset;
cursor:pointer;display:inline-block;background-color:#e6e6e6;background-repeat:no-repeat;background-image:-webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), color-stop(25%, #ffffff), to(#e6e6e6));background-image:-webkit-linear-gradient(#ffffff, #ffffff 25%, #e6e6e6);background-image:-moz-linear-gradient(top, #ffffff, #ffffff 25%, #e6e6e6);background-image:-ms-linear-gradient(#ffffff, #ffffff 25%, #e6e6e6);background-image:-o-linear-gradient(#ffffff, #ffffff 25%, #e6e6e6);background-image:linear-gradient(#ffffff, #ffffff 25%, #e6e6e6);padding:5px 14px 6px;text-shadow:0 1px 1px rgba(255, 255, 255, 0.75);color:#333;font-size:13px;line-height:normal;border:1px solid #ccc;border-bottom-color:#bbb;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;-webkit-box-shadow:inset 0 1px 0 rgba(255, 255, 255, 0.2),0 1px 2px rgba(0, 0, 0, 0.05);-moz-box-shadow:inset 0 1px 0 rgba(255, 255, 255, 0.2),0 1px 2px rgba(0, 0, 0, 0.05);box-shadow:inset 0 1px 0 rgba(255, 255, 255, 0.2),0 1px 2px rgba(0, 0, 0, 0.05);-webkit-transition:0.1s linear all;-moz-transition:0.1s linear all;-ms-transition:0.1s linear all;-o-transition:0.1s linear all;transition:0.1s linear all;}.btn{background-position:0 -15px;color:#333;text-decoration:none;}
.btn:focus{outline:1px dotted #666;}
.btn.active,.btn:active{-webkit-box-shadow:inset 0 2px 4px rgba(0, 0, 0, 0.25),0 1px 2px rgba(0, 0, 0, 0.05);-moz-box-shadow:inset 0 2px 4px rgba(0, 0, 0, 0.25),0 1px 2px rgba(0, 0, 0, 0.05);box-shadow:inset 0 2px 4px rgba(0, 0, 0, 0.25),0 1px 2px rgba(0, 0, 0, 0.05);}
.btn.disabled{cursor:default;background-image:none;filter:progid:DXImageTransform.Microsoft.gradient(enabled = false);filter:alpha(opacity=65);-khtml-opacity:0.65;-moz-opacity:0.65;opacity:0.65;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;}
.btn[disabled]{cursor:default;background-image:none;filter:progid:DXImageTransform.Microsoft.gradient(enabled = false);filter:alpha(opacity=65);-khtml-opacity:0.65;-moz-opacity:0.65;opacity:0.65;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;}
.btn.large{font-size:15px;line-height:normal;padding:9px 14px 9px;-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;}
.btn.small{padding:3px 5px 3px;font-size:12px;}
button.btn::-moz-focus-inner,input[type=submit].btn::-moz-focus-inner{padding:0;border:0;}

input#nominatim	{
	padding-left: 0.1em; padding-right: 0.1em;
	 overflow: hidden; background-color:#FFF8C6;
	 font-size:90%;
}
input#nominatim:focus {
	background:white;
}		
input#nominatim_query.keyboardInput{
	line-height:1.2em;
	color:#366;
	border: 0.1em solid red;
    -webkit-border-radius: 0.3em;
    -moz-border-radius:    0.3em; 
    border-radius:         0.3em; 
}
input#nominatim_query.keyboardInput:placeholder-shown {
	color:#69A;
	border: none;
}
/* ---------- Small devices Style ---------- */
@media screen and (max-width:640px) {
	#contribuez, #legende {
            width : 95%;
	}
	#naviguer {
            width : 95%;
	}
	h1 {
		font-size:1.05em;
	}
	.lieu {
		font-size:0.95em;
	}
	h3.popup {
		clear:right;
		color:navy;
		background-color:#ccddcc;
		margin-top:0.3em;
		margin-bottom:0.3em;
		font-size:1.05em;
	}
	h3.popup2 {
		 font-size:1em;
	 }
	.ligne {
		 display:inline;
	 }
}		
</style> 
<link rel="stylesheet" href="bootstrap.min.css">
</head>
<body onload="init_map()">

	<header>	
	<div id="section_principale" class="l100">
		<div id="sect1">
			<a id="localiser"  href="#" onclick="javascript:req_localiser();return false;" title="Show your localisation">Locate me</a>
		</div>
		<div id="sect2">
			<h1> Wifi Zones </h1> &nbsp; 
		</div>
			<div id="search_panel">
			<form action="" method="get" onsubmit="fragmapquest();return false;">
				<span id="nominatim_query_input" class="cadre">
				<input type="text" vki_attached="true" required id="nominatim_query" class="keyboardInput" title="ie :&#10; &#10;Roma, Italia&#10;Tour Eiffel, Paris&#10;Taj Mahal, India" 
				placeholder="Search ..." name="q" size="33" lang="en" type="search"> 
				</span>
				<button class="btn small" type="submit"><img src="img/nominatim-search.png" alt="" value="point" style="height:16px;">
				Search</button> &nbsp; 
			 </form>
		&nbsp; &nbsp;

		<form name="langue" action="/zoneswifi/index.php" method="post">
			<select name="lang" id="lang"  onchange='this.form.submit()'>
				<option value="fr"  >Fr</option>
				<option value="en"  selected  >En</option>
			</select>
			<input type="hidden" name="zoom" id="zoom" value="<br />
<b>Warning</b>:  Undefined variable $zoom in <b>/home/pierzen/public_html/zoneswifi/index.php</b> on line <b>1274</b><br />
" />
			<input type="hidden" name="lat" id="lat" value="<br />
<b>Warning</b>:  Undefined variable $lat in <b>/home/pierzen/public_html/zoneswifi/index.php</b> on line <b>1275</b><br />
" />
			<input type="hidden" name="lon" id="lon" value="<br />
<b>Warning</b>:  Undefined variable $lon in <b>/home/pierzen/public_html/zoneswifi/index.php</b> on line <b>1276</b><br />
" />
		</form>
			</div>
			<div class="clearboth" style="clear:both;"></div>
		</div>		
		</div>
		</header>
	<div id="message"></div>
	<div id="result" class="hidden"> Hidden</div>
	<div id="map" style="height:89%;">
	</div>
	<footer>
	<div id="credit">
	<a href='https://openlayers.org/'><img class="ico" src="../img/openlayers_logo70.png"/></a>
	<a title='Application by' href='https://twitter.com/pierzen'>pierzen</a>
	</div>
	<div id="map_attribution">
	</div>
	<div id="options">
    <button type="button" style="font-size:90%;" class="btn btn-mini infos" data-toggle="modal" data-target="#mInfos" onclick="window.location.href='#mInfos'">Infos</button>
	</div>
	</footer>

	<div class="modal fade" id="mInfos" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
		<div id="naviguer">
		<h3>Infos</h3>
		
	<p>On this Map, the <em class="bleu">Wifi HotSpots</em> icons let's locate the free Wifi HotSpots. Click on the icons to show the description of each facility.
	</p>
	<p> Search a town and the Wifi HotSpots will be located for the zone. From this point,
	zoom-in to see the various Wifi HotSpots and the description of esch facillity.
	 
		</div>

		<div id="contribuez">
			<h3>Contributions</h3>
			
		<p>Wifi HotSpots	: Service Overpass, ©&nbsp; 
		<a href="#" onclick="lien("osm_copyright");return false;">OpenStreetMap ODbL</a></p>
		<p>Localities : Nominatim Search, courtesy of 
		<a href="#" onclick="lien("nominatim_mapquest");return false;">MapQuest</a>.</p>
	 
		</div>		
		</div>
        </div>
      </div>		
	</div>
</body>
</html>