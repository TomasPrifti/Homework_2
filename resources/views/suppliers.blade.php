<!DOCTYPE html>
<html>

<head>
    <title>Fornitori - Catena di Ristoranti</title>
    <link rel="stylesheet" href="css/sharedAspect.css">
    <link rel="stylesheet" href="css/sharedUser.css">
    <link rel="stylesheet" href="css/suppliers.css" />
    <link href="https://fonts.googleapis.com/css2?family=Dela+Gothic+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Economica&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cherry+Swash&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Port+Lligat+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Arbutus&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="js/sharedMenu.js" defer></script>
    <script src="js/suppliers.js" defer></script>
</head>

<body>
    <header>
        <nav>
            <a href="./home"><strong>HOME</strong></a>
            <a href="./restaurants"><strong>RISTORANTI</strong></a>
            <a href=""><strong>FORNITORI</strong></a>
            <a href="./products"><strong>PRODOTTI</strong></a>
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
            <strong>FORNITORI</strong>
            <div id="login">
                <span id="title_login">
                    @if (!$existUserSup && !$existUserRes)
                        Sei un Fornitore ?
                    @else
                        Benvenuto {{ $user != null ? $user->username : "" }} !
                    @endif
                </span>
                <div>
                    @if (!$existUserRes)
                        <input id= {{ $existUserSup ? "profile" : "input_login" }} , type="button", value= {{ $existUserSup ? "Profilo" : "Accedi" }} >
                        <span>oppure</span>
                        <input id= {{ $existUserSup ? "logout" : "input_register" }} , type="button" , value={{ $existUserSup ? "Logout" : "Registrati" }}>
                    @else
                        <input id="logout_res", type="button", value="Logout">
                    @endif
                </div>
            </div>
        </div>

    </header>

    <section>

        <h1 id="title_blocks">Tutti i Fornitori</h1>
        <div id="input_block">Cerca:<input id="input_search" , type="text"></div>

        <!--
        <div class="block">
            <h1 class="name">Nome</h1>
            <span class="address">Indirizzo</span>
        </div>
     -->

        <div id="modal" , class="hidden">

            <div id="modal_menu", class="hidden">
                <a href="./home"><strong>HOME</strong></a>
                <a href="./restaurants"><strong>RISTORANTI</strong></a>
                <a href=""><strong>FORNITORI</strong></a>
                <a href="./products"><strong>PRODOTTI</strong></a>
            </div>

            <div id="modal_block", class="hidden">

                <strong id="modal_title">
                    <!-- Title -->
                </strong>

                <div id="modal_content_login" , class="hidden">
                    <form id="form_login", enctype="multipart/form-data", method="post", action="{{ route('form_login') }}">
                        @csrf
                        <div id="block_login_username" , class="fields">
                            <label>Username:<input id="login_username" , type="text"></label>
                            <span class="error"></span>
                        </div>
                        <div id="block_login_password" , class="fields">
                            <label>Password:<input id="login_password" , type="password"></label>
                            <span class="error"></span>
                        </div>
                    </form>
                </div>

                <div id="modal_content_register" , class="hidden">
                    <form id="form_register", enctype="multipart/form-data", method="post", action="{{ route('form_register') }}">
                        @csrf
                        <div id="block_register_username" , class="fields">
                            <em>(Sono ammesse lettere e numeri - Massimo 15 caratteri)</em>
                            <label>Username:<input id="register_username" , type="text"></label>
                            <span class="error"></span>
                        </div>
                        <div id="block_register_email" , class="fields">
                            <em>(Esempio: nomefornitore@example.com)</em>
                            <label>E-mail:<input id="register_email" , type="text"></label>
                            <span class="error"></span>
                        </div>
                        <div id="block_register_password" , class="fields">
                            <em>
                                (Lunghezza minima 8 caratteri e massima 15 caratteri)<br>
                                (Presenza di almeno un carattere maiuscolo e minuscolo)<br>
                                (Presenza di almeno un numero)<br>
                                (Presenza di almeno un simbolo)
                            </em>
                            <label>Password:<input id="register_password" , type="password"></label>
                            <span class="error"></span>
                        </div>
                        <div id="block_register_confirm_password" , class="fields">
                            <em>(Inserire nuovamente la password)</em>
                            <label>Conferma password:<input id="register_confirm_password" , type="password"></label>
                            <span class="error"></span>
                        </div>
                        <div class="fields">
                            <em>(Facoltativo) Nota: Se vuoto non verr?? mostrato in elenco</em>
                            <label>Nome del Fornitore:<input id="register_name" , type="text"></label>
                        </div>
                        <div class="fields">
                            <em>(Facoltativo)</em>
                            <label>Indirizzo del Fornitore:<input id="register_address" , type="text"></label>
                        </div>
                    </form>
                </div>

                <div id="modal_content_profile" , class="hidden">
                    <form id="form_profile", enctype="multipart/form-data", method="post", action="{{ route('form_update') }}", class="fields">
                        @csrf
                        <label>Nome del Fornitore:<input id="profile_name" , type="text" , value="{{ $user != null ? $user->name : "" }}"></label>
                        <label>Indirizzo del Fornitore:<input id="profile_address" , type="text" , value="{{ $user != null ? $user->address : "" }}"></label>
                    </form>
                </div>

                <div id="buttons">
                    <div id="modal_error" , class="error hidden">Errore verifica i campi</div>
                    <input id="modal_button" , type="button">
                    @if ($existUserSup)
                        <input id='delete_profile', type='button', value='Elimina profilo'>
                        <span></span>
                    @endif
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
