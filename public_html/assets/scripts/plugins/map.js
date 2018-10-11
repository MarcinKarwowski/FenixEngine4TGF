/* Vars */
var centreLat = '57';
var centreLon = '35';
var initialZoom = 3;
var imageWraps = false; //SET THIS TO false TO PREVENT THE IMAGE WRAPPING AROUND
var arrMeter = [];
var inputname = 'coords';

var poly, map, jArray; //the GMap3 itself
var gmicMapType;
var markers = [];
var clickMarkers = [];
var path = new google.maps.MVCArray;
var objCache = new Object();
objCache.points = [];

/* Fullscreen */
function FullScreenControl(map, enterFull, exitFull) {
    if (enterFull === void 0) { enterFull = null; }
    if (exitFull === void 0) { exitFull = null; }
    if (enterFull == null) {
        enterFull = "Full screen";
    }
    if (exitFull == null) {
        exitFull = "Exit full screen";
    }
    var controlDiv = document.createElement("div");
    controlDiv.className = "fullScreen";
    controlDiv.index = 1;
    controlDiv.style.padding = "5px";
    controlDiv.style.left = "80px";
    controlDiv.style.top = "";
    controlDiv.style.width = "150px";
    controlDiv.style.height = "20px";
    // Set CSS for the control border.
    var controlUI = document.createElement("div");
    controlUI.style.backgroundColor = "white";
    controlUI.style.borderStyle = "solid";
    controlUI.style.borderWidth = "1px";
    controlUI.style.borderColor = "#717b87";
    controlUI.style.cursor = "pointer";
    controlUI.style.textAlign = "center";
    controlUI.style.boxShadow = "rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px";
    controlDiv.appendChild(controlUI);
    // Set CSS for the control interior.
    var controlText = document.createElement("div");
    controlText.style.fontFamily = "Roboto,Arial,sans-serif";
    controlText.style.fontSize = "10px";
    controlText.style.fontWeight = "400";
    controlText.style.color = "#000000";
    controlText.style.paddingTop = "1px";
    controlText.style.paddingBottom = "1px";
    controlText.style.paddingLeft = "6px";
    controlText.style.paddingRight = "6px";
    controlText.innerHTML = "<strong>" + enterFull + "</strong>";
    controlUI.appendChild(controlText);
    // set print CSS so the control is hidden
    var head = document.getElementsByTagName("head")[0];
    var newStyle = document.createElement("style");
    newStyle.setAttribute("type", "text/css");
    newStyle.setAttribute("media", "print");
    var cssText = ".fullScreen { display: none;}";
    var texNode = document.createTextNode(cssText);
    try {
        newStyle.appendChild(texNode);
    }
    catch (e) {
        // IE8 hack
        newStyle.styleSheet.cssText = cssText;
    }
    head.appendChild(newStyle);
    var fullScreen = false;
    var interval;
    var mapDiv = map.getDiv();
    var divStyle = mapDiv.style;
    if (mapDiv.runtimeStyle) {
        divStyle = mapDiv.runtimeStyle;
    }
    var originalPos = divStyle.position;
    var originalWidth = divStyle.width;
    var originalHeight = divStyle.height;
    // IE8 hack
    if (originalWidth === "") {
        originalWidth = mapDiv.style.width;
    }
    if (originalHeight === "") {
        originalHeight = mapDiv.style.height;
    }
    var originalTop = divStyle.top;
    var originalLeft = divStyle.left;
    var originalZIndex = divStyle.zIndex;
    var bodyStyle = document.body.style;
    if (document.body.runtimeStyle) {
        bodyStyle = document.body.runtimeStyle;
    }
    var originalOverflow = bodyStyle.overflow;
    controlDiv.goFullScreen = function () {
        var center = map.getCenter();
        mapDiv.style.position = "fixed";
        mapDiv.style.width = "100%";
        mapDiv.style.height = "100%";
        mapDiv.style.top = "0";
        mapDiv.style.left = "0";
        mapDiv.style.zIndex = "10000";
        document.body.style.overflow = "hidden";
        controlText.innerHTML = "<strong>" + exitFull + "</strong>";
        fullScreen = true;
        google.maps.event.trigger(map, "resize");
        map.setCenter(center);
        // this works around street view causing the map to disappear, which is caused by Google Maps setting the
        // CSS position back to relative. There is no event triggered when Street View is shown hence the use of setInterval
        interval = setInterval(function () {
            if (mapDiv.style.position !== "fixed") {
                mapDiv.style.position = "fixed";
                google.maps.event.trigger(map, "resize");
            }
        }, 100);
    };
    controlDiv.exitFullScreen = function () {
        var center = map.getCenter();
        if (originalPos === "") {
            mapDiv.style.position = "relative";
        }
        else {
            mapDiv.style.position = originalPos;
        }
        mapDiv.style.width = originalWidth;
        mapDiv.style.height = originalHeight;
        mapDiv.style.top = originalTop;
        mapDiv.style.left = originalLeft;
        mapDiv.style.zIndex = originalZIndex;
        document.body.style.overflow = originalOverflow;
        controlText.innerHTML = "<strong>" + enterFull + "</strong>";
        fullScreen = false;
        google.maps.event.trigger(map, "resize");
        map.setCenter(center);
        clearInterval(interval);
    };
    // Setup the click event listener
    google.maps.event.addDomListener(controlUI, "click", function () {
        if (!fullScreen) {
            controlDiv.goFullScreen();
        }
        else {
            controlDiv.exitFullScreen();
        }
    });
    return controlDiv;
}

/* Distances */
function mercatorInterpolate(latLngFrom, latLngTo) {
    // Get projected points
    var projection = map.getProjection();
    var pointFrom = projection.fromLatLngToPoint(latLngFrom);
    var pointTo = projection.fromLatLngToPoint(latLngTo);
    // Calculate distance
    return Math.ceil((Math.sqrt(Math.pow((pointTo.x - pointFrom.x), 2) + Math.pow((pointTo.y - pointFrom.y), 2))) * 2.8);
}

/* Map */
function GMICMapType() {
    this.Cache = Array();
    this.opacity = 1.0;
}
GMICMapType.prototype.tileSize = new google.maps.Size(256, 256);
GMICMapType.prototype.maxZoom = 19;
GMICMapType.prototype.getTile = function(coord, zoom, ownerDocument) {
    var c = Math.pow(2, zoom);
    var tilex=coord.x,tiley=coord.y;
    if (imageWraps) {
        if (tilex<0) tilex=c+tilex%c;
        if (tilex>=c) tilex=tilex%c;
        if (tiley<0) tiley=c+tiley%c;
        if (tiley>=c) tiley=tiley%c;
    }
    else {
        if ((tilex<0)||(tilex>=c)||(tiley<0)||(tiley>=c))
        {
            var blank = ownerDocument.createElement("DIV");
            blank.style.width = this.tileSize.width + "px";
            blank.style.height = this.tileSize.height + "px";
            return blank;
        }
    }
    var img = ownerDocument.createElement("IMG");
    var d = tilex;
    var e = tiley;
    var f = "t";
    for (var g = 0; g < zoom; g++) {
        c /= 2;
        if (e < c) {
            if (d < c) { f += "q" }
            else { f += "r"; d -= c }
        }
        else {
            if (d < c) { f += "t"; e -= c }
            else { f += "s"; d -= c; e -= c }
        }
    }
    img.id = "t_" + f;
    img.style.width = this.tileSize.width + "px";
    img.style.height = this.tileSize.height + "px";
    img.src = "/assets/static/map/"+f+".jpg";
    this.Cache.push(img);
    return img;
}
GMICMapType.prototype.realeaseTile = function(tile) {
    var idx = this.Cache.indexOf(tile);
    if(idx!=-1) this.Cache.splice(idx, 1);
    tile=null;
}
GMICMapType.prototype.name = "Mapa";
GMICMapType.prototype.alt = "Mapa";
GMICMapType.prototype.setOpacity = function(newOpacity) {
    this.opacity = newOpacity;
    for (var i = 0; i < this.Cache.length; i++) {
        this.Cache[i].style.opacity = newOpacity; //mozilla
        this.Cache[i].style.filter = "alpha(opacity=" + newOpacity * 100 + ")"; //ie
    }
}

/* Resize map div */
function getWindowHeight() {
    if (window.self&&self.innerHeight) {
        return self.innerHeight;
    }
    if (document.documentElement&&document.documentElement.clientHeight) {
        return document.documentElement.clientHeight;
    }
    return 0;
}

function resizeMapDiv() {
    //Resize the height of the div containing the map.

    //Do not call any map methods here as the resize is called before the map is created.
    var d=document.getElementById("map");

    var offsetTop=0;
    for (var elem=d; elem!=null; elem=elem.offsetParent) {
        offsetTop+=elem.offsetTop;

    }
    var height=getWindowHeight()-offsetTop-16;

    if (height>=0) {
        d.style.height=height+"px";
    }
}

/* Addition functions */
function addPolygon (points, jArray)
{
    var opacity = (jArray.color == "#DBE0E6" ? 0.15 : 0.30);
    var strokeopacity = (jArray.color == "#DBE0E6" ? 0.4 : 0.80);
    var polys = new google.maps.Polygon({
        paths: points,
        strokeWeight: 2,
        strokeOpacity: strokeopacity,
        strokeColor: "#6d6d6d",
        fillColor: jArray.color,
        fillOpacity: opacity
    });
    google.maps.event.addListener(polys, "mouseover", function(e) {
        $("#poly_name").html((jArray.type == "CONTENER" ? "Kraj: " : "")+jArray.name);
        polys.setOptions({strokeColor: "red"});
    });
    google.maps.event.addListener(polys, "mouseout", function() {
        $("#poly_name").html("");
        polys.setOptions({strokeColor: "#6d6d6d"});
    });
    google.maps.event.addListener(polys, "click", addPoint);
    polys.setMap(map);
}
function addMarker(point, name, lid, pointIcon)
{
    var image = new google.maps.MarkerImage(
        "/assets/static/images/mapicons/"+pointIcon,
        new google.maps.Size(32,37),
        new google.maps.Point(0,0),
        new google.maps.Point(15,34)
    );
    var shape = {
        coord: [22,0,24,1,26,2,27,3,28,4,29,5,30,6,31,7,31,8,32,9,32,10,33,11,33,12,33,13,33,14,33,15,33,16,33,17,33,18,33,19,33,20,33,21,33,22,33,23,32,24,32,25,33,26,33,27,31,28,29,29,28,30,28,31,26,32,25,33,24,34,24,35,23,36,22,37,22,38,22,39,21,40,21,41,21,42,20,43,20,44,20,45,20,46,20,47,19,48,19,49,19,50,19,51,19,52,18,53,18,54,18,55,18,56,18,57,18,58,18,59,16,59,16,58,16,57,16,56,16,55,16,54,15,53,15,52,15,51,15,50,15,49,14,48,14,47,14,46,14,45,14,44,13,43,13,42,13,41,13,40,12,39,12,38,11,37,11,36,10,35,10,34,9,33,8,32,6,31,6,30,4,29,2,28,1,27,1,26,2,25,1,24,1,23,0,22,0,21,0,20,0,19,0,18,0,17,0,16,0,15,0,14,0,13,0,12,0,11,1,10,1,9,2,8,2,7,3,6,4,5,5,4,6,3,7,2,9,1,11,0,22,0],
        type: "poly"
    };
    var marker = new google.maps.Marker({
        position: point,
        map: map,
        draggable: false,
        icon: image,
        //shadow: shadow,
        shape: shape
    });
    google.maps.event.addListener(marker, "mouseover", function(e) {
        $("#point_coords .miles").html(name);
    });
    google.maps.event.addListener(marker, "mouseout", function() {
        $("#point_coords .miles").html("");
    });
    google.maps.event.addListener(marker, "click", function() {
        location.href = "/game/location/show/"+lid;
    });
    marker.setTitle(name);
    return marker;
}
function addPoint(event)
{
    var marker = new google.maps.Marker({
        position: event.latLng,
        title:"Punkt"
    });
    marker.setMap(map);
    if (arrMeter.length > 0 && arrMeter.length < 2)
    {
        var dist = mercatorInterpolate(event.latLng, arrMeter[0].point.latLng);
        $("#point_coords .miles").html("Odległość: "+dist+" km");

        var polyline;
        polyline = new google.maps.Polyline({
            path: [event.latLng, arrMeter[0].point.latLng],
            geodesic: false,
            strokeColor: "#FF0000",
            strokeOpacity: 1.0,
            strokeWeight: 2
        });
        //new polyline
        polyline.setMap(map);
        arrMeter.push({marker: marker, point: event, polyline: polyline});
    }
    else
    {
        if (arrMeter.length == 2)
        {
            $.each(arrMeter, function (index, value) {
                arrMeter[index].marker.setMap(null);
                if (arrMeter[index].polyline) arrMeter[index].polyline.setMap(null);
            });
            arrMeter.length = 0;
            $("#point_coords .miles").html("");
        }
        arrMeter.push({marker: marker, point: event});
    }
}
function managePolys(event) {
    if (((JSON.stringify(objCache) + event.latLng.toUrlValue()).length + 12) > 10000) {
        alert('Limit markerów wyczerpany.');
        return;
    }
    if ($('select[name="type"]').val().match(/(LOCATION)/) && path.length != 0) {
        alert("Ta lokacja ma już zaznaczone położenie. Edytuj lub usuń istniejący marker.");
        if (path.length > 1) {
            var tmp = path.getAt(0);
            path.clear();
            path.insertAt(0, tmp);
            poly.setPath(path);
            for (var i in markers) {
                if (i != 0)
                    markers[i].setMap(null);
            }
            markers.splice(1, markers.length - 1);
            objCache.points.splice(1, objCache.points.length - 1);
            $('input[name="'+inputname+'"]').val(JSON.stringify(objCache));
        }
        return;
    }
    var index = path.length;
    if (path.length > 1) {
        for (var i = 0; i < (path.length - 1); ++i) {
            var a = Math.abs(path.getAt(i).lat() - path.getAt(i + 1).lat());
            var b = Math.abs(path.getAt(i).lng() - path.getAt(i + 1).lng());
            var a2 = Math.abs(path.getAt(i).lat() - event.latLng.lat());
            var b2 = Math.abs(path.getAt(i).lng() - event.latLng.lng());
            var dmin = (a / b) - (a / b) / 10;
            var dmax = (a / b) + (a / b) / 10;
            if (((a2 / b2) > dmin) && ((a2 / b2) < dmax) && (a >= a2) && (b >= b2)) {
                index = i + 1;
            }
        };
    };
    path.insertAt(index, event.latLng);

    var marker = new google.maps.Marker({
        position: event.latLng,
        map: map,
        draggable: true
    });
    markers.splice(index, 0, marker);
    for (var i in markers) {
        markers[i].setTitle("#" + (parseInt(i) + 1));
        if (!objCache.points[i])
            objCache.points[i] = new Object();
        objCache.points[i].lat = parseFloat(markers[i].getPosition().lat().toFixed(6));
        objCache.points[i].lng = parseFloat(markers[i].getPosition().lng().toFixed(6));
    }
    $('input[name="'+inputname+'"]').val(JSON.stringify(objCache));
    markerEvents(marker);
}
function markerEvents(marker) {
    google.maps.event.addListener(marker, 'click', function() {
        marker.setMap(null);
        for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i)
            ;
        markers.splice(i, 1);
        objCache.points.splice(i, 1);
        path.removeAt(i);
        for (var i in markers) {
            markers[i].setTitle("#" + (parseInt(i) + 1));
        }
        $('input[name="'+inputname+'"]').val(JSON.stringify(objCache));
    });

    google.maps.event.addListener(marker, 'dragend', function() {
        for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i)
            ;
        path.setAt(i, marker.getPosition());
        objCache.points[i].lat = parseFloat(markers[i].getPosition().lat().toFixed(6));
        objCache.points[i].lng = parseFloat(markers[i].getPosition().lng().toFixed(6));
        $('input[name="'+inputname+'"]').val(JSON.stringify(objCache));
    });
}
function showCoords(event)
{
    $("#point_coords .coords").html("Koordynaty punktu | X:"+event.latLng.lng()+", Y:"+event.latLng.lat());
}
// Sets the map on all markers in the array.
function setAllMap(map) {
    for (var i in markers) {
        markers[i].setMap(map);
    }
}
// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
    setAllMap(null);
}
// Shows any markers currently in the array.
function showMarkers() {
    setAllMap(map);
}

function load() {
    path.clear();
    objCache.points = [];
    markers = [];
    delete objCache.color;
    //resizeMapDiv();
    var latlng = new google.maps.LatLng(centreLat, centreLon);
    var myOptions = {
        zoom: initialZoom,
        minZoom: 2,
        maxZoom: 5,
        center: latlng,
        panControl: true,
        zoomControl: true,
        mapTypeControl: true,
        scaleControl: false,
        streetViewControl: false,
        overviewMapControl: true,
        mapTypeControlOptions: { mapTypeIds: ["OwnMap"] },
        mapTypeId: "OwnMap"
    };
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(FullScreenControl(map, "Powiększ mapę", "Pomniejsz mapę"));
    gmicMapType = new GMICMapType();
    map.mapTypes.set("OwnMap",gmicMapType);
    poly = new google.maps.Polygon({
        strokeWeight: 3,
        fillColor: '#ffe4b5'
    });

    /* Map bounds */
    var allowedBounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(-84.96033424784537, -179.560546875),
        new google.maps.LatLng(85.0803597843061, 179.560546875)
    );

    var zoomChanged = false;

    google.maps.event.addListener(map, "center_changed", function() {

        var mapBounds = map.getBounds();

        if((mapBounds.getNorthEast().lng()*-1)+30 > allowedBounds.getNorthEast().lng()-5) {
            var newCenter = new google.maps.LatLng(map.getCenter().lat(),
                map.getCenter().lng() -
                (mapBounds.getNorthEast().lng() -
                allowedBounds.getNorthEast().lng()), true);
            map.panTo(newCenter);
            return;
        }

        if((mapBounds.getSouthWest().lng()*-1)-30 < allowedBounds.getSouthWest().lng()+5) {
            var newCenter = new google.maps.LatLng(map.getCenter().lat(),
                map.getCenter().lng() +
                (allowedBounds.getSouthWest().lng() -
                mapBounds.getSouthWest().lng()), true);
            map.panTo(newCenter);
            return;
        }

        if(mapBounds.getNorthEast().lat() > allowedBounds.getNorthEast().lat()) {
            var newCenter = new google.maps.LatLng(map.getCenter().lat() -
                (mapBounds.getNorthEast().lat() -
                allowedBounds.getNorthEast().lat()),
                map.getCenter().lng(), true);
            map.panTo(newCenter);
            return;
        }

        if(mapBounds.getSouthWest().lat() < allowedBounds.getSouthWest().lat()) {
            var newCenter = new google.maps.LatLng(map.getCenter().lat() +
                (allowedBounds.getSouthWest().lat() -
                mapBounds.getSouthWest().lat()),
                map.getCenter().lng(), true);
            map.panTo(newCenter);
            return;
        }
    }, this);

    google.maps.event.addListener(map, "zoom_changed", function() { zoomChanged = true; }, this);
    google.maps.event.addListener(map, "bounds_changed", function() {
        if(zoomChanged) {
            var mapBounds = map.getBounds();
            if((mapBounds.getNorthEast().lng()*-1)+30 > allowedBounds.getNorthEast().lng()-5) {
                var newCenter = new google.maps.LatLng(map.getCenter().lat(),
                    map.getCenter().lng() -
                    (mapBounds.getNorthEast().lng() -
                    allowedBounds.getNorthEast().lng()), true);
                map.panTo(newCenter);
                return;
            }

            if((mapBounds.getSouthWest().lng()*-1)-30 < allowedBounds.getSouthWest().lng()+5) {
                var newCenter = new google.maps.LatLng(map.getCenter().lat(),
                    map.getCenter().lng() +
                    (allowedBounds.getSouthWest().lng() -
                    mapBounds.getSouthWest().lng()), true);
                map.panTo(newCenter);
                return;
            }

            if(mapBounds.getNorthEast().lat() > allowedBounds.getNorthEast().lat()) {
                var newCenter = new google.maps.LatLng(map.getCenter().lat() -
                    (mapBounds.getNorthEast().lat() -
                    allowedBounds.getNorthEast().lat()),
                    map.getCenter().lng(), true);
                map.panTo(newCenter);
                return;
            }

            if(mapBounds.getSouthWest().lat() < allowedBounds.getSouthWest().lat()) {
                var newCenter = new google.maps.LatLng(map.getCenter().lat() +
                    (allowedBounds.getSouthWest().lat() -
                    mapBounds.getSouthWest().lat()),
                    map.getCenter().lng(), true);
                map.panTo(newCenter);
                return;
            }

            zoomChanged = false;
        }
    }, this);

    //google.maps.event.addListener(map, "click", showCoords);
    google.maps.event.addListener(map, "click", addPoint);

    google.maps.event.addListenerOnce(map, 'idle', function(){
        if (typeof additionMapStart === "function") {
            additionMapStart();
        }
    });
}