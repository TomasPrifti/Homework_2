<!DOCTYPE html>
<html>

<head>
    <title>Prodotti - Catena di Ristoranti</title>
    <link rel="stylesheet" href="css/sharedAspect.css">
    <link rel="stylesheet" href="css/sharedUser.css">
    <link rel="stylesheet" href="css/products.css" />
    <link href="https://fonts.googleapis.com/css2?family=Dela+Gothic+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Economica&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cherry+Swash&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Arbutus&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="js/sharedMenu.js" defer></script>
    <script src="js/products.js" defer></script>
</head>

<body>
    <header>
        <nav>
            <a href="./home"><strong>HOME</strong></a>
            <a href="./restaurants"><strong>RISTORANTI</strong></a>
            <a href="./suppliers"><strong>FORNITORI</strong></a>
            <a href=""><strong>PRODOTTI</strong></a>
        </nav>
        <div id="button_menu">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <div id="overlay">
            <!-- Overlay -->
        </div>
        <div id="id_1">
            <strong>Catena di<br>Ristoranti</strong>
        </div>
        <div id="id_2">
            <strong>PRODOTTI</strong>
            @if ($existUserRes || $existUserSup)
                <div id="login">
                <span id="title_login">Benvenuto {{ $user != null ? $user->username : "" }} !</span>
                    <div>
                    @if ($existUserRes)
                        <input id="preference", type="button", value="Preferiti">
                    @endif
                        <input id="logout", type="button", value="Logout">
                    </div>
                </div>
            @endif
        </div>

    </header>

    <section>

        <div class="lateral_block">
            <!-- <img src=""> -->
        </div>

        <div id="main_block">

            <h1 id="title_blocks">
                Tutti i Prodotti
            </h1>

            <div id="input_block">
                <input id="input_images" , type="button" , value="Nuove Immagini">
                <form id="form_input">
                    <span>Cerca:</span>
                    <input id="input_content" , type="text">
                    <input id="input_button" , type="submit" , value="Cerca">
                </form>
            </div>

            <div id="all_blocks">
                <!--
                <div class="block">
                    <h1 class="name">

                    </h1>
                    <img class="image" src="">
                </div>
                 -->
            </div>

        </div>

        <div class="lateral_block">
            <!-- <img src=""> -->
        </div>

        <div id="modal" , class="hidden">

            <div id="modal_menu", class="hidden">
                <a href="./home"><strong>HOME</strong></a>
                <a href="./restaurants"><strong>RISTORANTI</strong></a>
                <a href="./suppliers"><strong>FORNITORI</strong></a>
                <a href=""><strong>PRODOTTI</strong></a>
            </div>

            <div id="modal_block" , class="hidden">
                <img id="modal_image" src="">
                <div id="modal_content">
                    <h1 id="modal_name"></h1>
                    <div id="modal_text"></div>
                    @if ($existUserRes)
                        <form id="form_modal_preference", enctype="multipart/form-data", method="post", action="{{ route('form_preference') }}">
                            @csrf
                            <input id='modal_preference', type='button', value='Aggiungi ai Preferiti'>
                            <span></span>
                        </form>
                    @endif
                </div>
            </div>

            <div id="modal_block_preference" , class="hidden">
                <h1 id="modal_title">Prodotti Preferiti</h1>
                <div id="products">
                    <!--
                        <div class="product">
                            <div class="product_info">
                                <h1>Nome</h1>
                                <span>Costo</span>
                            </div>
                            <input class="product_button", type="button", value="Rimuovi">
                        </div>
                    -->
                </div>
            </div>

        </div>

    </section>

    <footer>
        <div>
            Seguici anche sui social !<br>
            <em>Powered by<br>Prifti Tomas O46002191</em>
        </div>
    </footer>
</body>

</html>
