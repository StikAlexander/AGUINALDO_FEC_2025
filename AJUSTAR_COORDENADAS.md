# Guía para ajustar coordenadas del PDF

## Ubicación del código

El código para generar el PDF está en: **index.php**

Buscar la sección: `// ========== ACCIÓN: GENERAR PDF ==========`

## Coordenadas actuales (EJEMPLO - Debes ajustarlas)

```php
// Campo 1: Estimado(a) - Nombre completo
$pdf->SetXY(50, 60);
$pdf->Write(0, $datos['nombre']);

// Campo 2: Valor aguinaldo 2025
$pdf->SetXY(50, 80);
$pdf->Write(0, '$' . number_format($datos['valor_aguinaldo'], 0, ',', '.'));

// Campo 3: Valor retención en la fuente
$pdf->SetXY(50, 100);
$pdf->Write(0, '$' . number_format($datos['valor_retencion'], 0, ',', '.'));

// Campo 4: Valor abonado a los depósitos
$pdf->SetXY(50, 120);
$pdf->Write(0, '$' . number_format($datos['valor_abonado'], 0, ',', '.'));
```

## Cómo ajustar las coordenadas

### Sistema de coordenadas:
- **X**: Posición horizontal (de izquierda a derecha)
- **Y**: Posición vertical (de arriba hacia abajo)
- Origen (0,0) está en la esquina superior izquierda
- La unidad de medida por defecto es milímetros (mm)

### Método de prueba y error:

1. **Coloca tu plantilla PDF** en la raíz del proyecto con el nombre: `plantilla_aguinaldo.pdf`

2. **Genera un PDF de prueba** usando el sistema

3. **Revisa dónde quedó el texto** en el PDF generado

4. **Ajusta las coordenadas**:
   - Si el texto está muy a la izquierda → Aumenta X
   - Si el texto está muy a la derecha → Disminuye X
   - Si el texto está muy arriba → Aumenta Y
   - Si el texto está muy abajo → Disminuye Y

5. **Repite** hasta que quede en la posición correcta

### Ejemplo de ajuste:

Si necesitas que el nombre aparezca más a la derecha y más abajo:

```php
// Antes:
$pdf->SetXY(50, 60);

// Después:
$pdf->SetXY(80, 90);  // 30mm más a la derecha, 30mm más abajo
```

## Ajustar tamaño de fuente

Si necesitas cambiar el tamaño de la fuente:

```php
// Fuente más grande
$pdf->SetFont('Arial', '', 14);  // Cambia el 14 por el tamaño deseado

// Fuente en negrita
$pdf->SetFont('Arial', 'B', 12);

// Fuente cursiva
$pdf->SetFont('Arial', 'I', 12);
```

## Cambiar color del texto

```php
// Negro (default)
$pdf->SetTextColor(0, 0, 0);

// Rojo
$pdf->SetTextColor(255, 0, 0);

// Azul
$pdf->SetTextColor(0, 0, 255);

// Verde
$pdf->SetTextColor(0, 128, 0);
```

## Consejo útil

Para encontrar las coordenadas exactas de tu plantilla:

1. Abre tu plantilla PDF en un editor de PDF
2. Usa la herramienta de texto/anotación
3. Coloca un texto de prueba donde quieras que aparezca
4. Muchos editores muestran las coordenadas al posicionar elementos
5. Usa esas coordenadas en el código

## Archivo necesario

Asegúrate de tener el archivo:
```
plantilla_aguinaldo.pdf
```
En la raíz del proyecto: `c:\xampp\htdocs\AGUINALDO_FEC_2025\`
