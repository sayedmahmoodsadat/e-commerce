/**
 * Registers the map block and manages all functionality related to that block
 * 
 * @since 1.0.0
 * @for block
*/

(function( blocks, components, i18n, wp) {
	const blockEditor = wp.blockEditor;
	const useBlockProps = blockEditor.useBlockProps;
	const useState = wp.element.useState;

	jQuery(($) => {
        if(!window.WPGMZA){
            window.WPGMZA = {};
        }

        if(!WPGMZA.Standalone){
            WPGMZA.Standalone = {
                instances : {}
            };
        }

		if(wpgmzaStandaloneBlockLocalized) {
			wpgmzaStandaloneBlockLocalized.hasFullPlugin = wpgmzaStandaloneBlockLocalized.hasFullPlugin && wpgmzaStandaloneBlockLocalized.hasFullPlugin === 'true' ? true : false;
		}

		WPGMZA.Standalone.MapBlock = function(){
			blocks.registerBlockType('wpgmza-standalone/map-block', this.getDefinition());
		}

		WPGMZA.Standalone.MapBlock.createInstance = function() {
			return new WPGMZA.Standalone.MapBlock();
		}

		WPGMZA.Standalone.MapBlock.prototype.onEdit = function(props){
			const inspector = this.getInspector(props);
			const preview = this.getPreview(props);
	
			return [
				inspector,
				preview
			];
		}

		WPGMZA.Standalone.MapBlock.prototype.getInspector = function(props){
			const [searching, setSearching] = useState({info : false, timeout : false, list : []});

			let inspector = [];
			if(!!props.isSelected){
				const unitControl = components.UnitControl || components.__experimentalUnitControl;

				let panel = React.createElement(
					wp.blockEditor.InspectorControls,
					{ key: "inspector" },
					React.createElement(
						components.PanelBody,
						{ title: i18n.__('Location', 'wp-go-maps-block') },
						React.createElement(
							components.SearchControl,
							{ 
								label : i18n.__("Address", 'wp-go-maps-block'),
								name : 'address',
								value : props.attributes.address,
								onChange : (value) => {
									/* Search for address */
									if(value.trim().length){
										if(searching.timeout){
											clearTimeout(searching.timeout);
											setSearching({info : searching.info, timeout : false, list : []});
										}

										const timeout = setTimeout(() => {
											$.ajax("https://nominatim.openstreetmap.org/search", {
												data: {
													q: value.trim(),
													format: "json"
												},
												success: (response, xhr, status) => {
													if(response && response instanceof Array && response.length){
														const formatted = []
														for(let i in response){
															if(formatted.length > 3){
																continue;
															}

															const location = response[i];
															if(location.lat && location.lon){
																formatted.push({
																	key : 'location_' + i,
																	address : location.display_name,
																	type : location.addresstype,
																	location : {
																		lat : location.lat,
																		lng : location.lon,
																	}
																});
															}
														}

														if(formatted.length){
															// props.setAttributes({lat : location.lat, lng: location.lon});
															setSearching({info : i18n.__("Suggestions", 'wp-go-maps-block'), timeout : false, list : formatted});
														} else {
															setSearching({info : i18n.__("We could not find this location, please enter lat/lng coordinates manually", 'wp-go-maps-block'), timeout : false, list : []});
														}
													} else {
														setSearching({info : i18n.__("We could not find this location, please enter lat/lng coordinates manually", 'wp-go-maps-block'), timeout : false, list : []});
													}
												},
												error: (response, xhr, status) => {
													setSearching({info : i18n.__("We could not find this location, please enter lat/lng coordinates manually", 'wp-go-maps-block'), timeout : false, list : []});
												}
											});
										}, 1500);

										setSearching({info : i18n.__("Searching...", 'wp-go-maps-block'), timeout : timeout});
									}
									
									props.setAttributes({address : value});
								}
							}
						),
						searching.info ? React.createElement(
							'div', 
							{ className : 'wpgmza-standalone-block-inspector-search-info' + (searching.list ? ' list-mode' : '') }, 
							React.createElement(
								'div', 
								{ className : "wpgmza-standalone-block-inspector-search-info-label" }, 
								searching.info,
								searching.list ? React.createElement(
									'div',
									{ 
										className : "wpgmza-standalone-block-inspector-search-info-close",
										onClick : (event) => {
											setSearching({info : false, timeout : false, list : []});
										}
									}
								) : null
							),
							searching.list ? searching.list.map(
								(location) => {
									return React.createElement(
										'div',
										{ 
											key : location.key, 
											className : 'wpgmza-standalone-block-inspector-search-location',
											onClick : (event) => {
												props.setAttributes({lat : location.location.lat, lng : location.location.lng, address : location.address});
												setSearching({info : false, timeout : false, list : []});
											}
										},
										React.createElement('div', { key : `title_${location.key}`, className : "wpgmza-standalone-block-inspector-search-location-address" }, location.address),
										React.createElement('div', { key : `type_${location.key}`, className : "wpgmza-standalone-block-inspector-search-location-type" }, location.type),
									); 
								}) 
							: null
						) 
						: null,
						React.createElement(
							components.Flex, 
							{},
							React.createElement(
								components.FlexItem, 
								{},
								React.createElement(
									components.TextControl,
									{ 
										label : i18n.__("Latitude", 'wp-go-maps-block'),
										name : 'lat',
										value : props.attributes.lat,
										onChange : (value) => {
											props.setAttributes({lat : value});
										}
									}
								)
							),
							React.createElement(
								components.FlexItem, 
								{},
								React.createElement(
									components.TextControl,
									{ 
										label : i18n.__("Longitude", 'wp-go-maps-block'),
										name : 'lng',
										value : props.attributes.lng,
										onChange : (value) => {
											props.setAttributes({lng : value});
										}
									}
								)
							)
						),
						React.createElement(
							'div',
							{
								className : 'wpgmza-standalone-block-inspector-tip'
							},
							i18n.__("Tip: You can right click in the map preview to move/place your marker", 'wp-go-maps-block')
						),
						React.createElement(
							components.RangeControl, 
							{
								label : i18n.__("Zoom", 'wp-go-maps-block'),
								name : 'zoom',
								min : 1,
								max : 21,
								value : props.attributes.zoom,
								onChange : (value) => {
									props.setAttributes({zoom : value});
								}
							}
						),
						React.createElement(
							components.SelectControl,
							{
								label : i18n.__('Info Window Open', 'wp-go-maps-block'),
								name : 'infowindow_mode',
								value : props.attributes.infowindow_mode,
								options : [
									{
										key: "click",
										value: "click",
										label: i18n.__("On Click", 'wp-go-maps-block')
									},
									{
										key: "load",
										value: "load",
										label: i18n.__("On Load", 'wp-go-maps-block')
									},
									{
										key: "never",
										value: "never",
										label: i18n.__("Never", 'wp-go-maps-block')
									}
								],
								onChange : (value) => {
									props.setAttributes({infowindow_mode : value})
								}
							}
						)
					),
					React.createElement(
						components.PanelBody,
						{ title: i18n.__('Map', 'wp-go-maps-block') },
						React.createElement(
							unitControl, 
							{
								label : i18n.__("Width", 'wp-go-maps-block'), 
								name : 'width', 
								value : props.attributes.width,
								onChange : (value) => {
									props.setAttributes({width : value});
								}
							}
						),
						React.createElement(
							unitControl, 
							{
								label : i18n.__("Height", 'wp-go-maps-block'), 
								name : 'height', 
								value : props.attributes.height,
								onChange : (value) => {
									props.setAttributes({height : value});
								}
							}
						),
						React.createElement(
							components.SelectControl,
							{
								name: "alignment",
								label: i18n.__("Alignment", 'wp-go-maps-block'),
								value: props.attributes.alignment,
								options: [
									{
										key: "left",
										value: "left",
										label: i18n.__("Left", 'wp-go-maps-block')
									},
									{
										key: "center",
										value: "center",
										label: i18n.__("Center", 'wp-go-maps-block')
									},
									{
										key: "right",
										value: "right",
										label: i18n.__("Right", 'wp-go-maps-block')
									}
								],
								onChange : (value) => {
									props.setAttributes({alignment : value});
								}
							}
						)
					),
					React.createElement(
						wp.components.PanelBody,
						{ title: wp.i18n.__('Settings', 'wp-go-maps-block') },
						!wpgmzaStandaloneBlockLocalized.hasFullPlugin ? React.createElement(components.SelectControl, {
							name: "map_engine",
							label: i18n.__("Map Engine", 'wp-go-maps-block'),
							value: props.attributes.map_engine,
							options: this.getMapEngineOptions(),
							onChange : (value) => {
								props.setAttributes({map_engine : value});
							}
						}) : null,
						!wpgmzaStandaloneBlockLocalized.hasFullPlugin && props.attributes.map_engine === 'google-maps' ? React.createElement(
							components.TextControl,
							{ 
								label : i18n.__("Google Maps API Key", 'wp-go-maps-block'),
								name : 'google_maps_api_key',
								value : props.attributes.google_maps_api_key,
								onChange : (value) => {
									props.setAttributes({google_maps_api_key : value});
								}
							}
						) : null,
						!wpgmzaStandaloneBlockLocalized.hasFullPlugin && props.attributes.map_engine === 'google-maps' ? React.createElement(
							"a",
							{ 
								className: "wpgmza-standalone-block-inspector-link",
								href: "https://docs.wpgmaps.com/creating-a-google-maps-api-key",
								target: "_blank"
							},
							i18n.__("Need an API key? Learn how", 'wp-go-maps-block'), 
						) : null,
						wpgmzaStandaloneBlockLocalized.hasFullPlugin ? React.createElement(
							'div',
							{
								className : 'wpgmza-standalone-block-inspector-managed-block'
							},
							(!WPGMZA || !WPGMZA.gutenbergData) ? React.createElement(
								'div',
								{},
								i18n.__("Map settings are managed by your default map in WP Go Maps", 'wp-go-maps-block'),
								React.createElement(
									'a',
									{
										href: wpgmzaStandaloneBlockLocalized.mapListPageLink,
										target: '_BLANK',
										className : 'button button-primary',
									},
									'Edit Map'
								)
							) : null,
							(WPGMZA && WPGMZA.gutenbergData) ? React.createElement(components.SelectControl, 
								{
									name: "id",
									label: i18n.__("Linked Map", 'wp-go-maps-block'),
									value: props.attributes.id,
									options: this.getMapSelectOptions(),
									onChange : (value) => {
										props.setAttributes({id : value});
									}
								}
							) : null
						) : null
					),
					!wpgmzaStandaloneBlockLocalized.hasFullPlugin ? React.createElement(
						wp.components.PanelBody,
						{ title: wp.i18n.__('Need more features?', 'wp-go-maps-block') },
						React.createElement(
							"div",
							{},
							i18n.__("This is the Block version of the plugin. Get the full version for more capabilities.", 'wp-go-maps-block'), 
							React.createElement(
								'ul', 
								{ className : 'wpgmza-standalone-block-inspector-feature-list' }, 
								wpgmzaStandaloneBlockLocalized.fullPluginFeatures.map(
									(feature, index) => {
										return React.createElement(
											'li',
											{
												key : `feature_${index}`
											},
											feature
										);
									}
								)
							),
							React.createElement(
								'a',
								{
									href: wpgmzaStandaloneBlockLocalized.fullPluginLink,
									target: '_BLANK',
									className : 'button button-primary',
									onClick : (event) => {
										event.preventDefault();
										
										/* Open it in an overlay window instead of just a tab, because the layout of that link favors this */
										const dimensions = {
											width : $(window).width() * 0.3,
											height : $(window).height() * 0.8,
											left : ($(window).width() * 0.7) / 2,
											top : ($(window).height() * 0.2) / 2,
										}
										window.open(wpgmzaStandaloneBlockLocalized.fullPluginLink, '_blank', `width=${dimensions.width}, height=${dimensions.height}, top=${dimensions.top}, left=${dimensions.left}`);
									}
								},
								i18n.__('Get Full Plugin', 'wp-go-maps-block')
							)
						)
					) : null
				);
	
				inspector.push(panel);
			}
			return inspector;
		}

		WPGMZA.Standalone.MapBlock.prototype.getPreview = function(props){
			let blockProps = useBlockProps({
				className: props.className + " wpgmza-standalone-block-module", key: 'standalone-map-block-preview',
			});

			const fullPluginNotice = this.getRandomFullPluginNotice();
			
			return React.createElement(
				"div",
				{ ...blockProps },
				React.createElement(
					'div',
					{
						className : 'wpgmza-standalone-block-preview-wrapper' + (props.attributes.alignment ? ` wpgmza-standalone-block-align-${props.attributes.alignment}` : ''),
						style: {height: props.attributes.height || "250px", width: props.attributes.width || "100%"}
					},
					React.createElement(
						"div",
						{ "className": "wpgmza-standalone-block-preview-map", ref : (node) => { this.onRenderMap(props, node) } }
					)
				),
				React.createElement(
					"span",
					{ "className": "wpgmza-standalone-block-preview-note" },
					i18n.__("Map preview is shown using OpenLayers", 'wp-go-maps-block')
				),
				!wpgmzaStandaloneBlockLocalized.hasFullPlugin ? React.createElement(
					'div',
					{ "className": "wpgmza-standalone-block-full-feature-block" },
					React.createElement(
						'img',
						{ "className": "wpgmza-standalone-block-full-feature-block-blinky", "src" : wpgmzaStandaloneBlockLocalized.blinkyIcon}
					),
					React.createElement(
						'div',
						{ "className": "wpgmza-standalone-block-full-feature-block-inner" },
						React.createElement(
							'div',
							{ "className": "wpgmza-standalone-block-full-feature-block-title" },
							fullPluginNotice.title
						),
						React.createElement(
							'div',
							{ "className": "wpgmza-standalone-block-full-feature-block-subtext" },
							fullPluginNotice.subtext || i18n.__('This is the Block version of the plugin. Get the full version for more capabilities.', 'wp-go-maps-block')
						)
					),
					React.createElement(
						'a',
						{
							href: wpgmzaStandaloneBlockLocalized.fullPluginLink,
							target: '_BLANK',
							className : 'button button-primary',
							onClick : (event) => {
								event.preventDefault();
								
								/* Open it in an overlay window instead of just a tab, because the layout of that link favors this */
								const dimensions = {
									width : $(window).width() * 0.3,
									height : $(window).height() * 0.8,
									left : ($(window).width() * 0.7) / 2,
									top : ($(window).height() * 0.2) / 2,
								}
								window.open(wpgmzaStandaloneBlockLocalized.fullPluginLink, '_blank', `width=${dimensions.width}, height=${dimensions.height}, top=${dimensions.top}, left=${dimensions.left}`);
							}
						},
						i18n.__('Get Full Plugin', 'wp-go-maps-block')
					)
				) : null,
				wpgmzaStandaloneBlockLocalized.hasFullPlugin ? React.createElement(
					'div',
					{ "className": "wpgmza-standalone-block-full-feature-manage-notice" },
					i18n.__("Note: Some block settings are managed by WP Go Maps", 'wp-go-maps-block'),
					React.createElement(
						'a',
						{
							href: wpgmzaStandaloneBlockLocalized.mapListPageLink,
							target: '_BLANK',
							className : 'button button-primary',
						},
						i18n.__('Manage Maps', 'wp-go-maps-block')
					)
				) : null
			)
		}
		
		WPGMZA.Standalone.MapBlock.prototype.onRenderMap = function(props, node){
			
			if(typeof ol !== 'undefined' && typeof ol.layer !== 'undefined'){
				if(node){
					const location = {
						lat : props.attributes.lat ? parseFloat(props.attributes.lat) : 34.020479,
						lng : props.attributes.lng ? parseFloat(props.attributes.lng) : -118.4117325,
					}

					if(!node._wpgmzaPreviewMap){
						/* Generate the map */
						node._wpgmzaPreviewMap = new ol.Map({
							target: node,
							layers: [
								new ol.layer.Tile({ source: new ol.source.OSM({ url : "https://{a-c}.tile.openstreetmap.org/{z}/{x}/{y}.png" }) })
							],
							view: new ol.View(
								{
									zoom : props.attributes.zoom ? parseInt(props.attributes.zoom) : 4, 
									center : ol.proj.fromLonLat([location.lng, location.lat])
								}
							)
						});

						node._wpgmzaPreviewMap.getInteractions().forEach(function(interaction) {
							if(interaction instanceof ol.interaction.DoubleClickZoom || interaction instanceof ol.interaction.MouseWheelZoom){
								interaction.setActive(false);
							}
						}, this);

						node._wpgmzaPreviewMap.getView().on("change:resolution", (event) => {
							setTimeout(() => {
								const currentZoom = Math.round( node._wpgmzaPreviewMap.getView().getZoom() );
								props.setAttributes({zoom : currentZoom});
							}, 300);
						});

						$(node).on('click contextmenu', (event) => {
							event.preventDefault();
							event = event.originalEvent;

							let isRight = false;
							if("which" in event){
								isRight = event.which == 3;
							}else if("button" in event){
								isRight = event.button == 2;
							}

							if(isRight){
								const coord = node._wpgmzaPreviewMap.getCoordinateFromPixel([event.offsetX, event.offsetY]);
								if(coord){
									const lonLat = ol.proj.toLonLat(coord);
									const position = {
										lat: lonLat[1],
										lng: lonLat[0]
									};

									props.setAttributes({lat : `${position.lat}`, lng : `${position.lng}`});

									if(!props.attributes.address.trim().length){
										props.setAttributes({address : `${position.lat}, ${position.lng}`});
									}
								}
							}
						});

						if(props.attributes.lat && props.attributes.lng){
							/* Preview Marker */
							const icon =  $("<img alt=''/>")[0];
							icon.onload = (event) => {};
							icon.src = wpgmzaStandaloneBlockLocalized.defaultIcon;
				
							const markerElement =  $("<div class='ol-marker'></div>")[0];
							markerElement.appendChild(icon);
				
							node._wpgmzaPreviewMarker = new ol.Overlay({
								element : markerElement,
								position : ol.proj.fromLonLat([location.lng, location.lat]),
								positioning: 'bottom-center',
								stopEvent: false
							});
				
							node._wpgmzaPreviewMarker.setPosition(ol.proj.fromLonLat([location.lng, location.lat]));
							node._wpgmzaPreviewMap.addOverlay(node._wpgmzaPreviewMarker);
						}

					} else {
						node._wpgmzaPreviewMap.updateSize();

						/* Update the zoom */
						node._wpgmzaPreviewMap.getView().setZoom(parseInt(props.attributes.zoom));

						if(props.attributes.lat && props.attributes.lng){
							/* Update the center point */
							node._wpgmzaPreviewMap.getView().animate(
								{
									center: ol.proj.fromLonLat([location.lng, location.lat]),
									duration: 500
								}
							);

							if(!node._wpgmzaPreviewMarker){
								/* Create the marker first */
								const icon =  $("<img alt=''/>")[0];
								icon.onload = (event) => {};
								icon.src = wpgmzaStandaloneBlockLocalized.defaultIcon;
					
								const markerElement =  $("<div class='ol-marker'></div>")[0];
								markerElement.appendChild(icon);
					
								node._wpgmzaPreviewMarker = new ol.Overlay({
									element : markerElement,
									position : ol.proj.fromLonLat([location.lng, location.lat]),
									positioning: 'bottom-center',
									stopEvent: false
								});
					
								node._wpgmzaPreviewMarker.setPosition(ol.proj.fromLonLat([location.lng, location.lat]));
								node._wpgmzaPreviewMap.addOverlay(node._wpgmzaPreviewMarker);
							} else {
								/* Just move it */
								node._wpgmzaPreviewMarker.setPosition(ol.proj.fromLonLat([location.lng, location.lat]));
							}
						}
					}
				}
			}
		}

		WPGMZA.Standalone.MapBlock.prototype.getDefinition = function(){
			const definition = {
				attributes : this.getAttributes(),
				edit : (props) => {
					return this.onEdit(props);
				},
				save : (props) => { 
					const blockProps = useBlockProps.save();
					return null; 
				}
			};

			if(wpgmzaStandaloneBlockLocalized.hasFullPlugin){
				/* This user has the full plugin installed, so let's re-title the block to something more fitting */
				definition.title = i18n.__("Single Location", 'wp-go-maps-block');
				
				const categories = blocks.getCategories();
				if(categories && categories.length){
					for(let category of categories){
						if(category.slug === 'wpgmza-gutenberg'){
							/* The WP Go Maps Category is present, move the block here */
							definition.category = category.slug;
						}
					}
				}

			}

			return definition;
		}

		WPGMZA.Standalone.MapBlock.prototype.getAttributes = function(){
			return {
				address : {type : 'string', default: ''},
				lat : {type : 'string', default: ''},
				lng : {type : 'string', default: ''},
				zoom : {type : 'int', default : 4},
				width : {type : 'string', default : '100%'},
				height : {type : 'string', default : '400px'},
				alignment : {type : 'string', default : 'center'},
				map_engine : {type : 'string', default: 'open-layers'},
				google_maps_api_key : {type : 'string', default : ''},
				infowindow_mode : {type : 'string', default : 'click'},
				id : {type : 'string', default : '1'}
			}
		}

		WPGMZA.Standalone.MapBlock.prototype.getMapEngineOptions = function(){
			let options =  [
				{
					key: "open-layers",
					value: "open-layers",
					label: i18n.__("OpenLayers", 'wp-go-maps-block')
				},
				{
					key: "google-maps",
					value: "google-maps",
					label: i18n.__("Google Maps (Requires API Key)", 'wp-go-maps-block')
				}
			];
			return options;
		}


		WPGMZA.Standalone.MapBlock.prototype.getMapSelectOptions = function () {
            const result = [];
			if(WPGMZA && WPGMZA.gutenbergData && WPGMZA.gutenbergData.maps){
				WPGMZA.gutenbergData.maps.forEach(function (el) {
					result.push({
						key: el.id,
						value: el.id,
						label: el.map_title + " (ID: " + el.id + ")"
					});
				});
			}

            return result;
        }


		WPGMZA.Standalone.MapBlock.prototype.getGUID = function() { 
			let d = new Date().getTime();
			if (typeof performance !== 'undefined' && typeof performance.now === 'function'){
				d += performance.now(); //use high-precision timer if available
			}

			return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
				let r = (d + Math.random() * 16) % 16 | 0;
				d = Math.floor(d / 16);
				return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
			});
		}

		WPGMZA.Standalone.MapBlock.prototype.getRandomFullPluginNotice = function(){
			const [noticeIndex, setNoticeIndex] = useState(0);
			const [hasNoticeIndex, setHasNoticeIndex] = useState(false);

			if(!hasNoticeIndex){
				setNoticeIndex(Math.floor(Math.random()*wpgmzaStandaloneBlockLocalized.fullPluginNotices.length));
				setHasNoticeIndex(true);
			}

			return wpgmzaStandaloneBlockLocalized.fullPluginNotices[noticeIndex];
		}
	
		/* Init the block */
		WPGMZA.Standalone.instances.mapBlock = WPGMZA.Standalone.MapBlock.createInstance(); 
	});
})(window.wp.blocks, window.wp.components, window.wp.i18n, window.wp);