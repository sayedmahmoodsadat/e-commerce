jQuery(($) => {
    if(!window.WPGMZA){
        window.WPGMZA = {};
    }

    if(!WPGMZA.Standalone){
        WPGMZA.Standalone = {
            Client : {},
        };
    }

    /* Standalone.Client.Core */
    WPGMZA.Standalone.Client.Core = function(){
        this.init();
    }

    WPGMZA.Standalone.Client.Core.prototype.init = function(){
        this.elements = $('.wpgmza_standalone_map_block');

        this.elements.each((index, element) => {
            element.__wpgmzaStandaloneMap = new WPGMZA.Standalone.Client.Map(element);
        });
    }

    /* Standalone.Client.LatLng */
    WPGMZA.Standalone.Client.LatLng = function(lat, lng){
        this.coords = {
            lat : parseFloat(lat) || 34.020479,
            lng : parseFloat(lng) || -118.4117325
        };
    }

    WPGMZA.Standalone.Client.Core.prototype.init = function(){
        this.elements = $('.wpgmza_standalone_map_block');

        this.elements.each((index, element) => {
            element.__wpgmzaStandaloneMap = new WPGMZA.Standalone.Client.Map(element);
        });
    }

    /* Standalone.Client.Map  */
    WPGMZA.Standalone.Client.Map = function(element){
        this.engineElement = element;
        this.element = $(element);
        this.init();
    }

    WPGMZA.Standalone.Client.Map.prototype.init = function(){
        if(!this.element.hasClass('wpgmza-init')){
            this.config = {
                engine : this.element.data('maps-engine'),
                build : this.element.data('build-engine'),
                icon : this.element.data('icon'),
                lat : this.element.data('lat'),
                lng : this.element.data('lng'),
                address : this.element.data('address'),
                zoom : this.element.data('zoom'),
                infowindowMode : this.element.data('infowindow-mode')
            };

            if(!this.config.address.length){
                this.config.address = `${this.config.lat}, ${this.config.lng}`;
            }

            this.center = new WPGMZA.Standalone.Client.LatLng(this.config.lat, this.config.lng);

            this.render();
            
            this.element.addClass('wpgmza-init');
        }
    }

    WPGMZA.Standalone.Client.Map.prototype.render = function(){
        this.map = false;
        if(this.config.engine === 'google-maps'){
            /* Google Map Init */
            if(typeof google !== 'undefined' && typeof google.maps !== 'undefined'){
                const options = {
                    zoom : this.config.zoom ? parseInt(this.config.zoom) : 4,
                    center : new google.maps.LatLng(this.center.coords),
                    mapId : this.element.attr('id')
                };
                
                this.map = new google.maps.Map(this.engineElement, options);
                this.marker = new WPGMZA.Standalone.Client.Marker(this);
            } else {
                setTimeout(() => {
                    this.render();
                }, 1000);
            }
        } else {
            /* OpenLayers Map Init */
            if(typeof ol !== 'undefined' && typeof ol.layer !== 'undefined'){
                const options = {
                    zoom : this.config.zoom ? parseInt(this.config.zoom) : 4, 
                    center : ol.proj.fromLonLat([this.center.coords.lng, this.center.coords.lat])
                };

                this.map = new ol.Map({
                    target: this.engineElement,
                    layers: [
                        new ol.layer.Tile({ source: new ol.source.OSM({ url : "https://{a-c}.tile.openstreetmap.org/{z}/{x}/{y}.png" }) })
                    ],
                    view: new ol.View(options)
                });

                this.marker = new WPGMZA.Standalone.Client.Marker(this);
            } else {
                setTimeout(() => {
                    this.render();
                }, 1000);
            }
        }
    }

    /* Standalone.Client.Marker  */
    WPGMZA.Standalone.Client.Marker = function(map){
        this.marker = false;
        this.map = map;
        this.position = this.map.center;
        this.infoWindow = false;

        this.init();
    }

    WPGMZA.Standalone.Client.Marker.prototype.init = function(){
        this.infoWindow = new WPGMZA.Standalone.Client.InfoWindow(this);
        if(this.map.config.engine === 'google-maps'){
            /* Google Maps Marker */
            this.marker = new google.maps.marker.AdvancedMarkerElement({
                position : this.position.coords,
                map : this.map.map
            });

            /* Click handler */
            if(this.map.config.infowindowMode !== 'never'){
                google.maps.event.addListener(this.marker, "click", () => {
                    this.infoWindow.open();
                });

                if(this.map.config.infowindowMode === 'load'){
                    /* Auto Open */
                    this.infoWindow.open();
                }
            }
        } else {
            /* OpenLayers Marker */
            const origin = ol.proj.fromLonLat([
                this.position.coords.lng,
                this.position.coords.lat
            ]);

            const icon =  $("<img alt=''/>")[0];
            icon.onload = (event) => {

            };
            icon.src = this.map.config.icon;

            const markerElement =  $("<div class='ol-marker'></div>")[0];
            markerElement.appendChild(icon);

            this.marker = new ol.Overlay({
                element : markerElement,
                position : origin,
                positioning: 'bottom-center',
                stopEvent: false
            });

            this.marker.setPosition(origin);

            this.map.map.addOverlay(this.marker);

            /* Click handler */
            if(this.map.config.infowindowMode !== 'never'){
                $(markerElement).on("click", () => {
                    this.infoWindow.open();
                });

                if(this.map.config.infowindowMode === 'load'){
                    /* Auto Open */
                    this.infoWindow.open();
                }
            }
        }
    }

    /* Standalone.Client.InfoWindow  */
    WPGMZA.Standalone.Client.InfoWindow = function(marker){
        this.marker = marker;
        this.map = this.marker.map;
        this.infoWindow = false;

        this.init();
    }

    WPGMZA.Standalone.Client.InfoWindow.prototype.init = function(){
        const html = `<div>${this.map.config.address}</div>`;
        if(this.map.config.engine === 'google-maps'){
            /* Google info-window */
            this.infoWindow = new google.maps.InfoWindow();
		    this.infoWindow.setContent(html);
        } else {
            /* OpenLayers Info-window */
            this.infoWindow = $(`<div class='wpgmza-infowindow ol-info-window-container ol-info-window-plain'>${html}<div class='ol-infowindow-close'></div></div>`)[0];
        }
    }

    WPGMZA.Standalone.Client.InfoWindow.prototype.open = function(){
        if(typeof google !== 'undefined' && typeof google.maps !== 'undefined' && this.infoWindow instanceof google.maps.InfoWindow){
            /* It is a Google info-window, so we can treat it as one */
            this.infoWindow.open(
                this.map.map,
                this.marker.marker
            );
        } else {
            /* Assume it is an OpenLayers info-window, and treat it accordingly */
            if(this._overlay){
                this.map.map.removeOverlay(this._overlay);
            }
                
            this._overlay = new ol.Overlay({
                element: this.infoWindow,
                stopEvent: true,
                insertFirst: true
            });
            
            const position = this.map.center.coords;
            this._overlay.setPosition(ol.proj.fromLonLat([
                position.lng,
                position.lat
            ]));

            this.map.map.addOverlay(this._overlay);
            
            $(this.infoWindow).show();

            $(this.infoWindow).find('.ol-infowindow-close').on('click', (event) => {
                event.preventDefault();
                this.close();
            });
        }
    }

    WPGMZA.Standalone.Client.InfoWindow.prototype.close = function(){
        if(typeof google !== 'undefined' && typeof google.maps !== 'undefined' && this.infoWindow instanceof google.maps.InfoWindow){
            /* It is a Google info-window, so we can treat it as one */
        } else {
            /* Assume it is an OpenLayers info-window, and treat it accordingly */
            $(this.infoWindow).hide();
        }
    }

    /* Init Global */
    WPGMZA.Standalone.core = new WPGMZA.Standalone.Client.Core();
});