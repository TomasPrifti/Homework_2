/* Foodish */
/* https://foodish-api.herokuapp.com/images/somefoodish/ */

/* Spoonacular */
/* https://spoonacular.com/food-api/ */
//type = "products", "informations"


/* Main Code */
const MAX_LATERAL_BLOCK = 5;
let NUM_IMAGE = 0;
const data = ["pasta", "ice", "flour", "sugar", "oil", "salt", "orange", "cheese", "coconut"];
const word = data[Math.round(Math.random() * (data.length - 1))];
let priceProduct;
let idProduct;

const section = document.querySelector("section");
createLateralBlocks();
document.querySelector("form").addEventListener("submit", searchBlock);
document.querySelector("#modal_block").addEventListener("click", doNothing);
document.querySelector("#modal_block_preference").addEventListener("click", doNothing);
document.querySelector("#input_images").addEventListener("click", newImages);
const lateralImageList = document.querySelectorAll(".lateral_image");
newImages();
searchOnWeb1("products", word);
if (document.querySelector("#logout")) {
    document.querySelector("#logout").addEventListener("click", logout);
}
if (document.querySelector("#preference")) {
    document.querySelector("#preference").addEventListener("click", openModal);
}
if (document.querySelector("#modal_preference")) {
    document.querySelector("#modal_preference").addEventListener("click", addPreference);
}


/* Start Function Declarations */

/* API Functions */

function searchOnWeb1(type, str) {
    str = modifyString(str);
    fetch("products/api1/" + encodeURIComponent(type) + "/" + encodeURIComponent(str)).then(onResponse, onError).then(onJson1);
}
function searchOnWeb2() {
    fetch("products/api2").then(onResponse, onError).then(onJson2);
}
function onResponse(response) {
    return response.json();
}
function onError(error) {
    console.log("Errore: " + error);
}
function onJson1(json) {
    document.querySelector("#all_blocks").innerHTML = "";
    createBlocks(json);
}
function onJson2(json) {
    lateralImageList[NUM_IMAGE++].src = json.image;
    /* Soluzione all'errore */
    /* if (NUM_IMAGE == MAX_LATERAL_BLOCK * 2) {
        NUM_IMAGE = 0;
    } */
}

/* Generic Functions */

function searchBlock(event) {
    event.preventDefault();
    const str = event.currentTarget.querySelector("#input_content").value.toLowerCase();
    searchOnWeb1("products", str);
}

function openModal(event) {
    if (event.currentTarget.id != "preference") {
        idProduct = event.currentTarget.parentNode.dataset.id;
        fetch("products/api1/informations/" + encodeURIComponent(idProduct)).then(onResponse, onError).then(onJsonModal);
    } else {
        document.querySelector("#modal").classList.remove("hidden");
        document.querySelector("#modal").classList.add("flex");
        document.body.classList.add("no_scroll");
        document.querySelector("#modal_block_preference").classList.remove("hidden");
        document.querySelector("#modal_block_preference").classList.add("flex");
        document.querySelector("#modal_block").classList.remove("flex");
        document.querySelector("#modal_block").classList.add("hidden");
        showPreference();
    }
}

function onJsonModal(json) {
    document.querySelector("#modal_image").src = json.image;
    document.querySelector("#modal_name").textContent = json.name[0].toUpperCase() + json.name.substring(1).toLowerCase();
    document.querySelector("#modal_text").innerHTML = jsonDescription(json);
    document.querySelector("#modal").classList.remove("hidden");
    document.querySelector("#modal").classList.add("flex");
    document.body.classList.add("no_scroll");
    document.querySelector("#modal_block_preference").classList.remove("flex");
    document.querySelector("#modal_block_preference").classList.add("hidden");
    document.querySelector("#modal_block").classList.remove("hidden");
    document.querySelector("#modal_block").classList.add("flex");
}

function jsonDescription(json) {
    const jsonData = json.nutrition.nutrients;
    let str;
    priceProduct = json.estimatedCost.value;

    str = "COST: " + json.estimatedCost.value + " " + json.estimatedCost.unit + "<br>NUTRIENTS:<br>";
    for (const obj of jsonData) {
        str += " - " + obj.name.toUpperCase() + ": " + obj.amount + " " + obj.unit + ";<br>";
    }
    return str;
}

function closeModal() {
    if (document.querySelector("#modal_content span")) {
        document.querySelector("#modal_content span").textContent = "";
    }
    if (document.querySelector("#modal_block").classList == "flex") {
        document.querySelector("#modal_block").classList.remove("flex");
        document.querySelector("#modal_block").classList.add("hidden");
    }
    if (document.querySelector("#modal_block_preference").classList == "flex") {
        document.querySelector("#modal_block_preference").classList.remove("flex");
        document.querySelector("#modal_block_preference").classList.add("hidden");
    }
}

function newImages() {
    NUM_IMAGE = 0; //Forse è meglio dentro la funzione
    for (let i = 0; i < MAX_LATERAL_BLOCK * 2; i++) {
        searchOnWeb2();
    }
}

function logout(event) {
    fetch("products/logout").then(onResponse, onError).then(onAccess);
}

function addPreference(event) {
    const form = new FormData();
    form.append("id", idProduct);
    form.append("name", document.querySelector("#modal_name").textContent);
    form.append("price", priceProduct);
    const token = document.querySelector("#form_modal_preference input[name='_token']").value;
    fetch("products/addPreference", { headers: { 'x-csrf-token': token }, method: "post", body: form }).then(onResponse, onError).then(onAccess);
}

function showPreference() {
    fetch("products/showPreference").then(onResponse, onError).then(onJsonShowPreference);
}

function removePreference(event) {
    const form = new FormData();
    idProduct = event.currentTarget.parentNode.dataset.id;
    form.append("id", idProduct);
    const token = document.querySelector("#form_modal_preference input[name='_token']").value;
    fetch("products/removePreference", { headers: { 'x-csrf-token': token }, method: "post", body: form }).then(onResponse, onError).then(onAccess);
    //event.currentTarget.parentNode.remove();
}

function onAccess(json) {

    switch (json) {
        case "removePreference":
            showPreference();
            break;
        case "addPreference":
            document.querySelector("#form_modal_preference span").textContent = "(Aggiunto ai Preferiti)";
            break;
        case "preferenceExist":
            document.querySelector("#form_modal_preference span").textContent = "(Già presente nei Preferiti)";
            break;
        case "logout":
            window.location.reload();
            break;
        default:
            console.log("Error: " + json);
            break;

    }
}

function onJsonShowPreference(json) {

    document.querySelector("#products").innerHTML = "";

    if(json.length == 0) {
        const none = document.createElement("h1");
        none.textContent = "Nessun prodotto tra i preferiti";
        document.querySelector("#products").appendChild(none);
        return;
    }

    let product, product_info, h1, span, input;
    for (const obj of json) {
        product = document.createElement("div");
        product.classList.add("product");
        product.dataset.id = obj.id;
        document.querySelector("#products").appendChild(product);

        product_info = document.createElement("div");
        product_info.classList.add("product_info");
        input = document.createElement("input");
        input.classList.add("product_button");
        input.type = "button";
        input.value = "Rimuovi";
        input.addEventListener("click", removePreference);
        product.appendChild(product_info);
        product.appendChild(input);

        h1 = document.createElement("h1");
        h1.textContent = obj.name;
        span = document.createElement("span");
        span.textContent = "Cost: " + obj.cost + " US Cents";
        product_info.appendChild(h1);
        product_info.appendChild(span);
    }
}

function doNothing(event) {
    event.stopPropagation();
}

function modifyString(str) {
    let pos = 0;
    let ind;

    do {
        ind = str.indexOf(" ", pos);
        if (ind != -1) {
            pos = ind;
            str = str.substring(0, ind) + "%20" + str.substring(ind + 1);
        } else break;
    } while (true);

    return str;
}

/* Main Function */

function createBlocks(json) {

    /* Il JSON contiene un array di elementi con il proprio 'id', 'name', 'image' */

    const all_blocks = document.querySelector("#all_blocks");

    if (json.length == 0) {
        const noElement = document.createElement("h1");
        noElement.id = "noElement";
        if (document.querySelector("#input_content").value == "") {
            noElement.textContent = "Ricerca non valida !";
        } else
            noElement.textContent = "Nessun Prodotto Disponibile !";
        all_blocks.appendChild(noElement);
        return;
    }

    /* Creation all main blocks */
    let block, name, img, imageURL;

    for (const obj of json) {

        /* Creation Block */
        block = document.createElement("div");
        block.classList.add("block");
        block.dataset.id = obj.id;
        all_blocks.appendChild(block);

        /* Creation Name */
        name = document.createElement("h1");
        name.classList.add("name");
        name.textContent = obj.name[0].toUpperCase() + obj.name.substring(1).toLowerCase();
        block.appendChild(name);

        /* Creation Image */
        imageURL = obj.image;
        img = document.createElement("img");
        img.classList.add("image");
        img.src = imageURL;
        block.appendChild(img);
    }

    const imageList = document.querySelectorAll(".image");
    for (const image of imageList) {
        image.addEventListener("click", openModal);
    }
}

function createLateralBlocks() {
    const lateral_blockList = document.querySelectorAll(".lateral_block");
    let image;

    for (const lateral_block of lateral_blockList) {
        for (let i = 0; i < MAX_LATERAL_BLOCK; i++) {
            image = document.createElement("img");
            image.classList.add("lateral_image");
            lateral_block.appendChild(image);
        }
    }
}
