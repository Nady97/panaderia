/**
 * PERFIL DE USUARIO 
 * Funcionalidades: Validación de contraseña, foto de perfil, toasts, modales, etc.
 */

document.addEventListener('DOMContentLoaded', function () {

    // ============================================
    // ⚠️ VERIFICAR SI ESTAMOS EN LA PÁGINA DE PERFIL
    // ============================================
    const isProfilePage = document.querySelector('#perfilForm, #collapsePassword, #collapseDanger, form[action*="/perfil/"]');

    if (!isProfilePage) {
        // No es la página de perfil, salir sin ejecutar nada
        return;
    }

    // ============================================
    // 1. VALIDACIÓN DE CONTRASEÑA EN TIEMPO REAL
    // ============================================
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordMatch = document.getElementById('passwordMatch');

    function checkPasswordStrength(password) {
        let score = 0;
        let feedback = [];

        if (password.length >= 8) score++;
        else if (password.length >= 6) feedback.push('6+ caracteres');

        if (password.match(/[a-z]/)) score++;
        if (password.match(/[A-Z]/)) score++;
        if (password.match(/[0-9]/)) score++;
        if (password.match(/[$@#&!]/)) score++;

        const strengthText = ['Muy débil', 'Débil', 'Media', 'Fuerte', 'Muy fuerte'];
        const strengthColor = ['#dc2626', '#f59e0b', '#eab308', '#10b981', '#059669'];

        if (password.length === 0) {
            if (passwordStrength) passwordStrength.innerHTML = '';
            return 0;
        }

        if (passwordStrength) {
            passwordStrength.innerHTML = `
                <div class="strength-bar">
                    <div class="strength-fill" style="width: ${(score / 5) * 100}%; background: ${strengthColor[score - 1]}"></div>
                </div>
                <span style="color: ${strengthColor[score - 1]}">${strengthText[score - 1]}</span>
                ${feedback.length ? `<small class="feedback-hint">${feedback.join(', ')}</small>` : ''}
            `;
        }
        return score;
    }

    function checkPasswordMatch() {
        if (!confirmPassword || !passwordMatch) return;

        if (confirmPassword.value.length === 0) {
            passwordMatch.innerHTML = '';
            return true;
        }

        if (newPassword.value === confirmPassword.value) {
            passwordMatch.innerHTML = '<i class="bi bi-check-circle-fill"></i> Las contraseñas coinciden';
            passwordMatch.style.color = '#10b981';
            return true;
        } else {
            passwordMatch.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Las contraseñas no coinciden';
            passwordMatch.style.color = '#dc2626';
            return false;
        }
    }

    if (newPassword) {
        newPassword.addEventListener('input', () => checkPasswordStrength(newPassword.value));
        if (confirmPassword) confirmPassword.addEventListener('input', checkPasswordMatch);
    }

    // ============================================
    // 2. LOADING STATE EN BOTONES
    // ============================================
    function setLoading(btn, isLoading, customText = 'Procesando...') {
        if (!btn) return;

        if (isLoading) {
            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.setAttribute('data-original-html', originalHtml);
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> ${customText}`;
        } else {
            btn.disabled = false;
            const originalHtml = btn.getAttribute('data-original-html');
            if (originalHtml) btn.innerHTML = originalHtml;
            btn.removeAttribute('data-original-html');
        }
    }

    // ============================================
    // 3. MANEJO DE FORMULARIOS
    // ============================================
    const perfilForm = document.getElementById('perfilForm');
    const passwordForm = document.querySelector('#collapsePassword form[action*="/perfil/password"]');

    if (perfilForm) {
        perfilForm.addEventListener('submit', function (e) {
            const btn = this.querySelector('.btn-primary, button[type="submit"]');
            if (btn) setLoading(btn, true, 'Actualizando...');
        });
    }

    if (passwordForm) {
        passwordForm.addEventListener('submit', function (e) {
            const password = document.querySelector('input[name="password"]')?.value || '';
            const confirm = document.querySelector('input[name="password_confirmation"]')?.value || '';

            if (password !== confirm) {
                e.preventDefault();
                showToast('Las contraseñas no coinciden', 'error');
                return;
            }
            if (password.length < 6) {
                e.preventDefault();
                showToast('La contraseña debe tener al menos 6 caracteres', 'error');
                return;
            }

            const btn = this.querySelector('.btn-primary, button[type="submit"]');
            if (btn) setLoading(btn, true, 'Cambiando contraseña...');
        });
    }

    // ============================================
    // 4. TOAST NOTIFICATIONS
    // ============================================
    window.showToast = function (message, type = 'success', duration = 3000) {
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) existingToast.remove();

        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;

        const icons = {
            success: '<i class="bi bi-check-circle-fill"></i>',
            error: '<i class="bi bi-exclamation-triangle-fill"></i>',
            info: '<i class="bi bi-info-circle-fill"></i>',
            warning: '<i class="bi bi-exclamation-circle-fill"></i>'
        };

        toast.innerHTML = `${icons[type] || icons.info} <span>${message}</span>`;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    };

    // ============================================
    // 5. MODAL DE CONFIRMACIÓN
    // ============================================
    let modalCallback = null;

    window.showModal = function (message, callback, title = 'Confirmar acción') {
        const modal = document.getElementById('confirmModal');
        if (!modal) return;

        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');

        if (modalTitle) modalTitle.innerHTML = title;
        if (modalMessage) modalMessage.innerHTML = message;
        modal.style.display = 'flex';
        modalCallback = callback;

        const confirmBtn = document.getElementById('modalConfirmBtn');
        if (confirmBtn) {
            const newBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
            newBtn.addEventListener('click', () => {
                if (modalCallback) modalCallback();
                closeModal();
            });
        }
    };

    window.closeModal = function () {
        const modal = document.getElementById('confirmModal');
        if (modal) modal.style.display = 'none';
        modalCallback = null;
    };

    window.onclick = function (event) {
        const modal = document.getElementById('confirmModal');
        if (event.target === modal) closeModal();
    };

    window.confirmDeleteAccount = function () {
        showModal(
            '<strong>⚠️ ¿Estás seguro?</strong><br><small>Esta acción es irreversible y eliminará todos tus datos.</small>',
            () => {
                window.location.href = '/perfil/delete';
            },
            'Eliminar cuenta'
        );
    };

    // ============================================
    // 6. AUTO-OCULTAR ALERTAS
    // ============================================
    const alerts = document.querySelectorAll('.alert-custom, .alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // ============================================
    // 7. COPIA AL PORTAPAPELES
    // ============================================
    document.querySelectorAll('.info-value, .copyable').forEach(el => {
        const text = el.innerText;
        if (text && !text.includes('No registrado') && !text.includes('No registrada')) {
            el.style.cursor = 'pointer';
            el.title = 'Click para copiar';
            el.classList.add('copy-hint');

            el.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(text);
                    showToast(`"${text.substring(0, 50)}" copiado`, 'success', 1500);
                    el.classList.add('copy-effect');
                    setTimeout(() => el.classList.remove('copy-effect'), 300);
                } catch (err) {
                    showToast('No se pudo copiar', 'error');
                }
            });
        }
    });

    // ============================================
    // 8. ANIMACIÓN DE ENTRADA
    // ============================================
    const animateCards = () => {
        document.querySelectorAll('.info-card, .form-card, .activity-panel').forEach((card, i) => {
            if (!card.hasAttribute('data-animated')) {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease';
                card.setAttribute('data-animated', 'true');

                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, i * 80);
            }
        });
    };
    animateCards();

    // ============================================
    // 9. PREVISUALIZACIÓN DE FOTO DE PERFIL
    // ============================================
    const avatarInput = document.querySelector('input[name="imagen"]');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarCircle = document.querySelector('.avatar-circle');
    const removeAvatarBtn = document.getElementById('removeAvatar');

    if (avatarInput) {
        avatarInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                if (!file.type.match('image.*')) {
                    showToast('Solo se permiten imágenes', 'error');
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    showToast('La imagen no debe superar los 2MB', 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    if (avatarPreview) {
                        avatarPreview.src = event.target.result;
                        avatarPreview.style.display = 'block';
                    }
                    if (avatarCircle) {
                        avatarCircle.style.backgroundImage = `url(${event.target.result})`;
                        avatarCircle.style.backgroundSize = 'cover';
                        avatarCircle.style.backgroundPosition = 'center';
                        const icon = avatarCircle.querySelector('i');
                        if (icon) icon.style.display = 'none';
                    }
                    if (removeAvatarBtn) removeAvatarBtn.style.display = 'inline-flex';
                    showToast('Foto cargada correctamente', 'success');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (removeAvatarBtn) {
        removeAvatarBtn.addEventListener('click', function () {
            if (confirm('¿Eliminar foto de perfil?')) {
                if (avatarPreview) {
                    avatarPreview.src = '';
                    avatarPreview.style.display = 'none';
                }
                if (avatarCircle) {
                    avatarCircle.style.backgroundImage = 'none';
                    const icon = avatarCircle.querySelector('i');
                    if (icon) icon.style.display = 'flex';
                }
                if (avatarInput) avatarInput.value = '';
                this.style.display = 'none';
                showToast('Foto eliminada', 'info');
            }
        });
    }

    // ============================================
    // 10. VALIDACIÓN DE EMAIL
    // ============================================
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput) {
        const validateEmail = () => {
            const email = emailInput.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            let errorDiv = emailInput.parentElement.querySelector('.email-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'email-error';
                errorDiv.style.cssText = 'font-size: 0.7rem; margin-top: 5px; transition: all 0.3s;';
                emailInput.parentElement.appendChild(errorDiv);
            }

            if (email && !emailRegex.test(email)) {
                errorDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Ingresa un email válido';
                errorDiv.style.color = '#dc2626';
                emailInput.style.borderColor = '#dc2626';
                return false;
            } else {
                errorDiv.innerHTML = '';
                emailInput.style.borderColor = '';
                return true;
            }
        };

        emailInput.addEventListener('input', validateEmail);
        emailInput.addEventListener('blur', validateEmail);
    }

    // ============================================
    // 11. MOSTRAR/OCULTAR CONTRASEÑA
    // ============================================
    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input) {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                const icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('bi-eye');
                    icon.classList.toggle('bi-eye-slash');
                }
            }
        });
    });

    // ============================================
    // 12. BOTÓN DE MODO OSCURO
    // ============================================
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');

            const icon = themeToggle.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-moon-fill');
                icon.classList.toggle('bi-sun-fill');
            }

            showToast(`Modo ${isDark ? 'oscuro' : 'claro'} activado`, 'info', 1500);
        });

        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
            const icon = themeToggle.querySelector('i');
            if (icon) {
                icon.classList.remove('bi-moon-fill');
                icon.classList.add('bi-sun-fill');
            }
        }
    }
});

// ============================================
// FUNCIÓN PARA RESETEAR FORMULARIO
// ============================================
function resetForm(form) {
    if (!form) return;

    form.reset();

    const emailError = form.querySelector('.email-error');
    if (emailError) emailError.innerHTML = '';

    const passwordStrength = document.getElementById('passwordStrength');
    if (passwordStrength) passwordStrength.innerHTML = '';

    const passwordMatch = document.getElementById('passwordMatch');
    if (passwordMatch) passwordMatch.innerHTML = '';

    form.querySelectorAll('.form-control, .form-select').forEach(input => {
        input.style.borderColor = '';
        input.style.outline = '';
    });

    const avatarPreview = document.getElementById('avatarPreview');
    const avatarCircle = document.querySelector('.avatar-circle');
    const removeAvatarBtn = document.getElementById('removeAvatar');

    if (avatarPreview) avatarPreview.style.display = 'none';
    if (avatarCircle) {
        avatarCircle.style.backgroundImage = 'none';
        const icon = avatarCircle.querySelector('i');
        if (icon) icon.style.display = 'flex';
    }
    if (removeAvatarBtn) removeAvatarBtn.style.display = 'none';

    if (typeof showToast !== 'undefined') {
        showToast('Formulario restablecido', 'info', 1500);
    }
}

// ============================================
// ESTILOS ADICIONALES
// ============================================

document.head.appendChild(style);



function confirmarEliminacion() {
    if (confirm('⚠️ ¿Estás seguro de eliminar tu cuenta?\n\nEsta acción es irreversible y todos tus datos serán eliminados permanentemente.')) {
        // Aquí iría la acción de eliminar cuenta
        alert('Funcionalidad de eliminación de cuenta');
    }
}
