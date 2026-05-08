import re, os
files = [
    'resources/views/proveedores/create.blade.php',
    'resources/views/proveedores/edit.blade.php'
]
bad_styles = [
    r' style="background: var\(--bg-input\); color: var\(--text-primary\); border: 1px solid var\(--border-color\); border-radius: 8px; padding: 12px;"',
    r' style="background: rgba\(139, 90, 43, 0\.05\); color: var\(--text-muted\); border: 1px solid var\(--border-color\); border-radius: 8px; padding: 12px; cursor: not-allowed;"',
    r' style="border-color: var\(--border-color\);"',
    r' style="color: var\(--text-primary\);"',
    r' style="color: var\(--text-secondary\);"',
    r' class="form-control" ',
    r' class="form-select form-control" '
]

replace_with = [
    '',
    '',
    '',
    '',
    '',
    ' class="form-control input-group-modern" ',
    ' class="form-select input-group-modern" '
]

for f in files:
    with open(f, 'r', encoding='utf-8') as file:
        content = file.read()
        
    for bad, good in zip(bad_styles, replace_with):
        content = re.sub(bad, good, content)
        
    with open(f, 'w', encoding='utf-8') as file:
        file.write(content)
