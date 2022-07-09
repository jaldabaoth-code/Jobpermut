require('leaflet');

// eslint-disable-next-line
const L = window.L;
let map = null;
const userData = document.querySelector('#userData');
const userDataValues = userData.value.split('/');
// Fonction d'initialisation de la carte
function initMap(
    homeLong = userDataValues[0],
    homeLat = userDataValues[1],
    workLong = userDataValues[2],
    workLat = userDataValues[3],
    lat = 48.852969,
    lon = 2.2,
) {
    // Créer l'objet "macarte" et l'insèrer
    // dans l'élément HTML qui a l'ID "map"
    map = L.map('map', {
        zoomControl: false,
        scrollWheelZoom: false,
        dragging: false,
        tap: false,
        touchZoom: false,
        center: [48.852969, 2.2],
    }).setView(
        [lat, lon],
        10,
    );
    // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut.
    // Nous devons lui préciser où nous souhaitons les récupérer. Ici, thunderforest.com
    L.tileLayer(
        'https://tile.thunderforest.com/atlas/{z}/{x}/{y}.png?apikey=99affac52963476dbbf2ed20db232af9',
        {
            // Il est toujours bien de laisser le lien vers la source des données
            attribution:
            'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
        },
    ).addTo(map);

    const workIcon = L.divIcon({ className: 'fas fa-briefcase', iconAnchor: [12, 25] });
    L.marker([workLat, workLong], { icon: workIcon }).addTo(map);
    const homeIcon = L.divIcon({ className: 'fas fa-home', iconAnchor: [12, 25] });
    L.marker([homeLat, homeLong], { icon: homeIcon }).addTo(map);

    fetch(`/leaflet/direction/${homeLong}/${homeLat}/${workLong}/${workLat}`)
        .then((response) => response.json())
        .then((data) => {
            data = data.geometry.coordinates;
            data.forEach((element) => {
                element = element.reverse();
            });
            L.polyline(data, { color: '#00636f' }).addTo(map);
            map.fitBounds(data);
        });
}

window.onload = function () {
    initMap();
};
