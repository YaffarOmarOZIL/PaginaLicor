<?php
session_start();
require_once "config.php";

// Verificar que el usuario es un administrador
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: login.php");
    exit;
}

// Obtener productos para el formulario
$sql = "SELECT id, nombre, precio FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Pedido - Licorería</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            color: white;
            background-color: #4CAF50;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover { background-color: #45a049; }
    </style>
    <script>
        function updateTotal() {
            let productos = document.getElementsByName('productos[]');
            let cantidades = document.getElementsByName('cantidad[]');
            let total = 0;

            for (let i = 0; i < productos.length; i++) {
                let precio = productos[i].options[productos[i].selectedIndex].dataset.precio;
                let cantidad = cantidades[i].value;
                total += precio * cantidad;
            }

            document.getElementById('total').value = total.toFixed(2);
        }

        function validateForm() {
            let total = parseFloat(document.getElementById('total').value);
            let efectivo = parseFloat(document.getElementById('efectivo').value);

            if (efectivo < total) {
                alert('El monto en efectivo es menor que el total. Por favor, ingrese un monto suficiente.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h1>Registrar Pedido</h1>
    <form action="admin_pedidos.php" method="post" onsubmit="return validateForm();">
        <label for="cliente_nombre">Nombre del Cliente:</label>
        <input type="text" id="cliente_nombre" name="cliente_nombre" required><br><br>
        
        <h3>Productos:</h3>
        <div id="productos">
            <div class="producto">
                <select name="productos[]" onchange="updateTotal();" required>
                    <option value="">Seleccionar producto</option>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" data-precio="<?php echo $row['precio']; ?>">
                            <?php echo htmlspecialchars($row['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="number" name="cantidad[]" min="1" value="1" onchange="updateTotal();" required>
            </div>
        </div>
        <button type="button" onclick="addProduct()">Agregar otro producto</button><br><br>
        
        <label for="total">Total:</label>
        <input type="text" id="total" name="total" readonly><br><br>
        
        <label for="efectivo">Monto en Efectivo:</label>
        <input type="number" id="efectivo" name="efectivo" step="0.01" min="0" required><br><br>
        
        <button type="submit">Registrar Pedido</button>
    </form>
    
    <a href="admin.php" class="button">Volver al Panel de Administración</a>

    <script>
        function addProduct() {
            var div = document.createElement('div');
            div.classList.add('producto');
            div.innerHTML = `
                <select name="productos[]" onchange="updateTotal();" required>
                    <option value="">Seleccionar producto</option>
                    <?php
                    $result->data_seek(0); // Resetear el puntero de resultados
                    while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" data-precio="<?php echo $row['precio']; ?>">
                            <?php echo htmlspecialchars($row['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="number" name="cantidad[]" min="1" value="1" onchange="updateTotal();" required>
            `;
            document.getElementById('productos').appendChild(div);
            updateTotal();
        }
    </script>
</body>
</html>
