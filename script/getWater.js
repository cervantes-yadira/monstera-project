console.log("This is the getWater.js starting ");
//Display plant that is due for watering
document.addEventListener('DOMContentLoaded', function() {
    fetch('getWaterDate.php')
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
                console.log("Watering period : " + plant.wateringPeriod);
                console.log("get date :" + lastWateredDate.getDate())
                console.log("Total : ", lastWateredDate.getDate() + wateringPeriod);

                const today = new Date();
                if (today >= nextWateringDate) {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item';
                    listItem.innerHTML = `<i class="fas fa-bell"></i> ${plant.Nickname} is due for watering`;
                    notificationList.appendChild(listItem);
                    console.log("Water due!");
                }
                else{
                    console.log("Water not due!");
                    console.log(today);

                }
            });
        })
        .catch(error => console.error('Error fetching plant data:', error));
});

console.log("This is the getWater.js ending 000");