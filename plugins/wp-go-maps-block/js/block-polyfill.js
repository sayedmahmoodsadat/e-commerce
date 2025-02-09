jQuery(($) => {
    if(!window.WPGMZA){
        return;
    }

    if(!WPGMZA.Standalone){
        WPGMZA.Standalone = {
            Polyfill : {},
        };
    }

    /* Standalone.Polyfill.Core */
    WPGMZA.Standalone.Polyfill.Core = function(){
        this.init();
    }

    WPGMZA.Standalone.Polyfill.Core.prototype.init = function(){
        $(document.body).on('init.wpgmza', (event) => {
            this.onMapLoad(event);
        });
    }

    WPGMZA.Standalone.Polyfill.Core.prototype.onMapLoad = function(event){
        if(event){
            if(event.target && event.target instanceof WPGMZA.Map){
                const options = this.parseOptions(event.target);

                /* Move map and place marker */
                if(options.lat && options.lng){
                    this.panTo(event.target, options.lat, options.lng);
                    const marker = this.placeMarker(event.target, options.lat, options.lng, options.address || false);

                    /* Check if we need to open or disable the info-window */
                    if(marker && options.infowindowmode){
                        if(options.infowindowmode === 'load'){
                            marker.trigger('select');
                        } else if (options.infowindowmode === 'never'){
                            marker.disableInfoWindow = true;
                        }
                    }
                }

                /* Zoom the map */
                if(options.zoom){
                    this.zoomTo(event.target, options.zoom);
                }
            }
        }
    }

    WPGMZA.Standalone.Polyfill.Core.prototype.parseOptions = function(target){
        const options = {};
        if(target && target instanceof WPGMZA.Map && target.element.parentElement){
            const parent = target.element.parentElement;
            for(let attribute of parent.attributes){
                if(attribute.name.indexOf('data-') !== -1){
                    const param = attribute.name.replace('data-', '').replaceAll('-', '');
                    options[param] = attribute.value;
                }
            }
        }
        return options;
    }

    WPGMZA.Standalone.Polyfill.Core.prototype.panTo = function(map, lat, lng){
        if(map && map instanceof WPGMZA.Map){
            if(lat && lng){
                const position = new WPGMZA.LatLng(lat, lng);
                map.setCenter(position);
            }
        }
    }

    WPGMZA.Standalone.Polyfill.Core.prototype.zoomTo = function(map, zoom){
        if(map && map instanceof WPGMZA.Map){
            if(zoom){
                zoom = parseInt(zoom);
                map.setZoom(zoom);
            }
        }
    }

    WPGMZA.Standalone.Polyfill.Core.prototype.placeMarker = function(map, lat, lng, address){
        if(map && map instanceof WPGMZA.Map){
            if(lat && lng){
                address = address ? address : `${lat}, ${lng}`;

                const data = {
                    title : address,
                    address : address,
                    map_id : map.id,
                    lat : lat,
                    lng : lng
                };

                const marker = WPGMZA.Marker.createInstance(data);
                map.addMarker(marker);

                return marker;
            }
        }
        return false;
    }

    /* Init Global */
    WPGMZA.Standalone.polyfill = new WPGMZA.Standalone.Polyfill.Core();
});