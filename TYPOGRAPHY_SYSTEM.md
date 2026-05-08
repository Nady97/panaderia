# Sistema Tipográfico Minimalista & Elegante

## Jerarquía de Tipografía

### Primaria (Headings)
- **H1**: 32px (2rem), **Bold** (700), Letter-spacing: -0.02em
- **H2**: 24px (1.5rem), **Bold** (700), Letter-spacing: -0.01em  
- **H3**: 20px (1.25rem), **SemiBold** (600)

### Secundaria (Subheadings & Labels)
- **H4**: 16px (1rem), **SemiBold** (600)
- **H5**: 15.2px (0.95rem), **SemiBold** (600)
- **H6**: 14.4px (0.9rem), **SemiBold** (600)

### Cuerpo (Body Text)
- **Párrafos**: 14px (body), **Regular** (400), Line-height: 1.6
- **Labels**: 14.4px (0.9rem), **Medium** (500)
- **Captions**: 12.5px (0.78rem), **Medium** (500), Letter-spacing: 0.02em

### Monoespaciado
- `font-family`: 'JetBrains Mono', 'Fira Code', Consolas, monospace
- `font-size`: 13.6px (0.85rem)
- `font-weight`: 600
- `letter-spacing`: 0.02em

---

## Clases Utilitarias de Tipografía

```css
.text-primary-heading   /* h3 nivel: 20px, 700, -0.02em */
.text-secondary-heading /* 18px, 600, -0.01em */
.text-tertiary-heading  /* 16px, 600, 0em */
.text-label            /* 14.4px, 500, 0.01em */
.text-caption          /* 12.5px, 500, 0.02em */
```

---

## Espaciado Estándar (Cards & Grids)

### Gap Entre Cards
- **Desktop (KPI Grid)**: 1.25rem (20px) - 4 columnas
- **Tablet**: 2 columnas, gap 1.25rem
- **Mobile**: 1 columna, gap 1rem

### Padding Interno (Cards)
- **Card-Modern**: 1.25rem (20px)
- **Card-KPI**: 1.25rem (20px)
- **Table-Section**: 1.25rem (20px)

### Separadores de Sección
- **Margin-Bottom (grids)**: 1.75rem (28px)
- **Table Borders**: 1px var(--border-color)

---

## Sistema de Sombras (Elegancia Minimalista)

### Baseline (En Reposo)
- **Shadow-SM**: `0 2px 8px rgba(0, 0, 0, 0.04)`

### On Hover
- **Shadow-MD**: `0 4px 12px rgba(0, 0, 0, 0.08)`
- **Elevation**: `translateY(-1px)` suave

### Transiciones
- **Duration**: 0.2s ease (rápido y fluido)
- Aplicada a: cards, buttons, table rows

---

## Bordes & Radios

### Border-Radius
- **Cards**: 10px (minimalista)
- **Buttons**: 8px
- **Input Groups**: 10px (con esquinas redondeadas)
- **Icons**: 8px

### Border-Color
- **Estándar**: var(--border-color) = rgba(186, 105, 34, 0.18)
- **On Hover (Cards)**: rgba(204, 120, 38, 0.15)
- **On Focus (Inputs)**: var(--gold-dark) = #cc7826

---

## Colores Tipográficos

### Texto Primario
- `--text-primary`: #2C1B10 (claro), #FEF6EC (oscuro)

### Texto Secundario
- `--text-secondary`: #5A3D2B (claro), #D4C5B8 (oscuro)

### Texto Muted (Captions)
- `--text-muted`: #8B6F63 (claro), #A59A92 (oscuro)

---

## Componentes Clave

### KPI Cards
```html
<!-- Ejemplo estructura -->
<div class="card-kpi">
  <div class="card-kpi-body">
    <div class="card-kpi-icon" style="background: rgba(100, 54, 23, 0.12)">
      <!-- Icon -->
    </div>
    <div class="card-kpi-content">
      <div class="card-kpi-title">1,234</div>
      <div class="card-kpi-label">TOTAL VENDIDO</div>
    </div>
  </div>
</div>
```

### Buttons
- `btn-primary-panaderia`: Brown, shadow 0 3px 8px
- `btn-gold-panaderia`: Gold, shadow 0 3px 8px  
- `btn-light-panaderia`: Light border

All buttons: **10px 20px padding, 0.95rem font-size, 500 weight**

### Forms
```html
<div class="input-group input-group-modern">
  <span class="input-group-text">
    <i class="bi bi-icon"></i>
  </span>
  <input type="text" class="form-control" />
</div>
```
**Border-radius**: 10px (izquierda en .input-group-text, derecha en .form-control)

---

## Responsive Design

```css
/* Desktop (default) */
.kpi-grid { grid-template-columns: repeat(4, 1fr); }

/* Tablet (max-width: 992px) */
.kpi-grid { grid-template-columns: repeat(2, 1fr); }

/* Mobile (max-width: 576px) */
.kpi-grid { grid-template-columns: 1fr; gap: 1rem; }
```

---

## Guía Rápida: Aplicar Consistencia

### Para Títulos
- ✅ Use `h3` (primaria) o `.text-primary-heading`
- ❌ Avoid `h1` for sections (use h3)

### Para Labels
- ✅ Use `.text-label` + `.text-uppercase`
- ✅ Letter-spacing: 0.03em (subtle)

### Para Valores Numéricos
- ✅ `.card-kpi-title` (1.5rem, 700, -0.02em)
- ✅ Alt: `class="text-primary-heading"`

### Para Descripciones
- ✅ Body text (14px, 400, 1.6 line-height)
- ✅ `.text-caption` para notas pequeñas

### Para Espacios Entre Cards
- ✅ Grid gap: 1.25rem (siempre)
- ✅ Margin-bottom section: 1.75rem

---

## Validación: Sistema Impecable ✨

- [x] Tipografía unificada (fuente, pesos, tamaños)
- [x] Espaciados consistentes (gap 1.25rem, padding 1.25rem)
- [x] Sombras elegantes (0.2s transitions, subtle depth)
- [x] Colores coordinados (brown/gold theme)
- [x] Responsive fluid (4→2→1 grid collapse)
- [x] CSS sin errores (6 archivos validados)

---

**Última actualización**: 22 de abril de 2026  
**Modo**: Minimalista & Elegante 🎨
