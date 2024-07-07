<?php
include 'conexion.php';
include 'navbar.php';


if (!isset($_SESSION['user_token'])) {
    header("Location: index.php");
    exit();
}

// Obtener el token del usuario actual
$token = $_SESSION['user_token'];

// Realizar la consulta SQL para obtener el ID del cliente
$query = "SELECT id FROM cliente WHERE token=?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $cliente_id = $row['id'];
} else {
    echo "Error: No se encontraron datos del usuario.";
    exit;
}

// Realizar la consulta SQL para obtener las compras del cliente
$query = "
    SELECT v.producto_id, v.cantidad, v.precio_unitario, v.total, p.nombre AS producto_nombre, 
           p.imagenes, p.tallas_seleccionadas, c.nombre AS categoria_nombre, 
           m.nombre AS marca_nombre, sc.nombre AS subcategoria_nombre
    FROM ventas v
    JOIN producto p ON v.producto_id = p.id
    JOIN categoria c ON p.categoria_id = c.id
    JOIN marca m ON p.marca_id = m.id
    JOIN subcategoria sc ON p.subcategoria_id = sc.id
    WHERE v.cliente_id = ?
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $cliente_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<style>
        .compra {
            display: flex;
            flex-wrap: wrap;
            border: 1px solid #007bff;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 10px;
        }
        .compra img {
            width: 100px;
            margin-right: 20px;
            border-radius: 8px;
        }
        .detalle {
            display: flex;
            flex-wrap: wrap;
            flex: 1;
        }
        .detalle div {
            border: 1px solid #007bff;
            padding: 10px;
            margin: 5px;
            flex: 1 1 calc(33.333% - 20px);
            box-sizing: border-box;
            border-radius: 8px;
        }
        .detalle div span {
            display: block;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
    </style>

<div class="container mt-5">
    <div class="border-bottom text-center">
        <h2 class="mt-2 mt-md-4 mb-3 pt-3">MIS COMPRAS</h2>
        <p class="text-muted">Aquí puedes observar las compras que realizaste</p>
    </div>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="compra">
                <img src="<?php echo htmlspecialchars($row['imagenes']); ?>" alt="Imagen del producto">
                <div class="detalle">
                    <div>
                        <strong>PRODUCTO</strong><br>
                        <?php echo htmlspecialchars($row['producto_nombre']); ?>
                    </div>
                    <div>
                        <strong>MARCA</strong><br>
                        <?php echo htmlspecialchars($row['marca_nombre']); ?>
                    </div>
                    <div>
                        <strong>CATEGORÍA</strong><br>
                        <?php echo htmlspecialchars($row['categoria_nombre']); ?>
                    </div>
                    <div>
                        <strong>PRECIO UNITARIO</strong><br>
                        <?php echo htmlspecialchars($row['precio_unitario']); ?>
                    </div>
                    <div>
                        <strong>CANTIDAD</strong><br>
                        <?php echo htmlspecialchars($row['cantidad']); ?>
                    </div>
                    <div>
                        <strong>TOTAL</strong><br>
                        <?php echo htmlspecialchars($row['total']); ?>
                    </div>
                    <div>
                        <strong>SUBCATEGORÍA</strong><br>
                        <?php echo htmlspecialchars($row['subcategoria_nombre']); ?>
                    </div>
                    <div>
                        <strong>TALLAS SELECCIONADAS</strong><br>
                        <?php echo htmlspecialchars($row['tallas_seleccionadas']); ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
</div><br><br><br><br>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<?php include 'footer.php'; ?>
