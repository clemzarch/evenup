mapboxgl.accessToken = 'pk.eyJ1IjoiY2xlbXotc3BhZ2hldHRpIiwiYSI6ImNqcGpobjQ2ZDA1dHMza2xucDhudGZhNWsifQ.PobnKYF8IBNfZLH2a120Jg';
var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/clemz-spaghetti/cjqnuhhxz6b9h2smwrg0wmz1j',
    center: [1, 47],
    zoom: 5
});

document.getElementById('date_event').addEventListener('mouseup', function(e) {
    let offset = e.target.value;
    let MaDate = moment().add(parseInt(offset), 'days').format('YYYY-MM-DD');

    document.getElementById('date_label').innerHTML = MaDate;

	fetch('/geo/'+MaDate)
	.then((res) => {
		return res.json();
	})
	.then((data) => {		
		map.addSource('earthquakes', {
			"type": "geojson",
			"data": data
		});
		map.addLayer({
			"id": "earthquakes-heat",
			"type": "heatmap",
			"source": "earthquakes",
			"paint": {
				"heatmap-weight": 1,
				"heatmap-intensity": 1,
				"heatmap-opacity": 1,
				"heatmap-radius": 50,
				"heatmap-color":
					[
						"interpolate",
						["linear"],
						["heatmap-density"],
						0, "hsla(240, 0%, 100%, 0)",
						0.17, "hsla(238, 38%, 13%, 0.81)",
						0.39, "hsla(285, 100%, 37%, 0.85)",
						0.86, "hsla(317, 100%, 83%, 0.59)"
					]
			}
		});
		map.addLayer({ // les cercles qui servent de hit-box
			"id": "earthquakes-point",
			"type": "circle",
			"source": "earthquakes",
			"paint": {
				"circle-radius": 35,
				"circle-stroke-color": "white",
				"circle-stroke-width": 0,
				"circle-opacity": 0
			}
		});

		map.on('click', 'earthquakes-point', function (e) { // TODO filter les meteos qui ne sont pas a 17 heure le jour de MaDate
			if(clicked === false) {
				clicked = true; // evite le spam pendant qu'on fait des requetes
				document.getElementById('card_container').innerHTML = '<div class="fas fa-times" id="close_button"></div>';

				let offset = document.getElementById('date_event').value;
				let jour = (parseInt((new Date()).getDate()) + parseInt(offset)).toString();

				let MaDateZero =	(new Date()).getFullYear() + '-' +
					("0" + ((new Date()).getMonth() + 1)).slice(-2) + '-' +
					("0" + jour).slice(-2);

				const longitude = e.features[0].geometry.coordinates[0];
				const lattitude = e.features[0].geometry.coordinates[1];

				//	recup la meteo
				fetch('http://api.openweathermap.org/data/2.5/forecast?lat='+lattitude+'&lon='+longitude+'&appid=7e39eceace89a2eabd5786d8248a25bd&units=metric')
					.then((res) => {
						return res.json();
					})
					.then((data) => {
						data.list.forEach(function(list_item) {
							if(list_item.dt_txt === MaDateZero+' 18:00:00') {
								document.getElementById('meteo').innerHTML = list_item.weather[0].main;
								document.getElementById('temp').innerHTML = list_item.main.temp+'';
								document.getElementById('hum').innerHTML = list_item.main.humidity+'%';
							}
						});
					});

				// recup les infos de l'event
				e.features.forEach(function(event_item) {
					fetch('api/events/'+event_item.properties.id)
						.then((res) => {
							return res.json();
						})
						.then((data) => {
							document.getElementById('card_container').insertAdjacentHTML('beforeend','<div class="card"><div class="fas fa-heart" id="like_button"></div><a class="fas fa-paper-plane" id="share_button" href="https://www.google.fr/maps/dir//'+data.latitude+','+data.longitude+'"></a><h1>'+data.title+'</h1><details><p>'+data.description+'</p></details>'+data.formattedAddress+'<hr>'+data.date+'</div>');
						});
				});
				Buttons();

			}
		});

		map.on('mouseup', 'earthquakes-point', function () {
			clicked = false;
		});
	});
});

function Buttons(){
    document.getElementById('close_button').addEventListener('click', function(e) {
        document.getElementById('card_container').innerHTML = '';
    });
}
document.getElementById('date_event').addEventListener('mousedown', function() {
    map.removeLayer('earthquakes-heat');
    map.removeLayer('earthquakes-point');
    map.removeSource('earthquakes');
});

document.getElementById('date_event').addEventListener('mousemove', function(e){
	let offset = e.target.value;
    let MaDate = moment().add(parseInt(offset), 'days').format('YYYY-MM-DD');
    document.getElementById('date_label').innerHTML = MaDate;
	document.getElementById('date_label').style.left = e.pageX-150 +'px';
});