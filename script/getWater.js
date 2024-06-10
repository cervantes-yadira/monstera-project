console.log("This is the getWater.js starting ");
//Display plant that is due for watering
document.addEventListener('DOMContentLoaded', function() {
    fetch('models/getWaterDate.php')
        .then(response => response.json())
        .then(plants => {
            const notificationList = document.getElementById('notificationList');
            console.log("This is middle");

            plants.forEach(plant => {
                const lastWateredDate = new Date(plant.LastWatered);
                const nextWateringDate = new Date(lastWateredDate);

                // Convert wateringPeriod to an integer
                const wateringPeriod = parseInt(plant.wateringPeriod, 10);
                // const lastWatered = parseInt(lastWateredDate.getDate(), 10);

                nextWateringDate.setDate(lastWateredDate.getDate()+ wateringPeriod);
                console.log("Last watering date : " + lastWateredDate);
                console.log("Next watering date : " + nextWateringDate);
                console.log("Total : ", lastWateredDate.getDate() + wateringPeriod);

                const today = new Date();
                if (today >= nextWateringDate) {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item';
                    listItem.innerHTML = `<i class="fas fa-bell"></i> ${plant.Nickname} is due for watering <a href="" class="waterBtn">Water</a>`;
                    notificationList.appendChild(listItem);
                    console.log("Water due!");
                }
                else{
                    console.log("Water not due!");
                    console.log(today);
                }
            });

            //Click function for Water button
            document.querySelectorAll('.waterBtn').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const plantId = event.target.getAttribute('data-plant-id');
                    waterPlant(plantId);
                });
            });
        })
        .catch(error => console.error('Error fetching plant data:', error));
});

console.log("This is the get date ending");

function waterPlant(plantId) {
    const today = new Date().toISOString().split('T')[0]; // Format today's date as YYYY-MM-DD

    fetch('resetWaterDate.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: plantId,
            lastWatered: today
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(`Plant with ID ${plantId} watered successfully.`);
                location.reload(); // Reload the page to update the list
            } else {
                console.error('Error updating plant:', data.error);
            }
        })
        .catch(error => console.error('Error updating plant:', error));
}




console.log("This is the getWater.js ending 9999");

