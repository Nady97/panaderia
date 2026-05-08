import re

def fix_file(path):
    with open(path, 'r', encoding='utf-8') as f:
        text = f.read()

    # Look for label lines like: <label class="form-label fw-bold mb-2"><i class="bi bi-hash text-muted me-1"></i> Código Identificador <span class="text-danger">*</span></label>
    # Followed by input/select with input-group-modern class
    
    # We'll split the problem: first find the label, isolate the icon, then wrap the input.
    
    def repl(m):
        full_match = m.group(0)
        
        # Parse label
        label_match = re.search(r'<label (.*?)><i class="(.*?)".*?></i> (.*?)</label>', m.group(1))
        if not label_match:
            return full_match
            
        l_attrs = label_match.group(1)
        icon_class = label_match.group(2)
        label_txt = label_match.group(3)
        
        # Parse input
        tag = m.group(2)
        inner = m.group(3)
        input_class = m.group(4)
        post_class = m.group(5)
        
        icon_class = icon_class.replace(' me-1', '')
        input_class = input_class.replace('input-group-modern', '').strip()
        
        label_new = f'<label {l_attrs} style="color: var(--text-primary);">{label_txt}</label>'
        input_new = f'<{tag} {inner} class="{input_class}" {post_class}>'
        
        return f'{label_new}\n                        <div class="input-group input-group-modern">\n                            <span class="input-group-text"><i class="{icon_class}"></i></span>\n                            {input_new}\n                        </div>'

    pattern = r'(<label.*?</label>)\s*<(input|select) (.*?)class="(.*?)"(.*?)>'
    
    new_text = re.sub(pattern, repl, text)

    # specifically for the disabled one in edit:
    edit_disabled_old = '<label class="form-label fw-bold mb-2"><i class="bi bi-hash text-muted me-1"></i> Código Identificador</label>\n                        <input type="text" class="form-control fw-bold" value="{{ $proveedor->codigo }}" disabled>'
    edit_disabled_new = '<label class="form-label fw-bold mb-2" style="color: var(--text-primary);">Código Identificador</label>\n                        <div class="input-group input-group-modern">\n                            <span class="input-group-text"><i class="bi bi-hash text-muted"></i></span>\n                            <input type="text" class="form-control fw-bold" value="{{ $proveedor->codigo }}" disabled>\n                        </div>'
    new_text = new_text.replace(edit_disabled_old, edit_disabled_new)

    with open(path, 'w', encoding='utf-8') as f:
        f.write(new_text)

fix_file('resources/views/proveedores/create.blade.php')
fix_file('resources/views/proveedores/edit.blade.php')
