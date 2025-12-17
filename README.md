# Sistema de Aguinaldo FEC 2025

Sistema PHP simple para generar certificados de aguinaldo en PDF a partir de datos en Excel.

## Instalación

### 1. Instalar dependencias con Composer

Abrir una terminal en la carpeta del proyecto y ejecutar:

```bash
composer install
```

Esto instalará:
- **PhpSpreadsheet**: Para leer archivos Excel
- **FPDI**: Para trabajar con plantillas PDF

### 2. Descargar FPDF

Como FPDF no está en Composer, descargarlo manualmente:

1. Ir a: http://www.fpdf.org/
2. Descargar la última versión
3. Extraer el contenido en una carpeta llamada `fpdf` en la raíz del proyecto
4. Debe quedar: `c:\xampp\htdocs\AGUINALDO_FEC_2025\fpdf\fpdf.php`

### 3. Preparar el archivo Excel

Crear un archivo llamado **exactamente**:
```
base_datos_aguinaldo_2025.xlsx
```

En la raíz del proyecto con:

- **Hoja llamada**: `base de datos`
- **Columnas**:
  - A: identificación
  - B: nombre
  - C: valor aguinaldo 2025
  - D: valor retención en la fuente
  - E: valor abonado a los depósitos

Ejemplo:

| identificación | nombre | valor aguinaldo 2025 | valor retención en la fuente | valor abonado a los depósitos |
|----------------|--------|----------------------|------------------------------|-------------------------------|
| 123456789 | Juan Pérez | 1500000 | 150000 | 1350000 |
| 987654321 | María López | 2000000 | 0 | 2000000 |

### 4. Preparar la plantilla PDF

Crear un archivo PDF llamado:
```
plantilla_aguinaldo.pdf
```

Este PDF debe tener el diseño fijo del certificado de aguinaldo.

El sistema escribirá texto en las siguientes coordenadas (ajustar según diseño):
- Posición X=50, Y=60: Nombre completo
- Posición X=50, Y=80: Valor aguinaldo 2025
- Posición X=50, Y=100: Valor retención en la fuente
- Posición X=50, Y=120: Valor abonado a los depósitos

**IMPORTANTE**: Debes ajustar las coordenadas X,Y en el archivo `index.php` (acción `generar_pdf`) según el diseño de tu plantilla.

## Uso

1. Iniciar sesión con cédula y contraseña FEC
2. Ingresar código de validación recibido por SMS
3. En la página principal, ingresar la cédula a buscar
4. Si existe en el Excel, aparecerá el registro
5. Hacer clic en "Generar PDF" para descargar el certificado

## Ajustar coordenadas del PDF

Editar el archivo `index.php`, buscar la sección `// ========== ACCIÓN: GENERAR PDF ==========` y modificar las líneas:

```php
// Campo 1: Estimado(a) - Nombre completo
$pdf->SetXY(50, 60);  // Ajustar X e Y según tu plantilla
$pdf->Write(0, $datos['nombre']);

// Campo 2: Valor aguinaldo 2025
$pdf->SetXY(50, 80);  // Ajustar X e Y
$pdf->Write(0, '$' . number_format($datos['valor_aguinaldo'], 0, ',', '.'));

// Campo 3: Valor retención en la fuente
$pdf->SetXY(50, 100);  // Ajustar X e Y
$pdf->Write(0, '$' . number_format($datos['valor_retencion'], 0, ',', '.'));

// Campo 4: Valor abonado a los depósitos
$pdf->SetXY(50, 120);  // Ajustar X e Y
$pdf->Write(0, '$' . number_format($datos['valor_abonado'], 0, ',', '.'));
```

## Estructura del proyecto

```
AGUINALDO_FEC_2025/
├── index.php                           # Archivo principal
├── funciones.php                       # Funciones auxiliares
├── entregas.php                        # Plantilla de interfaz
├── composer.json                       # Dependencias
├── base_datos_aguinaldo_2025.xlsx     # Base de datos Excel
├── plantilla_aguinaldo.pdf            # Plantilla PDF
├── sesiones.json                       # Sesiones de usuarios
├── fpdf/                              # Librería FPDF (descargar manual)
│   └── fpdf.php
├── vendor/                            # Dependencias de Composer
├── css/                               # Estilos
├── js/                                # Scripts
└── images/                            # Imágenes
```

## Características

- ✅ Login con API de FEC
- ✅ Validación por SMS (OTP)
- ✅ Búsqueda en Excel por identificación
- ✅ Generación de PDF con plantilla
- ✅ 4 campos dinámicos en el PDF
- ✅ Sin base de datos
- ✅ Sin cálculos (valores desde Excel)
- ✅ Sistema simple y directo

## Soporte

Para dudas o problemas contactar a:
- PBX: (601) 232 84 55
- Email: atencionalasociado@fecolsubsidio.com
