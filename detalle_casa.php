<?php
// detalle_casa.php?id=2
$casa_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Datos de ejemplo para cada casa (puedes reemplazar por consulta a BD si lo deseas)
$casas = [
    1 => [
        'titulo' => 'Casa Prefabricada 21 m²',
        'imagen' => 'IMG/casa1.jpg',
        'precio' => 'Desde $1.940.000',
        'dormitorios' => 1,
        'banos' => 1,
        'superficie' => '21 m²',
        'descripcion' => 'Modelo compacto ideal para parejas o personas solas. Diseño eficiente y funcional.'
    ],
    2 => [
        'titulo' => 'Casa Prefabricada 36 m²',
        'imagen' => 'IMG/casa2.jpg',
        'precio' => 'Desde $3.390.000',
        'dormitorios' => 2,
        'banos' => 1,
        'superficie' => '36 m²',
        'descripcion' => 'Modelo familiar pequeño, perfecto para familias jóvenes o como segunda vivienda.'
    ],
    3 => [
        'titulo' => 'Casa Prefabricada 48 m²',
        'imagen' => 'IMG/casa3.jpg',
        'precio' => 'Desde $4.338.000',
        'dormitorios' => 2,
        'banos' => 1,
        'superficie' => '48 m²',
        'descripcion' => 'Casa de mayor tamaño, ideal para familias que buscan más espacio y comodidad.'
    ],
    4 => [
        'titulo' => 'Casa Prefabricada 54 m²',
        'imagen' => 'IMG/casa4.jpg',
        'precio' => 'Desde $4.698.000',
        'dormitorios' => 2,
        'banos' => 1,
        'superficie' => '54 m²',
        'descripcion' => 'Modelo eficiente y funcional, perfecto para familias pequeñas.'
    ],
    5 => [
        'titulo' => 'Casa Prefabricada 66 m²',
        'imagen' => 'IMG/casa5.jpg',
        'precio' => 'Desde $5.400.000',
        'dormitorios' => 2,
        'banos' => 1,
        'superficie' => '60 m²',
        'descripcion' => 'Casa cómoda y moderna, ideal para familias que buscan confort.'
    ],
    6 => [
        'titulo' => 'Casa Prefabricada 60 m²',
        'imagen' => 'IMG/casa6.jpg',
        'precio' => 'Desde $5.610.000',
        'dormitorios' => 3,
        'banos' => 1,
        'superficie' => '60 m²',
        'descripcion' => 'Modelo con tres dormitorios, ideal para familias en crecimiento.'
    ],
    7 => [
        'titulo' => 'Casa Prefabricada 72 m²',
        'imagen' => 'IMG/casa7.jpg',
        'precio' => 'Desde $6.120.000',
        'dormitorios' => 2,
        'banos' => 2,
        'superficie' => '72 m²',
        'descripcion' => 'Casa con dos baños, perfecta para mayor comodidad.'
    ],
    8 => [
        'titulo' => 'Casa Prefabricada 80 m²',
        'imagen' => 'IMG/casa8.jpg',
        'precio' => 'Desde $4.338.000',
        'dormitorios' => '3-2',
        'banos' => 2,
        'superficie' => '80 m²',
        'descripcion' => 'Modelo versátil con opción de 2 o 3 dormitorios.'
    ],
    9 => [
        'titulo' => 'Casa Prefabricada 90 m² Tradicional',
        'imagen' => 'IMG/casa9.jpg',
        'precio' => 'Desde $7.650.000',
        'dormitorios' => 3,
        'banos' => 2,
        'superficie' => '90 m²',
        'descripcion' => 'Estilo tradicional, amplia y luminosa.'
    ],
    10 => [
        'titulo' => 'Casa Prefabricada 90 m² Mediterránea',
        'imagen' => 'IMG/casa10.jpg',
        'precio' => 'Desde $7.650.000',
        'dormitorios' => 3,
        'banos' => 2,
        'superficie' => '90 m²',
        'descripcion' => 'Diseño mediterráneo, elegante y funcional.'
    ],
    11 => [
        'titulo' => 'Casa Prefabricada 117 m²',
        'imagen' => 'IMG/casa11.jpg',
        'precio' => 'Desde $9.880.000',
        'dormitorios' => 4,
        'banos' => 2,
        'superficie' => '100 m²',
        'descripcion' => 'Casa grande para familias numerosas.'
    ],
    12 => [
        'titulo' => 'Casa Prefabricada 126 m²',
        'imagen' => 'IMG/casa12.jpg',
        'precio' => 'Desde $10.710.000',
        'dormitorios' => 4,
        'banos' => 2,
        'superficie' => '126 m²',
        'descripcion' => 'Espaciosa y moderna, con acabados de calidad.'
    ],
    13 => [
        'titulo' => 'Casa Prefabricada 156 m²',
        'imagen' => 'IMG/casa13.jpg',
        'precio' => 'Desde $12.900.000',
        'dormitorios' => 4,
        'banos' => 3,
        'superficie' => '120 m²',
        'descripcion' => 'Ideal para familias grandes, con tres baños.'
    ],
    14 => [
        'titulo' => 'Casa Prefabricada 130 m² (2 pisos)',
        'imagen' => 'IMG/casa14.jpg',
        'precio' => 'Desde $15.568.000',
        'dormitorios' => 3,
        'banos' => 2,
        'superficie' => '130 m²',
        'descripcion' => 'Casa de dos pisos, moderna y elegante.'
    ],
    15 => [
        'titulo' => 'Casa Prefabricada 166 m²',
        'imagen' => 'IMG/casa15.jpg',
        'precio' => 'Desde $13.600.000',
        'dormitorios' => 4,
        'banos' => 2,
        'superficie' => '120 m²',
        'descripcion' => 'Gran espacio y comodidad para toda la familia.'
    ],
    16 => [
        'titulo' => 'Casa Prefabricada 190 m²',
        'imagen' => 'IMG/casa16.jpg',
        'precio' => 'Desde $15.490.000',
        'dormitorios' => 3,
        'banos' => 3,
        'superficie' => '134 m²',
        'descripcion' => 'Casa amplia con tres baños y gran terreno.'
    ],
    17 => [
        'titulo' => 'Casa Prefabricada 190 m²',
        'imagen' => 'IMG/casa17.jpg',
        'precio' => 'Desde $16.490.000',
        'dormitorios' => 3,
        'banos' => 3,
        'superficie' => '135 m²',
        'descripcion' => 'Modelo premium, máxima comodidad y espacio.'
    ],
    18 => [
        'titulo' => 'Casa Prefabricada 281 m²',
        'imagen' => 'IMG/casa18.jpg',
        'precio' => 'Desde $30.297.000',
        'dormitorios' => 6,
        'banos' => '4,5',
        'superficie' => '254 m²',
        'descripcion' => 'La más grande, ideal para proyectos familiares o comerciales.'
    ]
];

// Generar kits genéricos para todos los modelos si no existen
$kits = $kits ?? [];
for ($i = 1; $i <= 18; $i++) {
    if (!isset($kits[$i])) {
        $kits[$i] = [
            [
                'nombre' => 'Kit Estructural',
                'descripcion' => 'Incluye estructura, paneles y techumbre. Ideal para autoconstrucción.',
                'imagen' => 'IMG/kits_1.jpeg',
                'precio' => '3.000.000'
            ],
            [
                'nombre' => 'Kit Inicial',
                'descripcion' => 'Incluye estructura más ventanas, puertas y terminaciones básicas.',
                'imagen' => 'IMG/kits_1.jpeg',
                'precio' => '5.000.000'
            ],
            [
                'nombre' => 'Kit Completo',
                'descripcion' => 'Incluye todo lo anterior más radier, electricidad y flete.',
                'imagen' => 'IMG/kits_1.jpeg',
                'precio' => '7.000.000'
            ]
        ];
    }
}
$casa = $casas[$casa_id] ?? $casas[1];

$kits_casa = $kits[$casa_id] ?? [];
$kit_seleccionado = 0;
if (isset($_POST['kit'])) {
    $kit_seleccionado = intval($_POST['kit']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $casa['titulo']; ?> - Green Valley</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .detalle-container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 40px; }
        .detalle-img { width: 100%; max-width: 400px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .detalle-info { margin-left: 40px; }
        .detalle-flex { display: flex; gap: 40px; align-items: flex-start; flex-wrap: wrap; }
        .detalle-titulo { font-size: 2.2rem; color: #2c3e50; margin-bottom: 10px; }
        .detalle-precio { font-size: 1.5rem; color: #7cb342; margin-bottom: 20px; }
        .detalle-lista { list-style: none; padding: 0; margin-bottom: 20px; }
        .detalle-lista li { margin-bottom: 10px; font-size: 1.1rem; }
        .detalle-desc { color: #555; margin-bottom: 30px; }
        .btn { background: #7cb342; color: #fff; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; transition: background 0.3s; border: none; }
        .btn:hover { background: #689f38; }
        .kits-section { margin-top: 50px; }
        .kits-title { font-size: 1.5rem; color: #2c3e50; margin-bottom: 20px; }
        .kits-grid { display: flex; gap: 30px; flex-wrap: wrap; justify-content:center; }
        .kit-card { background: #f8f9fa; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 20px; flex: 1 1 220px; min-width: 220px; max-width: 300px; text-align: center; position:relative; transition: box-shadow 0.3s; cursor:pointer; border: 2px solid transparent; }
        .kit-card.selected { border: 2px solid #7cb342; box-shadow: 0 4px 16px rgba(124,179,66,0.15); }
        .kit-img { width: 100%; max-width: 180px; border-radius: 8px; margin-bottom: 15px; }
        .kit-nombre { font-weight: bold; color: #7cb342; margin-bottom: 10px; }
        .kit-desc { color: #555; font-size: 1rem; }
        .kit-radio { position:absolute; top:15px; right:15px; transform:scale(1.3); }
        .kit-btn { margin-top: 25px; background: #7cb342; color: #fff; padding: 10px 28px; border-radius: 25px; border:none; font-weight:bold; font-size:1rem; cursor:pointer; transition: background 0.3s; }
        .kit-btn:hover { background: #689f38; }
        .kit-selected-info { margin-top: 30px; background: #eafbe7; border-left: 5px solid #7cb342; padding: 18px 25px; border-radius: 8px; color: #2c3e50; font-size: 1.1rem; }
    </style>
    <script>
    function selectKit(idx) {
        document.getElementById('kitFormKit').value = idx;
        document.getElementById('kitForm').submit();
    }
    </script>
</head>
<body>
    <div class="detalle-container">
        <div class="detalle-flex">
            <img src="<?php echo $casa['imagen']; ?>" alt="<?php echo $casa['titulo']; ?>" class="detalle-img">
            <div class="detalle-info">
                <h1 class="detalle-titulo"><?php echo $casa['titulo']; ?></h1>
                <div class="detalle-precio"><?php echo $casa['precio']; ?></div>
                <ul class="detalle-lista">
                    <li>🛏️ Dormitorios: <?php echo $casa['dormitorios']; ?></li>
                    <li>🚿 Baños: <?php echo $casa['banos']; ?></li>
                    <li>📐 Superficie: <?php echo $casa['superficie']; ?></li>
                </ul>
                <div class="detalle-desc"><?php echo $casa['descripcion']; ?></div>
                <a href="index.php#quote" class="btn">Solicitar Cotización</a>
            </div>
        </div>
        <?php if (!empty($kits_casa)): ?>
            <div class="kits-section">
                <div class="kits-title">Opciones de Kits para este modelo</div>
                <?php if (isset($kits_casa[$kit_seleccionado])): ?>
                    <div style="display:flex;justify-content:center;align-items:flex-start;gap:40px;margin-bottom:35px;flex-wrap:wrap;">
                        <img src="<?php echo $kits_casa[$kit_seleccionado]['imagen']; ?>" alt="Vista grande <?php echo $kits_casa[$kit_seleccionado]['nombre']; ?>" style="max-width:420px;width:100%;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,0.12);">
                        <div style="flex:1;min-width:220px;max-width:400px;">
                            <div class="kit-nombre" style="font-size:1.4rem;margin-bottom:10px;"><strong><?php echo $kits_casa[$kit_seleccionado]['nombre']; ?></strong></div>
                            <div class="kit-desc" style="font-size:1.1rem;margin-bottom:18px;"> <?php echo $kits_casa[$kit_seleccionado]['descripcion']; ?> </div>
                            <div class="kit-precio" style="color:#2c3e50;font-weight:bold;font-size:1.2rem;">$
                                <?php echo number_format((int)str_replace('.', '', $kits_casa[$kit_seleccionado]['precio']), 0, ',', '.'); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <form id="kitForm" method="post" style="margin-bottom:0;">
                    <input type="hidden" name="kit" id="kitFormKit" value="<?php echo $kit_seleccionado; ?>">
                    <div class="kits-grid">
                        <?php foreach ($kits_casa as $i => $kit): ?>
                            <div class="kit-card<?php if ($kit_seleccionado === $i) echo ' selected'; ?>" onclick="selectKit(<?php echo $i; ?>)">
                                <input type="radio" class="kit-radio" name="kit_radio" <?php if ($kit_seleccionado === $i) echo 'checked'; ?> readonly>
                                <img src="<?php echo $kit['imagen']; ?>" alt="<?php echo $kit['nombre']; ?>" class="kit-img">
                                <div class="kit-nombre"><?php echo $kit['nombre']; ?></div>
                                <div class="kit-desc"><?php echo $kit['descripcion']; ?></div>
                                <div class="kit-precio" style="margin-top:10px; color:#2c3e50; font-weight:bold; font-size:1.1rem;">$
                                    <?php echo number_format((int)str_replace('.', '', $kit['precio']), 0, ',', '.'); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </form>
                <form id="addToCartForm" method="post" action="carrito.php" style="margin-bottom:30px;">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="id" value="<?php echo $casa_id; ?>">
                    <input type="hidden" name="kit" value="<?php echo $kit_seleccionado; ?>">
                    <input type="hidden" name="cantidad" value="1">
                    <button type="submit" class="btn btn-primary" style="width:100%;max-width:320px;margin:0 auto 20px auto;display:block;font-size:1.2rem;">Cotizar este kit y agregar al carrito</button>
                </form>
            </div>
        <?php endif; ?>
        <div style="text-align:center; margin-top:40px;">
            <a href="index.php#catalog" class="btn">Ver más modelos</a>
        </div>
    </div>
</body>
</html>
