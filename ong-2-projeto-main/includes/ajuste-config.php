<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['tema'])) $_SESSION['tema'] = "claro";
if (!isset($_SESSION['fonte'])) $_SESSION['fonte'] = 16;
if (!isset($_SESSION['notificacoes'])) $_SESSION['notificacoes'] = "desativado";

// Processa requisições AJAX
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["tipo"])) {
    switch($_POST["tipo"]) {
        case "tema":
            $_SESSION["tema"] = $_POST["valor"];
            break;
        case "fonte":
            $_SESSION["fonte"] = intval($_POST["valor"]);
            break;
        case "notificacoes":
            $_SESSION["notificacoes"] = $_POST["valor"];
            break;
    }
    echo "ok";
    exit;
}
?>

<style>


body,
label, p, span, a, h1, h2, h3, input, select {
    transition: background 0.25s ease, color 0.25s ease;
}


body.tema-claro {
    background: #f5f7fa;
    color: #1f2a44;
}


.tema-claro h1,
.tema-claro h2,
.tema-claro h3,
.tema-claro label,
.tema-claro p,
.tema-claro .info{
    color: #1e2a3d;
}


.tema-claro .caixa-ajustes {
    background: #ffffff;
    border: 1px solid #d8dde6;
    color: #1e2a3d;
}


.tema-claro select,
.tema-claro .display-fonte {
    background: #ffffff;
    border: 1px solid #bcc4d3;
    color: #1e2a3d;
}


.tema-claro .btn-fonte,
.tema-claro .btn-salvar {
    color: #ffffff;
}


body.tema-escuro {
    background: #1d232d;   
    color: #eef2f7;
}


.tema-escuro h1,
.tema-escuro h2,
.tema-escuro h3,
.tema-escuro label,
.tema-escuro p,
.tema-escuro i,
.tema-escuro legend{
    color: #f2f4f8;
}


.tema-escuro .caixa-ajustes{
    background: #2a3342;
    border: 1px solid #3a4555;
    color: #eef2f7;
}

.tema-escuro form label,
.tema-escuro form .txtTitle,
.tema-escuro form p,
.tema-escuro .form-grid h2,
.tema-escuro .form-grid form span{
    color: #23467aff;
}

.tema-escuro .voluntario-container-form form h2,
.tema-escuro .voluntario-container-form form .spanDispo {
    color: #1e3768ff;
}

.tema-escuro .calendario-box  h3{
    color: #1e3768ff;  
}

.tema-escuro .container h2{
      color: #1e3768ff; 
}

.tema-escuro .funcionario-container-form form h2,
.tema-escuro .funcionario-container-form form .spanDispo {
    color: #1e3768ff;
}

.tema-escuro .estoque-container-form form h2,
.tema-escuro .estoque-container-form p {
    color: #1e3768ff;
}

.tema-escuro .caixa-ajustes .ajuste-item label {
    color: #f2f4f8;
}

.tema-escuro select,
.tema-escuro .display-fonte {
    background: #3a4455;
    border: 1px solid #4b566d;
    color: #f2f4f8;
}

.tema-escuro .btn-fonte,
.tema-escuro .btn-salvar {
    color: #ffffff;
}

.tema-escuro .popup-animal p, 
.tema-escuro .popup-animal strong,
.tema-escuro .popup-animal h2 {
    color: #0d305cff;
}

</style>

<script>
document.addEventListener("DOMContentLoaded", () => {

    function salvarSessao(tipo, valor) {
        return fetch("includes/ajuste-config.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `tipo=${tipo}&valor=${valor}`,
            credentials: "same-origin"
        }).then(res => res.text());
    }

   
    document.body.classList.add("tema-<?php echo $_SESSION['tema']; ?>");
    document.body.style.fontSize = "<?php echo $_SESSION['fonte']; ?>px";

    
    const selectTema = document.getElementById("tema");
    if (selectTema) {
        selectTema.value = "<?php echo $_SESSION['tema']; ?>";
        selectTema.addEventListener("change", () => {
            document.body.classList.toggle("tema-claro", selectTema.value === "claro");
            document.body.classList.toggle("tema-escuro", selectTema.value === "escuro");
            salvarSessao("tema", selectTema.value);
        });
    }

    
    const selectNotif = document.getElementById("notificacoes");
    if (selectNotif) {
        selectNotif.value = "<?php echo $_SESSION['notificacoes']; ?>";
        selectNotif.addEventListener("change", () => {
            salvarSessao("notificacoes", selectNotif.value);
            if (selectNotif.value === "ativado") {
                Notification.requestPermission().then(perm => {
                    if (perm === "granted") {
                        new Notification("Notificações ativadas!", {
                            body: "Agora você receberá alertas do sistema."
                        });
                    } else {
                        alert("Você bloqueou as notificações.");
                        selectNotif.value = "desativado";
                        salvarSessao("notificacoes", "desativado");
                    }
                });
            }
        });
    }

  
    const btnMenos = document.getElementById("diminuirFonte");
    const btnMais = document.getElementById("aumentarFonte");
    const display = document.getElementById("tamanhoFonte");

    if (btnMenos && btnMais && display) {
        let tamanho = parseInt(display.value.replace("px", "")), min = 12, max = 26;

        function aplicarFonte() {
            display.value = tamanho + "px";
            document.querySelectorAll("label, p, a, span, h1, h2, h3").forEach(el => {
                el.style.fontSize = tamanho + "px";
            });
        }

        btnMais.addEventListener("click", () => {
            if (tamanho < max) {
                tamanho++;
                aplicarFonte();
                salvarSessao("fonte", tamanho);
            }
        });

        btnMenos.addEventListener("click", () => {
            if (tamanho > min) {
                tamanho--;
                aplicarFonte();
                salvarSessao("fonte", tamanho);
            }
        });

        aplicarFonte();
    }

   
    document.querySelector(".form-ajustes")?.addEventListener("submit", e => {
        e.preventDefault();
        const tema = document.getElementById("tema")?.value;
        const notif = document.getElementById("notificacoes")?.value;
        const fonte = parseInt(document.getElementById("tamanhoFonte")?.value.replace("px", ""));
        const promessas = [];
        if (tema) promessas.push(salvarSessao("tema", tema));
        if (notif) promessas.push(salvarSessao("notificacoes", notif));
        if (fonte) promessas.push(salvarSessao("fonte", fonte));
        Promise.all(promessas)
            .then(() => alert("Configurações salvas com sucesso!"))
            .catch(err => console.error(err));
    });

});
</script>
