var map1 = map1 || {}

map1.Map = OpenLayers.Class(OpenLayers.Map,{
     initialize: function(options) {                  
         
        var self = this
         
        var MAP_TILE_URLS = [
            "tiles/${z}/${x}/${y}.jpg"    
        ];

        var DATA_TILE_URLS = 
            "tiles/${z}/${x}/${y}.js.gz"    
        ; 
                
        self.devicePixelRatio = undefined == window.devicePixelRatio  ? 1 : window.devicePixelRatio;
        
        var options = {
            div: "map",
            controls: [],           
            projection: new OpenLayers.Projection("EPSG:900913"),
            displayProjection: new OpenLayers.Projection("EPSG:4326"),            
            center: new OpenLayers.LonLat(14.41,50.083).transform(
                new OpenLayers.Projection("EPSG:4326"),
                new OpenLayers.Projection("EPSG:900913")
            ),  
            maxExtent: new OpenLayers.Bounds(-10, 35, 35, 70).transform(
                new OpenLayers.Projection("EPSG:4326"),
                new OpenLayers.Projection("EPSG:900913")
            ), 
            zoom: 8,  
            tileSize: new OpenLayers.Size(256/self.devicePixelRatio, 256/self.devicePixelRatio)
        }
        OpenLayers.Map.prototype.initialize.apply(this, [options]);
        
        this.locked = false
        
        this.i18n = new map1.I18n()
                
        this.search = new map1.Search(this)        
        
        this.layerMap = new OpenLayers.Layer.XYZ("map", MAP_TILE_URLS, {
            transitionEffect: "resize", 
            buffer: 0, 
            sphericalMercator: true, 
            
            isBaseLayer: true      
        });        
        this.addLayer(this.layerMap)
        
        this.layerUtfgrid = new map1.utfgrid.Layer({
            url: DATA_TILE_URLS            
        });
        this.addLayer(this.layerUtfgrid)
        
        
        this.zoomTo(5)
        
        
        
        this.controlNavigation = new OpenLayers.Control.Navigation({
            dragPanOptions: {
                enableKinetic: true
            }
        })
        this.addControl(this.controlNavigation)
        
        this.controlAttribution = new OpenLayers.Control.Attribution()
        this.addControl(this.controlAttribution)
        
        this.controlPermalink = new OpenLayers.Control.Permalink({anchor: true})
        this.addControl(this.controlPermalink)
        
        
        
        this.controlUtfgridMouseMove = new map1.utfgrid.ControlMouseMove(self,{layers: [self.layerUtfgrid]})
        this.addControl(this.controlUtfgridMouseMove)
        
        this.controlUtfgridClick = new map1.utfgrid.ControlClick(self,{layers: [self.layerUtfgrid]})
        this.addControl(this.controlUtfgridClick)
              
        
        this.searchPanel = new map1.gui.SideBar('#panel-search','#button-start')
        this.sideBar = new map1.gui.SideBar('#sidebar','#button-start, #panel-search',true)
        
                
        var is_touch_device = 'ontouchstart' in document.documentElement;
        
        if ( is_touch_device ) {
            $('#button-start').bind('touchstart',function() { self.sideBar.toggleLockShow(); self.searchPanel.toggleLockShow(); });
        }
        else {        
            $('#button-start').click(function() { self.sideBar.toggleLockShow(); self.searchPanel.toggleLockShow(); $('#button-start').toggleClass('locked'); });           
        }
        
        if ( !is_touch_device ) {
            this.printDialog = new map1.PrintDialog(
                this,
                ['#dialog-print','#dialog-print-2','#dialog-print-3'],
                '.button-print',
                '.dialog-print > .header > .close,.dialog-print > .footer > .close',
                '.dialog-print > .footer > .next',
                '.dialog-print > .footer > .prev',
                '.dialog-print > .footer > .finish'
            );
        }
        else {
           $(".button-print").hide()
        }
        
        this.aboutDialog = new map1.AboutDialog(
            '#dialog-about',
            '.button-about',
            '#dialog-about > .header > .close,#dialog-about > .footer > .close',
            null,
            null,
            null
        );
        
        this.directionsDialog = new map1.DirectionsDialog(
            '#dialog-directions',
            '#button-directions',
            '#dialog-directions > .header > .close,#dialog-directions > .footer > .close',
            null,
            null,
            null
        );
        
                        
        this.routing = new map1.routing.Route(this)                
        
        if ( is_touch_device ) {            
        }
        else {        
            $('#button-navigation').click(function() { self.routing.toggleActivate(); $(this).toggleClass('active'); });
        }
               
         
        
        $(document).bind('keydown', 'ctrl+p', function() {
            self.printDialog.open()
            return false;
        });  
        
        $(document).bind('keydown', '+', function() {            
            self.zoomIn()
        });  
        
        $(document).bind('keydown', '-', function() {
            self.zoomOut()
        });  
        
        $(document).bind('keydown', 'left', function() {
            self.pan(-300,0)
        });
        
        $(document).bind('keydown', 'right', function() {
            self.pan(300,0)
        });
        
        $(document).bind('keydown', 'up', function() {
            self.pan(0,-300)
        });
        
        $(document).bind('keydown', 'down', function() {
            self.pan(0,300)
        });
        $(document).bind('keydown', 'esc', function() {            
            if ( self.routing.active ) {
                $("#button-navigation").click()
            }
        });
        
    },
    
    isValidZoomLevel: function(zoomLevel) {
       return zoomLevel != null && zoomLevel >= 5 && zoomLevel <= 18;
    },        
    
    setMapCenter: function(boundingbox) {
        var bounds = new OpenLayers.Bounds();       
        
        bounds.extend(new OpenLayers.LonLat(boundingbox[2],boundingbox[0]).transform(
            new OpenLayers.Projection("EPSG:4326"),
            new OpenLayers.Projection("EPSG:900913")
        ));
        bounds.extend(new OpenLayers.LonLat(boundingbox[3],boundingbox[1]).transform(
            new OpenLayers.Projection("EPSG:4326"),
            new OpenLayers.Projection("EPSG:900913")
        ));            
        
        //bounds.extend(new OpenLayers.LonLat(boundingbox[0],boundingbox[1]));
        //bounds.extend(new OpenLayers.LonLat(boundingbox[2],boundingbox[3]));
        this.zoomToExtent(bounds)
        return false;
    },
    
    lock: function() {
        $('#map').css('cursor','wait')
        this._locked = true
    },
    
    unlock: function() {
        $('#map').css('cursor','auto')
        this._locked = false
    },
    
    isLocked: function() {
        return this._locked
    }
    
});



