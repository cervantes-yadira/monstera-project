window.onload = async() =>
{
    document.getElementById('plant-search-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const query = document.getElementById('query').value.trim();
        if (query) {
            searchPlant(query);
        }
    });

    console.log("testing ");

}

//search function implementing API
async function searchPlant(query)
{
    console.log('Search button clicked');

    const apiKey = 'sk-i0Hb663547d69f75c5335';
    const url = `https://perenual.com/api/species-list?key=${apiKey}&q=${query}`;
    try {
        const response = await fetch(url);
        const data = await response.json();
        displayResult(data);
    } catch (error) {
        console.error('Error fetching the plant data:', error);
        document.getElementById('result').innerHTML = 'Error fetching the plant data.';
    }

}

//prints the plant search result
function displayResult(array)
{
    console.log(array.data);
    let select = document.getElementById("apiList");

    // Clear previous search results
    select.innerHTML = '';

    // Capitalize the first letter of each common_name
    array.data.forEach(item => {
        item.common_name = capitalizeFirstLetter(item.common_name.toLowerCase());
    });

    // Sort the array based on common_name in ascending order
    array.data.sort((a, b) => a.common_name.localeCompare(b.common_name));

    for (let i = 0; i < array.data.length; i++) {

        let list = document.createElement("li");
        let href = document.createElement("a");

        href.textContent = array.data[i].common_name;
        href.href = "#";
        href.dataset.id = array.data[i].id;     //assign id as value using dataset so it can be retrived in the next method
        href.id = "plantSearchList";            //add id attribute to the anchor tag

        //separate the function containing event listener
        href.addEventListener('click', loadPlantInfo);

        list.appendChild(href);
        select.appendChild(list);
    }

}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

//prints plant info
async function loadPlantInfo(event){
    event.preventDefault();
    console.log("Button clicked");

    let plantId = event.currentTarget.dataset.id;
    console.log(plantId);

    let url = `https://perenual.com/api/species/details/${plantId}?key=sk-qpDM66427176d4a945459`;

    let config = {
        method: "get", //get for just reading
        mode: "cors", //security mode
        headers:{
            "Content-Type": "application/json",
            // "x-api-key": "live_oPsFo6ymBGV99n1xMNd4CJIFXSj6BMAUiVGgxQESzNWvqLT8Wvo2dJLVEhtpgfPt"
        }
    };

    let response = await fetch(url, config);
    let data = await response.json();

    //displpaying API
    let commonName = document.querySelector("#common_name");
    commonName.textContent = data.common_name;

    let scientificName = document.querySelector("#scientific_name");
    scientificName.textContent = data.scientific_name;

    let wateringSchedule = document.querySelector("#watering");
    wateringSchedule.textContent = data.watering;

    let wateringBenchmark = document.querySelector("#watering_general_benchmark");
    wateringBenchmark.textContent = data.watering_general_benchmark.value + ' ' + data.watering_general_benchmark.unit;

    let sunlight = document.querySelector("#sunlight");
    sunlight.textContent = data.sunlight;

    let pruningMonth = document.querySelector("#pruning_month");
    pruningMonth.textContent = data.pruning_month;

    // let careGuides = document.querySelector("#care-guides");
    // careGuides.textContent = data.care-guides;

    let description = document.querySelector("#description");
    description.textContent = data.description;

    let images = document.querySelector("#plantImage");
    images.src = data.default_image.original_url;


}

