const inputLat = document.querySelector('#taskform-lat');
const inputLong = document.querySelector('#taskform-long');
const inputAddress = document.querySelector('#taskform-address');
const inputCity = document.querySelector('#taskform-city');

const autoCompleteJS = new autoComplete({
    selector: '#autocomplete',  
    debounce: 2000,
    searchEngine: 'loose',
    wrapper: false,
    data: {
        src: async (query) => {
            try {
                const source = await fetch(`/ajax/autocomplete/${query}`);
                let data = await source.json();
            return data;
        } catch (error) {
            return error;
        }
    },
    cache:true,
      keys: ['autocomplete'],
    cache: false,
},
    resultsList: {
        element: (list, data) => {
            if (data.results.length === 0) {                
                const message = document.createElement("div");                
                message.setAttribute("class", "no_result");                
                message.textContent = `Локация не найдена`;                
                list.prepend(message);
            }
        },
        noResults: true,
    },
  resultItem: {
      highlight: true
  },
  events: {
      input: {
          selection: (event) => {
              const selection = event.detail.selection.value;
              autoCompleteJS.input.value = selection.autocomplete;
              inputLat.value = selection.lat;
              inputLong.value = selection.long;
              inputCity.value = selection.city;
          }
      }
  }
});
