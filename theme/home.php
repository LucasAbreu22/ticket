<?php $this->layout("_theme", ["title" => $title]); ?>

<div id="app">
    <div id="card-senha">
        <h1>Senha atual</h1>
        <h2>Código: {{senhaChamada["codigo"]}}</h2>
        <h2>Nome: {{senhaChamada["representante"]}}</h2>
    </div>
    <br>
    <h3>Status: {{status ? "Ativado" : "Desativado"}}</h3>
    <br>
    <button @click="enableSearch">{{!status ? "Ativar" : "Desativar"}} balcão</button>
    <button @click="proxSenha">Próxima senha</button>
</div>

<?php $this->start("js"); ?>
<script>
    const {
        createApp,
        ref,
        onMounted,
    } = Vue

    createApp({
        setup() {
            const senhaChamada = ref(0);
            const status = ref(false);

            function notify() {
                const audio = new Audio("<?= url('/theme/assets/sounds/notify.mp3') ?>");
                audio.play();
            }

            function carregarSenhaChamada() {
                setInterval(() => {
                    if (!status.value) return false;

                    $.ajax({
                        type: "POST",
                        url: "<?= url("/fila/carregarSenhaChamada") ?>",
                        dataType: "json",
                        success: function(response) {
                            if (response.data && response.data.codigo != senhaChamada.value.codigo) {
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

            function proxSenha() {
                $.ajax({
                    type: "POST",
                    url: "<?= url("/") ?>",
                    dataType: "json",
                    data: {
                        senhaAtual: senhaChamada.value["codigo"]
                    },
                    success: function(response) {
                        if (response.data) {
                            notify();
                        }

                        senhaChamada.value = response.data;
                    },
                }).fail(function(error) {
                    console.error(error.responseJSON.mensagem);
                }).done(() => {

                    setInterval(() => {
                        ocultarLoading();
                    }, 500);

                });
            }

            function enableSearch() {
                status.value = !status.value;
            }

            onMounted(() => {
                carregarSenhaChamada()
            });

            return {
                senhaChamada,
                proxSenha,
                enableSearch,
                status
            }
        }
    }).mount('#app')
</script>
<?php $this->end("js"); ?>