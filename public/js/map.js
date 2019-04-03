mapboxgl.accessToken = 'pk.eyJ1IjoiY2xlbXotc3BhZ2hldHRpIiwiYSI6ImNqcGpobjQ2ZDA1dHMza2xucDhudGZhNWsifQ.PobnKYF8IBNfZLH2a120Jg';
let map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/clemz-spaghetti/cjqnuhhxz6b9h2smwrg0wmz1j',
    center: [1, 47],
    zoom: 5
});

LoadGeoJson();
document.getElementById('date_range').addEventListener('change', function () {
	console.log('mouseup');
    UnloadGeoJson();
    LoadGeoJson();
});

function LoadGeoJson() {
    let offset = document.getElementById('date_range').value;
    let MaDate = moment().add(parseInt(offset), 'days').format('YYYY-MM-DD');

<<<<<<< HEAD
    // recupere les checkboxes pour faire une liste des filtres actifs
    let checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    let active_filters = [];
    checkboxes.forEach(function (checkbox) {
        if (checkbox.checked === true) {
            active_filters.push(checkbox.id);
        }
    });
    console.log(active_filters);

    // recupere le geo-json d'apres la date et la liste de filtres actifs
    fetch('/geo/' + MaDate + '/' + active_filters)
        .then((res) => {
            return res.json();
        })
        .then((data) => {
            map.addSource('events', {
                "type": "geojson",
                "data": data
            });
            map.addLayer({
                "id": "events-heat",
                "type": "heatmap",
                "source": "events",
                "paint": {
                    "heatmap-weight": 1,
                    "heatmap-intensity": 1,
                    "heatmap-opacity": 1,
                    "heatmap-radius": 50,
                    "heatmap-color": [
                        "interpolate",
                        ["linear"],
                        ["heatmap-density"],
                        0, "hsla(240, 0%, 100%, 0)",
                        0.17, "hsla(238, 38%, 13%, 0.81)",
                        0.39, "hsla(285, 100%, 37%, 0.85)",
                        1, "hsla(317, 100%, 83%, 0.59)"
                    ]
                }
            });
            map.addLayer({ // les cercles qui servent de hit-box
                "id": "events-hitbox",
                "type": "circle",
                "source": "events",
                "paint": {
                    "circle-radius": 35,
                    "circle-stroke-color": "white",
                    "circle-stroke-width": 0,
                    "circle-opacity": 0
                }
            });

            map.on('mouseup', 'events-hitbox', function () {
                clicked = false;
            });

            map.on('click', 'events-hitbox', function (e) {
                if (clicked === false) {
                    clicked = true; // evite le spam pendant qu'on fait des requetes
                    document.getElementById('date_controls').style.display = "none";

                    let offset = document.getElementById('date_range').value;
                    let MaDate = moment().add(parseInt(offset), 'days').format('YYYY-MM-DD');

                    const longitude = e.features[0].geometry.coordinates[0];
                    const lattitude = e.features[0].geometry.coordinates[1];

                    // recup les infos de l'event sur notre BDD, affiche les cards
                    let events_clicked = e.features;
                    for (let i = 0; i < events_clicked.length; i++) {
                        if (i > 10) {
                            break
                        } // limite le nombre d'events à fetch

                        fetch('api/events/' + events_clicked[i].properties.id)
                            .then((res) => {
                                return res.json();
                            })
                            .then((data) => {
                                document.getElementById('card_container').insertAdjacentHTML('beforeend', '<div class="card" latitude="' + data.latitude + '" longitude="' + data.longitude + '"><div class="fas fa-heart" id="like_button"></div><a class="fas fa-paper-plane" target="_blank" id="share_button" href="https://www.google.fr/maps/dir//' + data.latitude + ',' + data.longitude + '"></a><h1>' + data.title + '</h1><details><summary>...</summary><p>' + data.description + '</p></details>' + data.formattedAddress + '<hr>' + data.date + '</div>');

                                // zoom sur un point au survol de sa card
                                let cards = document.getElementsByClassName("card");
                                for (let i = 0; i < cards.length; i++) {
                                    cards[i].addEventListener('mouseenter', function (e) {
                                        map.flyTo({
                                            center: [
                                                e.target.attributes.longitude.value,
                                                e.target.attributes.latitude.value
                                            ],
                                            zoom: 15,
                                            curve: 1
                                        });
                                    });
                                }
                                // si on sors le curseur des cards, dezoomer la carte
                                document.getElementById('card_container').addEventListener('mouseleave', function () {
                                    map.flyTo({
                                        zoom: 8
                                    });
                                });
                            });
                    }

                    Meteo(lattitude, longitude, MaDate);

                    CloseButton();
                }
            });
        });
=======
	// recupere les checkboxes pour faire une liste des filtres actifs
	checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
	let active_filters = [];
	checkboxes.forEach(function(checkbox){
		if (checkbox.checked == true) {
			active_filters.push(checkbox.id);
		}
	});
	console.log(active_filters);
	
	// recupere le geo-json d'apres la date et la liste de filtres actifs
	fetch('/geo/' + MaDate + '/' + active_filters)
	.then((res) => {
		return res.json();
	})
	.then((data) => {
		map.addSource('events', {
			"type": "geojson",
			"data": data
		});
		map.addLayer({
			"id": "events-heat",
			"type": "heatmap",
			"source": "events",
			"paint": {
				"heatmap-weight": 1,
				"heatmap-intensity": 1,
				"heatmap-opacity": 1,
				"heatmap-radius": 50,
				"heatmap-color": [
					"interpolate",
					["linear"],
					["heatmap-density"],
					0, "hsla(240, 0%, 100%, 0)",
					0.17, "hsla(238, 38%, 13%, 0.81)",
					0.39, "hsla(285, 100%, 37%, 0.85)",
					1, "hsla(317, 100%, 83%, 0.59)"
				]
			}
		});
		map.addLayer({ // les cercles qui servent de hit-box
			"id": "events-hitbox",
			"type": "circle",
			"source": "events",
			"paint": {
				"circle-radius": 35,
				"circle-stroke-color": "white",
				"circle-stroke-width": 0,
				"circle-opacity": 0
			}
		});

		map.on('mouseup', 'events-hitbox', function () {
			clicked = false;
		});

		map.on('click', 'events-hitbox', function (e) {
			if (clicked === false) {
				clicked = true; // evite le spam pendant qu'on fait des requetes
				document.getElementById('date_event').style.display = "none";
				
				let offset = document.getElementById('date_event').value;
				let MaDate = moment().add(parseInt(offset), 'days').format('YYYY-MM-DD');

				const longitude = e.features[0].geometry.coordinates[0];
				const lattitude = e.features[0].geometry.coordinates[1];
				
<<<<<<< HEAD:public/script.js
				//	recup la meteo depuis openweather
				fetch('https://api.openweathermap.org/data/2.5/forecast?lat='+lattitude+'&lon='+longitude+'&appid=7e39eceace89a2eabd5786d8248a25bd&units=metric')
					.then((res) => {
						return res.json();
					})
					.then((data) => {
						data.list.forEach(function(list_item) {
							if(list_item.dt_txt === MaDate + ' 18:00:00') {
								document.getElementById('meteo').innerHTML = list_item.weather[0].main;
								document.getElementById('temp').innerHTML = list_item.main.temp+' °C';
								document.getElementById('hum').innerHTML = list_item.main.humidity+' %';
							}
						});
					});

=======
>>>>>>> 26a838c1dddbdde17912b42a3a50c130e9252617:public/js/map.js
				// recup les infos de l'event sur notre BDD, affiche les cards
				events_clicked = e.features;
				for (var i = 0; i < events_clicked.length; i++) {
					if (i > 10) { break }; // limite le nombre d'events à fetch
					
					fetch('api/events/' + events_clicked[i].properties.id)
						.then((res) => {
							return res.json();
						})
						.then((data) => {
							document.getElementById('card_container').insertAdjacentHTML('beforeend','<div class="card" latitude="'+data.latitude+'" longitude="'+data.longitude+'"><div class="fas fa-heart" id="like_button"></div><a class="fas fa-paper-plane" target="_blank" id="share_button" href="https://www.google.fr/maps/dir//'+data.latitude+','+data.longitude+'"></a><h1>'+data.title+'</h1><details><summary>...</summary><p>'+data.description+'</p></details>'+data.formattedAddress+'<hr>'+data.date+'</div>');
							
							// zoom sur un point au survol de sa card
							cards = document.getElementsByClassName("card");
							for(var i = 0; i < cards.length; i++){
								cards[i].addEventListener('mouseenter', function(e){
									map.flyTo({
										center: [
											e.target.attributes.longitude.value,
											e.target.attributes.latitude.value
										],
										zoom: 15,
										curve: 1
									});
								});
							}
							// si on sors le curseur des cards, dezoomer la carte
							document.getElementById('card_container').addEventListener('mouseleave', function(){
								map.flyTo({
									zoom: 8
								});
							});
						});
				}
				
				Meteo(lattitude, longitude, MaDate);
				
				CloseButton();
			}
		});
	});
>>>>>>> 759952fc0e98d7c7309aac7d61a554936926892e
}

// bouton de fermeture des cards
function CloseButton() {
    document.getElementById('card_container').innerHTML = '<div class="fas fa-times" id="close_button"></div>';

    document.getElementById('close_button').addEventListener('click', function () {
        map.flyTo({
            center: [1, 47],
            zoom: 5
        });
        document.getElementById('card_container').innerHTML = '';
        document.getElementById('date_controls').style.display = "unset";
        document.getElementById('weather_container').style.display = "none";
    });
}

//	recup la meteo depuis openweather
function Meteo(lattitude, longitude, MaDate) {
    document.getElementById('weather_container').style.display = "block";

    fetch('http://api.openweathermap.org/data/2.5/forecast?lat=' + lattitude + '&lon=' + longitude + '&appid=7e39eceace89a2eabd5786d8248a25bd&units=metric')
        .then((res) => {
            return res.json();
        })
        .then((data) => {
            data.list.forEach(function (list_item) {
                if (list_item.dt_txt === MaDate + ' 18:00:00') {
                    document.getElementById('meteo').innerHTML = list_item.weather[0].main;
                    document.getElementById('temp').innerHTML = list_item.main.temp + ' °C';
                    document.getElementById('hum').innerHTML = list_item.main.humidity + ' %';
                }
            });
        });
}

// efface les layers avant d'en ajouter un nouveau
function UnloadGeoJson() {
    map.removeLayer('events-heat');
    map.removeLayer('events-hitbox');
    map.removeSource('events');
}

// deplace le date_label et affiche la date au dessus du range
document.getElementById('date_range').addEventListener('mousemove', function (e) {
    let offset = e.target.value;
    let MaDate = moment().add(parseInt(offset), 'days').format('D');
<<<<<<< HEAD
    document.getElementById('date_label').innerHTML = MaDate + ' Avril'; //TODO : ENLEVER TRICHE
    document.getElementById('date_label').style.left = e.pageX - 150 + 'px';
=======
    document.getElementById('date_label').innerHTML = MaDate + ' Avril' + ' 2019'; // TODO : ENLEVER TRICHE
	document.getElementById('date_label').style.left = e.pageX-150 + 'px';
>>>>>>> 759952fc0e98d7c7309aac7d61a554936926892e
});

// affiche le menu des filtres
document.getElementById('filter_button').addEventListener('click', function () {
    document.getElementById('filter_container').style.display = "block";
    document.getElementById('map').style.filter = "blur(20px)";
    document.getElementById('card_container').style.filter = "blur(10px)";
    document.getElementById('weather_container').style.filter = "blur(10px)";
    document.getElementById('date_controls').style.display = "none";
    UnloadGeoJson();
});
// cache le menu des filtres
document.getElementById('filter_confirm_button').addEventListener('click', function () {
    LoadGeoJson();
    document.getElementById('filter_container').style.display = "none";
    document.getElementById('map').style.filter = "none";
    document.getElementById('card_container').style.filter = "none";
    document.getElementById('date_controls').style.display = "unset";
    document.getElementById('weather_container').style.filter = "none";
});

document.getElementById('play_button').addEventListener('click', function () {
    if (document.getElementById('date_range').value >= 7) {
        document.getElementById('date_range').value = 0;
    }

    document.getElementById('play_button').style.color = '#8E00BF';

    if(playID === false) {
        playID = window.setInterval(function () {
            document.getElementById('date_range').value++;
            UnloadGeoJson();
            LoadGeoJson();
            if (document.getElementById('date_range').value >= 7) {
                document.getElementById('play_button').style.color = '#333';
                window.clearInterval(playID);
            }
        }, 1000);
    }
});