<?php
require_once "../config.php";
requiere_login();

$pdo = conectar();
$errores = [];
$ok = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_requerir();

    $actual = (string)($_POST["actual"] ?? "");
    $nueva = (string)($_POST["nueva"] ?? "");
    $repetir = (string)($_POST["repetir"] ?? "");

    if ($actual === "" || $nueva === "" || $repetir === "") {
        $errores[] = "Completá todos los campos.";
    }
    if (strlen($nueva) < 8) {
        $errores[] = "La contraseña nueva debe tener al menos 8 caracteres.";
    }
    if ($nueva !== $repetir) {
        $errores[] = "La contraseña nueva y su repetición no coinciden.";
    }
    if ($nueva !== "" && $nueva === $actual) {
        $errores[] = "La contraseña nueva debe ser distinta de la actual.";
    }

    if (count($errores) === 0) {
        $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = ?");
        $stmt->execute([(int)$_SESSION["admin_id"]]);
        $hash = $stmt->fetchColumn();

        if (!$hash || !password_verify($actual, $hash)) {
            $errores[] = "La contraseña actual no es correcta.";
        } else {
            $nuevo_hash = password_hash($nueva, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?")
                ->execute([$nuevo_hash, (int)$_SESSION["admin_id"]]);
            session_regenerate_id(true);
            $ok = true;
        }
    }
}

$titulo = "Mi cuenta";
require_once "_header.php";
?>
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-1">Cambiar contraseña</h2>
                    <p class="text-muted small mb-4">Usuario: <strong><?= htmlspecialchars($_SESSION["admin_usuario"] ?? "") ?></strong></p>

                    <?php if ($ok): ?>
                        <div class="alert alert-success py-2 small">Contraseña actualizada. Guardala en un lugar seguro.</div>
                    <?php endif; ?>

                    <?php foreach ($errores as $e): ?>
                        <div class="alert alert-danger py-2 small"><?= htmlspecialchars($e) ?></div>
                    <?php endforeach; ?>

                    <form method="post" autocomplete="off">
                        <?= csrf_campo() ?>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold" for="actual">Contraseña actual</label>
                            <input type="password" class="form-control" id="actual" name="actual" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold" for="nueva">Contraseña nueva</label>
                            <input type="password" class="form-control" id="nueva" name="nueva" minlength="8" required>
                            <div class="form-text">Mínimo 8 caracteres.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold" for="repetir">Repetir contraseña nueva</label>
                            <input type="password" class="form-control" id="repetir" name="repetir" minlength="8" required>
                        </div>
                        <button type="submit" class="btn btn-dark fw-semibold w-100">Guardar contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php require_once "_footer.php"; ?>
