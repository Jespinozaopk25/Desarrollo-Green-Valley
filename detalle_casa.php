<?php
session_start();

// Obtener ID de la casa desde URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 2; // Por defecto casa 2

// Datos de las casas (normalmente vendrían de base de datos)
$casas = [
    1 => [
        'titulo' => 'Casa Prefabricada 21 m²',
        'imagenes' => [
            'IMG/casa1.jpg',
            'IMG/casa1_1.jpg',
            'IMG/casa1_2.jpg',
            'IMG/casa1_3.jpg'
        ],
        'precio_desde' => '1.940.000',
        'dormitorios' => 1,
        'banos' => 1,
        'superficie' => 21,
        'descripcion' => 'Modelo compacto ideal para parejas jóvenes o como casa de campo. Perfecta combinación de funcionalidad y confort en un espacio optimizado.'
    ],
    2 => [
        'titulo' => 'Casa Prefabricada 36 m²',
        'imagenes' => [
            'IMG/casa2.jpg',
            'IMG/casa2_1.jpg',
            'IMG/casa2_2.jpg',
            'IMG/casa2_3.jpg'
        ],
        'precio_desde' => '3.390.000',
        'dormitorios' => 2,
        'banos' => 1,
        'superficie' => 36,
        'descripcion' => 'Modelo familiar pequeño, perfecto para familias jóvenes o como segunda vivienda. Esta casa prefabricada combina funcionalidad y comodidad en un diseño compacto pero bien distribuido.'
    ],
    3 => [
        'titulo' => 'Casa Prefabricada 48 m²',
        'imagenes' => [
            'IMG/casa3.jpg',
            'IMG/casa3_1.jpg',
            'IMG/casa3_2.jpg',
            'IMG/casa3_3.jpg'
        ],
        'precio_desde' => '4.338.000',
        'dormitorios' => 2,
        'banos' => 1,
        'superficie' => 42,
        'cobertizo' => 6,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    //AGREGADO (azul)
    4 => [
        'titulo' => 'Casa Prefabricada 54 m²',
        'imagenes' => [
            'IMG/casa1.jpg',
            'IMG/casa4_1.jpg',
            'IMG/casa4_2.jpg',
            'IMG/casa4_3.jpg'
        ],
        'precio_desde' => '4.698.000',
        'dormitorios' => 2,
        'banos' => 1,
        'superficie' => 54,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    5 => [
        'titulo' => 'Casa Prefabricada 66 m²',
        'imagenes' => [
            'IMG/casa5.jpg',
            'IMG/casa5_1.jpg',
            'IMG/casa5_2.jpg',
            'IMG/casa5_3.jpg'
        ],
        'precio_desde' => '5.400.000',
        'dormitorios' => 2,
        'banos' => 1,
        'superficie' => 60,
        'cobertizo' => 6,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    6 => [
        'titulo' => 'Casa Prefabricada 66 m²',
        'imagenes' => [
            'IMG/casa6.jpg',
            'IMG/casa6_1.jpg',
            'IMG/casa6_2.jpg',
            'IMG/casa6_3.jpg'
        ],
        'precio_desde' => '5.610.000',
        'dormitorios' => 3,
        'banos' => 1,
        'superficie' => 60,
        'cobertizo' => 6,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    7 => [
        'titulo' => 'Casa Prefabricada 66 m²',
        'imagenes' => [
            'IMG/casa7.jpg',
            'IMG/casa7_1.jpg',
            'IMG/casa7_2.jpg',
            'IMG/casa7_3.jpg'
        ],
        'precio_desde' => '6.120.000',
        'dormitorios' => 2,
        'banos' => 2,
        'superficie' => 72,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    8 => [
        'titulo' => 'Casa Prefabricada 80 m²',
        'imagenes' => [
            'IMG/casa8.jpg',
            'IMG/casa8_1.jpg',
            'IMG/casa8_2.jpg',
            'IMG/casa8_3.jpg'
        ],
        'precio_desde' => '6.800.000',
        'dormitorios' => 3-2,
        'banos' => 2,
        'superficie' => 80,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    9 => [
        'titulo' => 'Casa Prefabricada 90 m² Tradicional',
        'imagenes' => [
            'IMG/casa9.jpg',
            'IMG/casa9_1.jpg',
            'IMG/casa9_2.jpg',
            'IMG/casa9_3.jpg'
        ],
        'precio_desde' => '7.650.000',
        'dormitorios' => 3,
        'banos' => 2,
        'superficie' => 90,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    10 => [
        'titulo' => 'Casa Prefabricada 90 m² Mediterránea',
        'imagenes' => [
            'IMG/casa10.jpg',
            'IMG/casa10_1.jpg',
            'IMG/casa10_2.jpg',
            'IMG/casa10_3.jpg'
        ],
        'precio_desde' => '7.650.000',
        'dormitorios' => 3,
        'banos' => 2,
        'superficie' => 90,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    11 => [
        'titulo' => 'Casa Prefabricada 117 m²',
        'imagenes' => [
            'IMG/casa11.jpg',
            'IMG/casa11_1.jpg',
            'IMG/casa11_2.jpg',
            'IMG/casa11_3.jpg'
        ],
        'precio_desde' => '9.880.000',
        'dormitorios' => 4,
        'banos' => 2,
        'superficie' => 100,
        'cobertizo' => 17,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    12 => [
        'titulo' => 'Casa Prefabricada 126 m²',
        'imagenes' => [
            'IMG/casa12.jpg',
            'IMG/casa12_1.jpg',
            'IMG/casa12_2.jpg',
            'IMG/casa12_3.jpg'
        ],
        'precio_desde' => '10.710.000',
        'dormitorios' => 4,
        'banos' => 2,
        'superficie' => 126,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    13 => [
        'titulo' => 'Casa Prefabricada 156 m²',
        'imagenes' => [
            'IMG/casa13.jpg',
            'IMG/casa13_1.jpg',
            'IMG/casa13_2.jpg',
            'IMG/casa13_3.jpg'
        ],
        'precio_desde' => '12.900.000',
        'dormitorios' => 4,
        'banos' => 3,
        'superficie' => 120,
        'cobertizo' => 36,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    14 => [
        'titulo' => 'Casa Prefabricada 130 m² (2 Pisos)',
        'imagenes' => [
            'IMG/casa14.jpg',
            'IMG/casa14_1.jpg',
            'IMG/casa14_2.jpg',
            'IMG/casa14_3.jpg'
        ],
        'precio_desde' => '15.568.000',
        'dormitorios' => 3,
        'banos' => 2,
        'superficie' => 130,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    15 => [
        'titulo' => 'Casa Prefabricada 166 m²',
        'imagenes' => [
            'IMG/casa15.jpg',
            'IMG/casa15_1.jpg',
            'IMG/casa15_2.jpg',
            'IMG/casa15_3.jpg'
        ],
        'precio_desde' => '13.600.000',
        'dormitorios' => 4,
        'banos' => 2,
        'superficie' => 120,
        'cobertizo' => 46,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    16 => [
        'titulo' => 'Casa Prefabricada 190 m²',
        'imagenes' => [
            'IMG/casa16.jpg',
            'IMG/casa16_1.jpg',
            'IMG/casa16_2.jpg',
            'IMG/casa16_3.jpg'
        ],
        'precio_desde' => '15.490.000',
        'dormitorios' => 3,
        'banos' => 3,
        'superficie' => 134,
        'cobertizo' => 56,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    17 => [
        'titulo' => 'Casa Prefabricada 190 m²',
        'imagenes' => [
            'IMG/casa17.jpg',
            'IMG/casa17_1.jpg',
            'IMG/casa17_2.jpg',
            'IMG/casa17_3.jpg'
        ],
        'precio_desde' => '16.490.000',
        'dormitorios' => 3,
        'banos' => 3,
        'superficie' => 135,
        'cobertizo' => 55,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ],
    18 => [
        'titulo' => 'Casa Prefabricada 281 m²',
        'imagenes' => [
            'IMG/casa18.jpg',
            'IMG/casa18_1.jpg',
            'IMG/casa18_2.jpg',
            'IMG/casa18_3.jpg'
        ],
        'precio_desde' => '30.297.000',
        'dormitorios' => 6,
        'banos' => 4.5,
        'superficie' => 254,
        'cobertizo' => 27,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ]
];

$kits = [
    1 => [
        0 => [
            'nombre' => 'Kit Básico',
            'precio' => '1940000',
            'descripcion' => 'Incluye estructura, paneles y techumbre. Ideal para autoconstrucción.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Completo',
            'precio' => '3380000',
            'descripcion' => 'Incluye estructura más ventanas, puertas y terminaciones básicas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Premium',
            'precio' => '4600000',
            'descripcion' => 'Incluye todo lo anterior más radier, electricidad y flete.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    2 => [
        0 => [
            'nombre' => 'Kit Estructural',
            'precio' => '3390000',
            'descripcion' => 'Incluye estructura, paneles y techumbre. Ideal para autoconstrucción.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Inicial',
            'precio' => '5272000',
            'descripcion' => 'Incluye estructura más ventanas, puertas y terminaciones básicas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Completo',
            'precio' => '7920000',
            'descripcion' => 'Incluye todo lo anterior más radier, electricidad y flete.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    3 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '4338000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '6512000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '10300000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    // AGREGADO (azul)
    4 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '4698000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '7830000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '11880000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    5 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '5400000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '9200000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '13930000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    6 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '5610000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '9570000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '14210000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    7 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '6120000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '10440000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '14760000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    8 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '6800000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '11600000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '16400000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    9 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '7650000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '13050000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '18450000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    10 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '7650000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '13050000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '18450000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    11 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '9880000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '16100000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '22720000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    12 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '10710000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '19555000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '25200000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    13 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '12900000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '19555000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '29760000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    14 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '15568000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '24325000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '31275000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    15 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '13600000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '20990000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '31360000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    16 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '15490000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '25290000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '35760000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    17 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '16490000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '26456000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '36710000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    18 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '30297000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '47285000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '62010000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ]
];

// Verificar si la casa existe
if (!isset($casas[$id])) {
    header("Location: index.php");
    exit;
}

$casa = $casas[$id];
$kits_casa = $kits[$id];

// Obtener cantidad total del carrito para el badge
function obtenerCantidadTotal($carrito) {
    $total = 0;
    if (!empty($carrito)) {
        foreach ($carrito as $item) {
            $total += $item['cantidad'];
        }
    }
    return $total;
}

$cantidadCarrito = isset($_SESSION['carrito']) ? obtenerCantidadTotal($_SESSION['carrito']) : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($casa['titulo']); ?> - Green Valley</title>
    <style>
        :root {
            --primary-color: #7cb342;
            --primary-dark: #689f38;
            --secondary-color: #2c3e50;
            --accent-color: #25d366;
            --text-light: #7f8c8d;
            --background-light: #f8f9fa;
            --white: #ffffff;
            --shadow-light: rgba(0, 0, 0, 0.08);
            --shadow-medium: rgba(0, 0, 0, 0.12);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--secondary-color);
            background: var(--background-light);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Enhanced Header */
        .top-bar {
            background: var(--secondary-color);
            color: white;
            padding: 8px 0;
            font-size: 13px;
        }

        .top-bar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .contact-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .whatsapp-buttons {
            display: flex;
            gap: 8px;
        }

        .whatsapp-btn {
            background: var(--accent-color);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 11px;
            transition: var(--transition);
        }

        header {
            background: var(--white);
            box-shadow: 0 4px 20px var(--shadow-light);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
        }

        .logo-image {
            height: 50px;
            width: auto;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        nav a {
            text-decoration: none;
            color: var(--secondary-color);
            font-weight: 500;
            transition: var(--transition);
            padding: 8px 16px;
            border-radius: 25px;
        }

        nav a:hover {
            color: var(--primary-color);
            background: rgba(124, 179, 66, 0.1);
        }

        .cart-icon {
            position: relative;
            font-size: 24px;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
            background: rgba(124, 179, 66, 0.2);
            color: var(--primary-color);
            text-decoration: none;
        }

        .cart-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
        }

        /* Enhanced Detail Container */
        .detail-container {
            padding: 60px 0;
        }

        .breadcrumb {
            background: var(--white);
            padding: 15px 30px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            box-shadow: 0 2px 10px var(--shadow-light);
        }

        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .house-detail-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 50px;
            box-shadow: 0 15px 35px var(--shadow-light);
            position: relative;
            overflow: hidden;
        }

        .house-detail-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
        }

        .house-hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: flex-start;
            margin-bottom: 60px;
        }

        .house-main-image {
            width: 100%;
            max-width: 500px;
            border-radius: var(--border-radius);
            box-shadow: 0 20px 40px var(--shadow-medium);
            transition: var(--transition);
        }

        .house-main-image:hover {
            transform: scale(1.02);
            box-shadow: 0 25px 50px var(--shadow-medium);
        }

        .house-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(124, 179, 66, 0.3);
        }

        .house-info {
            display: flex;
            flex-direction: column;
        }

        .house-title {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 15px;
            font-weight: 700;
            line-height: 1.2;
        }

        .house-price {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 30px;
            font-weight: 700;
        }

        .house-specs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .spec-item {
            background: var(--background-light);
            padding: 20px;
            border-radius: var(--border-radius);
            text-align: center;
            border-left: 4px solid var(--primary-color);
            transition: var(--transition);
        }

        .spec-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px var(--shadow-light);
        }

        .spec-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .spec-label {
            font-size: 0.9rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .spec-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .house-description {
            color: var(--text-light);
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 40px;
            padding: 25px;
            background: rgba(124, 179, 66, 0.05);
            border-radius: var(--border-radius);
            border-left: 4px solid var(--primary-color);
        }

        /* Enhanced Kits Section */
        .kits-section {
            margin-top: 60px;
        }

        .kits-title {
            font-size: 2.2rem;
            color: var(--secondary-color);
            margin-bottom: 40px;
            text-align: center;
            font-weight: 700;
            position: relative;
        }

        .kits-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            border-radius: 2px;
        }

        .kits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .kit-card {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: 0 8px 25px var(--shadow-light);
            padding: 30px;
            text-align: center;
            position: relative;
            transition: var(--transition);
            cursor: pointer;
            border: 2px solid transparent;
        }

        .kit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px var(--shadow-medium);
        }

        .kit-card.selected {
            border-color: var(--primary-color);
            box-shadow: 0 15px 35px rgba(124, 179, 66, 0.2);
        }

        .kit-radio {
            position: absolute;
            top: 20px;
            right: 20px;
            transform: scale(1.5);
            accent-color: var(--primary-color);
        }

        .kit-image {
            width: 100%;
            max-width: 200px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px var(--shadow-light);
        }

        .kit-name {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .kit-description {
            color: var(--text-light);
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .kit-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--secondary-color);
            background: var(--background-light);
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
        }

        /* Enhanced Buttons */
        .btn {
            display: inline-block;
            padding: 15px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1rem;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: var(--transition);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: 0 8px 25px rgba(124, 179, 66, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(124, 179, 66, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-secondary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .btn-large {
            padding: 20px 50px;
            font-size: 1.2rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        /* Success Message */
        .success-message {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 20px;
            border-radius: var(--border-radius);
            text-align: center;
            margin: 30px 0;
            font-weight: 600;
            box-shadow: 0 8px 25px rgba(124, 179, 66, 0.3);
            opacity: 0;
            transform: translateY(-20px);
            transition: var(--transition);
        }

        .success-message.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* WhatsApp Float */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(45deg, var(--accent-color), #128c7e);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            text-decoration: none;
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            transition: var(--transition);
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Kit Selection Form */
        .kit-selection-form {
            margin-top: 30px;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: center;
        }

        .quantity-selector label {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .quantity-input {
            width: 80px;
            padding: 10px;
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .house-hero {
                grid-template-columns: 1fr;
                gap: 30px;
                text-align: center;
            }

            .house-title {
                font-size: 2rem;
            }

            .house-specs {
                grid-template-columns: repeat(2, 1fr);
            }

            .kits-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }

            .header-container {
                flex-direction: column;
                gap: 15px;
            }

            nav ul {
                gap: 15px;
            }
        }

        /* AGREGADO (azul) */
       .house-image-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .house-gallery {
            display: flex;
            width: 100%;
            transition: transform 0.6s ease-in-out;
        }

        .gallery-item {
            flex: 0 0 100%;
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            object-fit: cover;
            display: block;
        }

        /* Botones */
        .gallery-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.7);
            border: none;
            color: #333;
            font-size: 2rem;
            padding: 8px 14px;
            cursor: pointer;
            border-radius: 50%;
            z-index: 10;
            transition: background 0.3s ease;
        }

        .gallery-btn:hover {
            background: rgba(255,255,255,0.95);
        }

        .gallery-btn.prev {
            left: 15px;
        }

        .gallery-btn.next {
            right: 15px;
        }

        /* Indicadores */
        .gallery-indicators {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .indicator {
            width: 10px;
            height: 10px;
            background: rgba(255,255,255,0.7);
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .indicator:hover {
            transform: scale(1.2);
        }

        .indicator.active {
            background: #4CAF50; /* color destacado */
        }


        /* HASTA ACA */

    </style>
</head>



<script>
    // Agregado (azul)
let currentIndex = 0;

function updateGallery() {
    const gallery = document.getElementById("houseGallery");
    const indicators = document.querySelectorAll(".indicator");

    gallery.style.transform = `translateX(-${currentIndex * 100}%)`;

    indicators.forEach((dot, i) => {
        dot.classList.toggle("active", i === currentIndex);
    });
}

function moveGallery(direction) {
    const items = document.querySelectorAll(".gallery-item");
    const totalItems = items.length;

    currentIndex += direction;
    if (currentIndex < 0) currentIndex = totalItems - 1;
    if (currentIndex >= totalItems) currentIndex = 0;

    updateGallery();
}

function goToSlide(index) {
    currentIndex = index;
    updateGallery();
}

// Navegación con flechas del teclado
document.addEventListener("keydown", function(e) {
    if (e.key === "ArrowLeft") {
        moveGallery(-1);
    } else if (e.key === "ArrowRight") {
        moveGallery(1);
    }
});
// hasta aca
</script>



<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="contact-info">
                    <div>📍 Av. Padre Jorge Alessandri KM 22, San Bernardo, RM.</div>
                    <div>📧 contacto@casasgreenvalley.cl</div>
                    <div>📞 Tel.: +56 2 2583 2001</div>
                </div>
                <div class="whatsapp-buttons">
                    <a href="https://wa.me/56956397365" class="whatsapp-btn" target="_blank">💬 +569 5309 7365</a>
                    <a href="https://wa.me/56987037917" class="whatsapp-btn" target="_blank">💬 +569 8703 7917</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header>
        <div class="container header-container">
            <a href="index.php" class="logo">
                <img src="IMG/logoGreenValley.jpg" alt="Green Valley" class="logo-image">
            </a>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="sobrenosotros.php">Nuestra Empresa</a></li>
                    <li><a href="index.php#catalog">Casas prefabricadas</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </nav>
            <a href="carrito.php" class="cart-icon">
                🛒<span class="cart-badge"><?php echo $cantidadCarrito; ?></span>
            </a>
        </div>
    </header>

    <!-- Detail Container -->
    <section class="detail-container">
        <div class="container">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="index.php">Inicio</a> > 
                <a href="index.php#catalog">Casas Prefabricadas</a> > 
                <span><?php echo htmlspecialchars($casa['titulo']); ?></span>
            </nav>

            <!-- House Detail Card -->
            <div class="house-detail-card fade-in-up">
                <!-- House Hero Section -->
                <div class="house-hero">
                    <!-- agregado (azul) -->
                    <div class="house-image-container">
                        <div class="house-gallery" id="houseGallery">
                            <?php foreach ($casa['imagenes'] as $index => $img): ?>
                                <div class="gallery-item">
                                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($casa['titulo']); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Botones -->
                        <button class="gallery-btn prev" onclick="moveGallery(-1)">&#10094;</button>
                        <button class="gallery-btn next" onclick="moveGallery(1)">&#10095;</button>

                        <!-- Indicadores -->
                        <div class="gallery-indicators" id="galleryIndicators">
                            <?php foreach ($casa['imagenes'] as $index => $img): ?>
                                <span class="indicator <?php echo $index === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $index; ?>)"></span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- HASTA ACA -->

                    <div class="house-info">
                        <h1 class="house-title"><?php echo htmlspecialchars($casa['titulo']); ?></h1>
                        <div class="house-price">Desde $<?php echo number_format((float) str_replace('.', '', $casa['precio_desde']), 0, ',', '.'); ?></div>
                        
                        <div class="house-specs">
                            <div class="spec-item">
                                <div class="spec-icon">🛏️</div>
                                <div class="spec-label">Dormitorios</div>
                                <div class="spec-value"><?php echo $casa['dormitorios']; ?></div>
                            </div>
                            <div class="spec-item">
                                <div class="spec-icon">🚿</div>
                                <div class="spec-label">Baños</div>
                                <div class="spec-value"><?php echo $casa['banos']; ?></div>
                            </div>
                            <div class="spec-item">
                                <div class="spec-icon">📐</div>
                                <div class="spec-label">Superficie</div>
                                <div class="spec-value"><?php echo $casa['superficie']; ?> m²</div>
                            </div>
                            <!-- Cobertizo (Algunas casas) AGREGADO azul -->
                            <?php if (isset($casa['cobertizo'])): ?>
                                <div class="spec-item">
                                    <div class="spec-icon">🏠</div>
                                    <div class="spec-label">Cobertizo</div>
                                    <div class="spec-value"><?php echo $casa['cobertizo']; ?> m²</div>
                                </div>
                            <?php endif; ?>
                            <!-- hasta aca -->
                        </div>

                        <div class="house-description">
                            <?php echo htmlspecialchars($casa['descripcion']); ?>
                        </div>

                        <a href="#kits" class="btn btn-primary">Ver Opciones de Kits</a>
                    </div>
                </div>

                <!-- Kits Section -->
                <div id="kits" class="kits-section">
                    <h2 class="kits-title">Opciones de Kits para este modelo</h2>

                    <!-- Formulario de selección de kit -->
                    <form id="kit-form" class="kit-selection-form" method="POST" action="carrito.php">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="kit" id="selected-kit" value="2">
                        
                        <!-- Kits Grid -->
                        <div class="kits-grid">
                            <?php foreach ($kits_casa as $kit_idx => $kit): ?>
                                <div class="kit-card <?php echo $kit_idx === 2 ? 'selected' : ''; ?>" onclick="selectKit(<?php echo $kit_idx; ?>)">
                                    <input type="radio" name="kit_radio" class="kit-radio" value="<?php echo $kit_idx; ?>" <?php echo $kit_idx === 2 ? 'checked' : ''; ?>>
                                    <img src="<?php echo htmlspecialchars($kit['imagen']); ?>" alt="<?php echo htmlspecialchars($kit['nombre']); ?>" class="kit-image">
                                    <div class="kit-name"><?php echo htmlspecialchars($kit['nombre']); ?></div>
                                    <div class="kit-description"><?php echo htmlspecialchars($kit['descripcion']); ?></div>
                                    <div class="kit-price">$<?php echo number_format(intval($kit['precio']), 0, ',', '.'); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="quantity-selector">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" id="cantidad" name="cantidad" value="1" min="1" max="10" class="quantity-input">
                        </div>

                        <!-- Success Message -->
                        <div id="success-message" class="success-message">
                            ✅ Kit agregado al carrito exitosamente
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary btn-large" id="add-to-cart-btn">
                                Agregar al Carrito
                            </button>
                            <a href="index.php#catalog" class="btn btn-secondary">Ver Más Modelos</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/56995862538" class="whatsapp-float" target="_blank">💬</a>

    <script>
        let selectedKit = 2; // Kit Completo selected by default
        
        const kits = <?php echo json_encode($kits_casa); ?>;

        function selectKit(kitIndex) {
            selectedKit = kitIndex;
            document.getElementById('selected-kit').value = kitIndex;
            document.querySelectorAll('.kit-card').forEach((card, idx) => {
                if (idx === kitIndex) {
                    card.classList.add('selected');
                    card.querySelector('.kit-radio').checked = true;
                } else {
                    card.classList.remove('selected');
                    card.querySelector('.kit-radio').checked = false;
                }
            });
        }

        function updateSuccessMessage() {
            const successMessage = document.getElementById('success-message');
            successMessage.style.display = 'block';
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        }
        document.getElementById('kit-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this);
            formData.append('kit', selectedKit);

            fetch('carrito.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateSuccessMessage();
                    // Optionally update cart badge here
                } else {
                    alert('Error al agregar al carrito. Intenta nuevamente.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Se agrega correctamente.');
            });
        });
    </script>
</body>     
</html>
<?php
// contacto.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $mensaje = htmlspecialchars($_POST['mensaje']);

    // Configura el destinatario
    $destinatario = "info@tuempresa.com";

    // Configura el asunto
    $asunto = "Nuevo mensaje de contacto de $nombre";

    // Configura el encabezado del correo
    $headers = "From: $nombre <$email>" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // Envía el correo
    if (mail($destinatario, $asunto, $mensaje, $headers)) {
        echo "Mensaje enviado exitosamente.";
    } else {
        echo "Error al enviar el mensaje.";
    }
}

