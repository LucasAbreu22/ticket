<?php $this->layout("_theme", ["title" => $title]); ?>

<div id="app">
    <div id="cards">
        <div id="card-senha">
            <h1>Senha atual</h1>
            <h2>Código: {{senhaChamada["codigo"]}}</h2>
            <h2>Nome: {{senhaChamada["representante"]}}</h2>
        </div>
        <div id="card-my-senha">
            <div>

                <h2>Sua senha: {{senha}}</h2>
            </div>
            <input type="text" name="Nome do solicitante" id="nome-solicitante" />
            <div>
                <button @click="gerarTicket">Solicitar ticket</button>
                <button @click="getSenhaByNome">Buscar ticket</button>
            </div>
        </div>
    </div>
    <div>
        <div>
            <br>
            <br>
            <span><b>Útilmas senhas chamadas:</b></span>
            <ol>
                <li v-for="ultimaSenha in ultimasSenhas">
                    {{ultimaSenha["codigo"]}} - {{ultimaSenha["representante"]}}
                </li>
            </ol>
        </div>
    </div>
</div>

<?php $this->start("js"); ?>
<script>
    const {
        createApp,
        ref,
        onMounted
    } = Vue

    createApp({
        setup() {
            const senhaChamada = ref(0);
            const senha = ref("");
            const ultimasSenhas = ref([]);
            let status = false;

            function notify() {
                const audio = new Audio("<?= url('/theme/assets/sounds/notify.mp3') ?>");
                audio.play();
            }

            function carregarFila() {
                setInterval(() => {
                    $.ajax({
                        type: "POST",
                        url: "<?= url("/fila/getFila") ?>",
                        dataType: "json",
                        success: function(response) {
                            ultimasSenhas.value = response.data;
                        },
                    }).fail(function(error) {
                        console.error(error.responseJSON.mensagem);
                    }).done(() => {

                        setInterval(() => {
                            ocultarLoading();
                        }, 500);

                    });
                }, 5000);
            }

            function carregarSenhaChamada() {
                setInterval(() => {
                    if (!status) return false;

                    $.ajax({
                        type: "POST",
                        url: "<?= url("/fila/carregarSenhaChamada") ?>",
                        dataType: "json",
                        success: function(response) {
                            if (senhaChamada.value.codigo != response.data.codigo) {
                                notify();

                                senhaChamada.value = response.data;
                            }
                        },
                    }).fail(function(error) {
                        console.error(error.responseJSON.mensagem);
                    }).done(() => {

                        setInterval(() => {
                            ocultarLoading();
                        }, 500);

                    });
                }, 5000);
            }

            function getSenhaByNome() {
                const representante = document.getElementById("nome-solicitante").value.trim();

                $.ajax({
                    type: "POST",
                    url: "<?= url("/fila/getSenhaByNome") ?>",
                    data: {
                        representante: representante
                    },
                    dataType: "json",
                    success: function(response) {

                        if (!response.data) senha.value = "Sem senha!";
                        else senha.value = response.data.codigo;
                        status = true;
                    },
                }).fail(function(error) {
                    console.error(error.responseJSON.mensagem);
                }).done(() => {

                    setInterval(() => {
                        ocultarLoading();
                    }, 500);

                });
            }

            function gerarTicket() {
                const representante = document.getElementById("nome-solicitante").value.trim();

                $.ajax({
                    type: "POST",
                    url: "<?= url("/fila/gerarTicket") ?>",
                    data: {
                        representante: representante
                    },
                    dataType: "json",
                    success: function(response) {
                        senha.value = response.data;
                        status = true;

                    },
                }).fail(function(error) {
                    console.error(error.responseJSON.mensagem);
                }).done(() => {

                    setInterval(() => {
                        ocultarLoading();
                    }, 500);

                });
            }

            onMounted(() => {
                carregarFila();
                carregarSenhaChamada();
            });

            return {
                gerarTicket,
                senhaChamada,
                getSenhaByNome,
                senha,
                ultimasSenhas
            }
        }
    }).mount('#app')
</script>
<?php $this->end("js"); ?>