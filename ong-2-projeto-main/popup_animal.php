<?php
require 'config.php';

if (!isset($_GET['id'])) {
    echo "Animal não encontrado.";
    exit;
}

$id = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("SELECT * FROM animal WHERE id = ?");
    $stmt->execute([$id]);
    $animal = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$animal) {
        echo "Animal não encontrado.";
        exit;
    }

} catch (PDOException $e) {
    echo "Erro ao buscar animal: " . $e->getMessage();
    exit;
}

?>

<div class="popup-animal" id="popup-animal">
    <span class="close-popup">&times;</span>
      <img 
        src="<?php echo !empty($animal['foto']) ? htmlspecialchars($animal['foto']) : 'https://images.vexels.com/media/users/3/144928/isolated/preview/ebbccaf76f41f7d83e45a42974cfcd87-ilustracao-de-cachorro.png'; ?>" 
        alt="Foto de <?php echo htmlspecialchars($animal['nome']); ?>" 
        width="100" style="height: 100px ;border-radius: 100%; border: 1px solid #a7b5f0ff;"
        onerror="this.onerror=null; this.src='https://images.vexels.com/media/users/3/144928/isolated/preview/ebbccaf76f41f7d83e45a42974cfcd87-ilustracao-de-cachorro.png';">
        

    <h2><?php echo htmlspecialchars($animal['nome']); ?></h2>
    <p><strong>Tipo:</strong> <?php echo htmlspecialchars($animal['tipo_animal']); ?></p>
    <p><strong>Raça:</strong> <?php echo htmlspecialchars($animal['raca']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($animal['status']); ?></p>
    <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($animal['descricao'])); ?></p>
</div>

<style>
.popup-animal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.3);
    z-index: 9999;
    width: 300px;
    max-width: 90%;
    text-align: center;
    display: none; 
}
.popup-animal img.popup-foto {
    width: 120px;
    height: 120px;
    border-radius: 100%;
    object-fit: cover;
    margin-bottom: 15px;
}

.popup-animal .close-popup {
    position: absolute;
    top: 8px;
    right: 12px;
    font-size: 20px;
    cursor: pointer;
    color: #333;
}

.popup-animal p {
    text-align: left;
    margin: 8px 0;
    color: #193661ff;
}
</style>

<script>
document.getElementById('open-popup').addEventListener('click', () => {
    document.getElementById('popup-animal').style.display = 'block';
});

document.querySelector('.close-popup').addEventListener('click', () => {
    document.getElementById('popup-animal').style.display = 'none';
});


window.addEventListener('click', (event) => {
    const popup = document.getElementById('popup-animal');
    if (event.target === popup) {
        popup.style.display = 'none';
    }
});
</script>
