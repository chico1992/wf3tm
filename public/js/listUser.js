
let btn = document.getElementById('load');
let users = document.getElementById('users');
 
let request = new XMLHttpRequest();
 
request.onreadystatechange = function() {
    if(request.readyState === XMLHttpRequest.DONE) {
        users.style.border = '1px solid #e8e8e8';
        if(request.status === 200) { 
            let json= JSON.parse(request.responseText);
            json.forEach(element => {
                let row= document.createElement("TR");
                let idElement = document.createElement("TD");
                let idText = document.createTextNode(element['id']);
                let nameElement = document.createElement("TD");
                let nameText = document.createTextNode(element['name']);
                idElement.appendChild(idText);
                row.appendChild(idElement);
                nameElement.appendChild(nameText);
                row.appendChild(nameElement);
                users.appendChild(row);
            });
        } else {
            users.innerHTML = 'An error occurred during your request: ' +  request.status + ' ' + request.statusText;
        } 
    }
}

// let url =  Routing.generate('admin_user_list')
request.open('Get', Routing.generate('admin_user_list'));
document.addEventListener("DOMContentLoaded", function() {
    request.send();
});