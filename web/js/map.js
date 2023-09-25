let DEFAULT_COORDS = [55.76, 37.64];
const taskMap = document.querySelector('#map')

let init = () => {
    let myMap = taskMap
    ? new ymaps.Map("map", {
        center: coords.length !== 0 ? coords : DEFAULT_COORDS,
        zoom: 16
    })
    : null;
     
    if (myMap) {
        myMap.controls.remove('trafficControl');
        myMap.controls.remove('searchControl');
        myMap.controls.remove('geolocationControl');
        myMap.controls.remove('typeSelector');
        myMap.controls.remove('fullscreenControl');
        myMap.controls.remove('rulerControl');
    }
}    

ymaps.ready(init);
 

