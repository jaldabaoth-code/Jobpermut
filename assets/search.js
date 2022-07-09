window.onload = function () {
    fetch('/permutsearchAjax')
        .then((response) => response.text())
        .then((html) => {
            const main = document.querySelector('.permutsearch');
            main.innerHTML = html;
            // eslint-disable-next-line
            require('leaflet');
            // eslint-disable-next-line
            const L = window.L;

            const NbCard = document.getElementsByClassName('permut-card').length + 1;
            const userData = document.querySelector('#user-data');
            const userDataValues = userData.value.split('/');
            for (let p = 1; p < NbCard; p += 1) {
                const btn = document.getElementById(`button-${p}`);
                const regUserData = document.querySelector(`#reguser-data-${p}`);
                const regUserDataValues = regUserData.value.split('/');
                const iMap = function initMap(
                    homeLong = 2.8884657,
                    homeLat = 48.9562018,
                    workLong = 2.3488,
                    workLat = 48.8534,
                    userHomeLong = 2.38333,
                    userHomeLat = 48.916672,
                    userWorkLong = 2.765796,
                    userWorkLat = 48.878462,
                    lat = 48.852969,
                    lon = 2.2,
                ) {
                    // Init Maps
                    // Map Before
                    const mapBefore = L.map(`map-before-${p}`, {
                        zoomControl: false,
                        scrollWheelZoom: false,
                        dragging: false,
                        tap: false,
                        touchZoom: false,
                        center: [48.852969, 2.2],
                    }).setView([lat, lon], 10);

                    // Map After
                    const mapAfter = L.map(`map-after-${p}`, {
                        zoomControl: false,
                        scrollWheelZoom: false,
                        dragging: false,
                        tap: false,
                        touchZoom: false,
                        center: [48.852969, 2.2],
                    }).setView([lat, lon], 10);

                    // Init Tiles
                    // Tiles on mapBefore
                    L.tileLayer(
                        'https://tile.thunderforest.com/atlas/{z}/{x}/{y}.png?apikey=99affac52963476dbbf2ed20db232af9',
                        {
                            attribution:
                                'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                        },
                    ).addTo(mapBefore);

                    // Tiles on mapAfter
                    L.tileLayer(
                        'https://tile.thunderforest.com/atlas/{z}/{x}/{y}.png?apikey=99affac52963476dbbf2ed20db232af9',
                        {
                            attribution:
                                'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                        },
                    ).addTo(mapAfter);

                    // Init Markers

                    // on mapBefore
                    const workIcon = L.divIcon({
                        className: 'fas fa-briefcase',
                        iconAnchor: [12, 25],
                    });
                    L.marker([workLat, workLong], { icon: workIcon }).addTo(mapBefore);
                    const homeIcon = L.divIcon({
                        className: 'fas fa-home',
                        iconAnchor: [12, 25],
                    });
                    L.marker([homeLat, homeLong], { icon: homeIcon }).addTo(mapBefore);

                    const userWorkIcon = L.divIcon({
                        className: 'fas fa-briefcase user',
                        iconAnchor: [12, 25],
                    });
                    L.marker([userWorkLat, userWorkLong], {
                        icon: userWorkIcon,
                    }).addTo(mapBefore);
                    const userHomeIcon = L.divIcon({
                        className: 'fas fa-home user',
                        iconAnchor: [12, 25],
                    });
                    L.marker([userHomeLat, userHomeLong], {
                        icon: userHomeIcon,
                    }).addTo(mapBefore);

                    // on mapAfter
                    const newWorkIcon = L.divIcon({
                        className: 'fas fa-briefcase',
                        iconAnchor: [12, 25],
                    });
                    L.marker([userWorkLat, userWorkLong], {
                        icon: newWorkIcon,
                    }).addTo(mapAfter);
                    const newHomeIcon = L.divIcon({
                        className: 'fas fa-home',
                        iconAnchor: [12, 25],
                    });
                    L.marker([homeLat, homeLong], { icon: newHomeIcon }).addTo(mapAfter);

                    const NewUserWorkIcon = L.divIcon({
                        className: 'fas fa-briefcase user',
                        iconAnchor: [12, 25],
                    });
                    L.marker([workLat, workLong], {
                        icon: NewUserWorkIcon,
                    }).addTo(mapAfter);
                    const NewUserHomeIcon = L.divIcon({
                        className: 'fas fa-home user',
                        iconAnchor: [12, 25],
                    });
                    L.marker([userHomeLat, userHomeLong], {
                        icon: NewUserHomeIcon,
                    }).addTo(mapAfter);

                    // Init Routes

                    // on map Before
                    fetch(`/leaflet/direction/${homeLong}/${homeLat}/${workLong}/${workLat}`)
                        .then((response) => response.json())
                        .then((data) => {
                            data = data.geometry.coordinates;
                            data.forEach((element) => {
                                element = element.reverse();
                            });
                            L.polyline(data, { color: '#ed9f1a' }).addTo(mapBefore);
                            fetch(`/leaflet/direction/${userHomeLong}/${userHomeLat}/${userWorkLong}/${userWorkLat}`)
                                .then((response) => response.json())
                                .then((dataRoad) => {
                                    dataRoad = dataRoad.geometry.coordinates;
                                    dataRoad.forEach((element) => {
                                        element = element.reverse();
                                    });
                                    L.polyline(dataRoad, {
                                        color: '#00636f',
                                    }).addTo(mapBefore);
                                    mapBefore.fitBounds([data, dataRoad]);
                                });
                        });

                    // on mapAfter

                    fetch(`/leaflet/direction/${homeLong}/${homeLat}/${userWorkLong}/${userWorkLat}`)
                        .then((response) => response.json())
                        .then((data) => {
                            data = data.geometry.coordinates;
                            data.forEach((element) => {
                                element = element.reverse();
                            });
                            L.polyline(data, { color: '#ed9f1a' }).addTo(mapAfter);
                            fetch(`/leaflet/direction/${userHomeLong}/${userHomeLat}/${workLong}/${workLat}`)
                                .then((response) => response.json())
                                .then((dataRoad) => {
                                    dataRoad = dataRoad.geometry.coordinates;
                                    dataRoad.forEach((element) => {
                                        element = element.reverse();
                                    });
                                    L.polyline(dataRoad, {
                                        color: '#00636f',
                                    }).addTo(mapAfter);
                                    mapAfter.fitBounds([data, dataRoad]);
                                });
                        });
                };

                btn.onclick = function () {
                    iMap(
                        userDataValues[0],
                        userDataValues[1],
                        userDataValues[2],
                        userDataValues[3],
                        regUserDataValues[0],
                        regUserDataValues[1],
                        regUserDataValues[2],
                        regUserDataValues[3],
                    );
                };
            }
        });
};
