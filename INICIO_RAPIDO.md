# ✅ SISTEMA LISTO PARA USAR

## 📋 Lo que ya está instalado:

✅ PhpSpreadsheet (leer Excel)
✅ FPDI (trabajar con PDFs)
✅ FPDF (generar PDFs)
✅ Código modificado y funcional
✅ Plantilla de interfaz actualizada

## 🔧 Lo que DEBES hacer para que funcione:

### 1. Crear el archivo Excel

Crear archivo: **base_datos_aguinaldo_2025.xlsx**

Ubicación: `c:\xampp\htdocs\AGUINALDO_FEC_2025\base_datos_aguinaldo_2025.xlsx`

**Estructura:**
- Nombre de hoja: `base de datos`
- Columnas en orden:
  - A: identificación
  - B: nombre
  - C: valor aguinaldo 2025
  - D: valor retención en la fuente
  - E: valor abonado a los depósitos

**Ejemplo de datos:**

| identificación | nombre | valor aguinaldo 2025 | valor retención en la fuente | valor abonado a los depósitos |
|----------------|--------|----------------------|------------------------------|-------------------------------|
| 123456789 | Juan Pérez García | 1500000 | 150000 | 1350000 |
| 987654321 | María López | 2000000 | 0 | 2000000 |

Ver archivo: `INSTRUCCIONES_EXCEL.md` para más detalles.

### 2. Colocar tu plantilla PDF

Guardar tu plantilla PDF con el nombre: **plantilla_aguinaldo.pdf**

Ubicación: `c:\xampp\htdocs\AGUINALDO_FEC_2025\plantilla_aguinaldo.pdf`

Este PDF debe tener el diseño completo del certificado de aguinaldo.

### 3. Ajustar coordenadas (después de probar)

Editar archivo: **index.php**

Buscar: `// ========== ACCIÓN: GENERAR PDF ==========`

Modificar las coordenadas X, Y según donde quieras que aparezca cada campo:

```php
// Coordenadas de ejemplo (AJUSTAR según tu PDF)
$pdf->SetXY(50, 60);   // Campo 1: Nombre
$pdf->SetXY(50, 80);   // Campo 2: Valor aguinaldo
$pdf->SetXY(50, 100);  // Campo 3: Retención
$pdf->SetXY(50, 120);  // Campo 4: Valor abonado
```

Ver archivo: `AJUSTAR_COORDENADAS.md` para guía completa.

## 🚀 Cómo probar el sistema:

1. Abrir en el navegador:
   ```
   http://localhost/AGUINALDO_FEC_2025/
   ```

2. Iniciar sesión con cédula y contraseña FEC

3. Validar código OTP recibido por SMS

4. En la página principal:
   - Ingresar una cédula que exista en el Excel
   - Hacer clic en "Buscar"
   - Si encuentra el registro, aparecerá en la tabla
   - Hacer clic en "Generar PDF"
   - Se descargará el PDF con los datos

## 📂 Estructura de archivos:

```
AGUINALDO_FEC_2025/
├── index.php                              ✅ Principal (modificado)
├── entregas.php                           ✅ Plantilla (modificado)
├── funciones.php                          ✅ Funciones auxiliares
├── composer.json                          ✅ Dependencias
├── base_datos_aguinaldo_2025.xlsx        ⚠️ CREAR ESTE
├── plantilla_aguinaldo.pdf               ⚠️ COLOCAR ESTE
├── vendor/                               ✅ Librerías instaladas
├── fpdf/                                 ✅ Librería FPDF
│   └── fpdf.php
├── css/                                  ✅ Estilos
├── js/                                   ✅ Scripts
└── images/                               ✅ Imágenes
```

## ⚙️ Campos que se llenan en el PDF:

1. **Nombre completo** (columna B del Excel)
2. **Valor aguinaldo 2025** (columna C - formateado con $ y puntos)
3. **Valor retención en la fuente** (columna D - formateado)
4. **Valor abonado a los depósitos** (columna E - formateado)

## 🎯 Características del sistema:

✅ Login con API FEC
✅ Validación OTP por SMS
✅ Búsqueda en Excel por cédula
✅ Generación de PDF con plantilla
✅ 4 campos dinámicos
✅ Formato de moneda automático ($1.500.000)
✅ Sin base de datos
✅ Sin cálculos (todo desde Excel)
✅ Interfaz simple

## 📞 Problemas comunes:

### No encuentra el Excel
- Verificar que el archivo se llame exactamente: `base_datos_aguinaldo_2025.xlsx`
- Verificar que la hoja se llame: `base de datos`
- Verificar que esté en la raíz del proyecto

### Error al generar PDF
- Verificar que exista: `plantilla_aguinaldo.pdf`
- Verificar que el archivo no esté dañado

### El texto no aparece en el PDF
- Ajustar coordenadas X, Y en index.php
- Ver guía: `AJUSTAR_COORDENADAS.md`

## 📚 Archivos de ayuda:

- `README.md` - Documentación completa
- `INSTRUCCIONES_EXCEL.md` - Cómo crear el Excel
- `AJUSTAR_COORDENADAS.md` - Cómo ajustar posiciones en el PDF

## ✨ ¡Todo listo!

Una vez crees el Excel y coloques tu plantilla PDF, el sistema estará 100% funcional.
