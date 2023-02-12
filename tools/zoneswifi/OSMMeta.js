/* Copyright (c) 2006-2012 by OpenLayers Contributors (see authors.txt for 
 * full list of contributors). Published under the 2-clause BSD license.
 * See license.txt in the OpenLayers distribution or repository for the
 * full text of the license. */

/**
 * @requires OpenLayers/Format/OSM.js
 */
 
/**  
 * Class: OpenLayers.Format.OSMMeta
 * Extended OSM parser. Adds meta attributes as tags.
 * Create a new instance with the <OpenLayers.Format.OSMMeta> constructor.
 *
 * Inherits from:
 *  - <OpenLayers.Format.OSM>
 */
OpenLayers.Format.OSMMeta = OpenLayers.Class(OpenLayers.Format.OSM, {
    
    // without id, which is already added as osm_id property to feature
    metaAttributes: ['version', 'timestamp', 'uid', 'user', 'changeset'],

    initialize: function(options) {
        OpenLayers.Format.OSM.prototype.initialize.apply(this, [options]);
    },

    getTags: function(dom_node, interesting_tags) {
        var tags = OpenLayers.Format.OSM.prototype.getTags.apply(this, arguments);
        var meta = this.getMetaAttributes(dom_node);
        tags = OpenLayers.Util.extend(tags, meta);
        return tags;
    },
    
    getMetaAttributes: function(dom_node) {
        var meta = {}, name;
        for (var i = 0; i < this.metaAttributes.length; i++) {
            name = this.metaAttributes[i];
            meta[name] = dom_node.getAttribute(name);
        }
        return meta;
    },

    CLASS_NAME: "OpenLayers.Format.OSMMeta" 
});     
