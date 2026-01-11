<!-- ═══════════════════════════════════════════════════════════════ -->
<!-- MODAL: AGREGAR CONTACTO - FULL WIDTH CON VALIDACIONES          -->
<!-- ═══════════════════════════════════════════════════════════════ -->
<div class="modal-contact" id="modal-add">
    <div class="modal-contact-overlay" onclick="closeModal('modal-add')"></div>
    <div class="modal-contact-wrapper">
        <div class="modal-contact-box">
            <div class="modal-contact-header">
                <h3 class="modal-contact-title">
                    <i class="fas fa-user-plus"></i>
                    Nuevo Contacto
                </h3>
                <button type="button" class="modal-contact-close" onclick="closeModal('modal-add')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="formAddContact" onsubmit="event.preventDefault(); createContact(this);" novalidate>
                <div class="modal-contact-body">
                    <div class="contact-form-grid">
                        <!-- ══════════════════════════════════════════════ -->
                        <!-- COLUMNA IZQUIERDA                              -->
                        <!-- ══════════════════════════════════════════════ -->
                        <div class="contact-form-col">
                            <!-- Información Personal -->
                            <div class="contact-form-section">
                                <h4 class="contact-section-title">
                                    <i class="fas fa-user"></i>
                                    Información Personal
                                </h4>
                                <div class="contact-row contact-row-3">
                                    <div class="contact-field">
                                        <label>Prefijo</label>
                                        <select name="prefix">
                                            <option value="">--</option>
                                            <option value="Sr.">Sr.</option>
                                            <option value="Sra.">Sra.</option>
                                            <option value="Dr.">Dr.</option>
                                            <option value="Dra.">Dra.</option>
                                            <option value="Ing.">Ing.</option>
                                            <option value="Lic.">Lic.</option>
                                        </select>
                                    </div>
                                    <div class="contact-field">
                                        <label>Nombre <span class="req">*</span></label>
                                        <input type="text" name="first_name" required
                                               pattern="[A-Za-záéíóúÁÉÍÓÚñÑüÜ\s]{2,50}"
                                               minlength="2" maxlength="50"
                                               title="Solo letras, mínimo 2 caracteres"
                                               oninput="this.value = this.value.replace(/[^A-Za-záéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                                        <span class="field-error"></span>
                                    </div>
                                    <div class="contact-field">
                                        <label>Apellido <span class="req">*</span></label>
                                        <input type="text" name="last_name" required
                                               pattern="[A-Za-záéíóúÁÉÍÓÚñÑüÜ\s]{2,50}"
                                               minlength="2" maxlength="50"
                                               title="Solo letras, mínimo 2 caracteres"
                                               oninput="this.value = this.value.replace(/[^A-Za-záéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                                        <span class="field-error"></span>
                                    </div>
                                </div>
                                <div class="contact-row contact-row-2">
                                    <div class="contact-field">
                                        <label>Segundo Nombre</label>
                                        <input type="text" name="middle_name"
                                               pattern="[A-Za-záéíóúÁÉÍÓÚñÑüÜ\s]{0,50}"
                                               maxlength="50"
                                               title="Solo letras"
                                               oninput="this.value = this.value.replace(/[^A-Za-záéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                                        <span class="field-error"></span>
                                    </div>
                                    <div class="contact-field">
                                        <label>Apodo</label>
                                        <input type="text" name="nickname" placeholder="Nombre corto"
                                               pattern="[A-Za-záéíóúÁÉÍÓÚñÑüÜ0-9\s]{0,30}"
                                               maxlength="30"
                                               title="Letras y números, máximo 30 caracteres">
                                        <span class="field-error"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Información de Contacto -->
                            <div class="contact-form-section">
                                <h4 class="contact-section-title">
                                    <i class="fas fa-address-book"></i>
                                    Información de Contacto
                                </h4>
                                <div class="contact-row contact-row-2">
                                    <div class="contact-field">
                                        <label><i class="fas fa-envelope"></i> Email Principal</label>
                                        <input type="email" name="email" 
                                               placeholder="correo@ejemplo.com"
                                               pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                                               maxlength="255"
                                               title="Ingrese un email válido">
                                        <span class="field-error"></span>
                                    </div>
                                    <div class="contact-field">
                                        <label><i class="fas fa-envelope"></i> Email Secundario</label>
                                        <input type="email" name="secondary_email" 
                                               placeholder="alternativo@ejemplo.com"
                                               pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                                               maxlength="255"
                                               title="Ingrese un email válido">
                                        <span class="field-error"></span>
                                    </div>
                                </div>
                                <div class="contact-row contact-row-3">
                                    <div class="contact-field">
                                        <label><i class="fas fa-phone"></i> Teléfono</label>
                                        <input type="tel" name="phone" 
                                               placeholder="999999999"
                                               pattern="[0-9]{7,9}"
                                               minlength="7" maxlength="9"
                                               title="Solo números, 7-9 dígitos"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)">
                                        <span class="field-hint">7-9 dígitos</span>
                                        <span class="field-error"></span>
                                    </div>
                                    <div class="contact-field">
                                        <label><i class="fas fa-mobile-alt"></i> Celular</label>
                                        <input type="tel" name="mobile" 
                                               placeholder="999999999"
                                               pattern="[0-9]{9}"
                                               minlength="9" maxlength="9"
                                               title="Solo números, exactamente 9 dígitos"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)">
                                        <span class="field-hint">9 dígitos</span>
                                        <span class="field-error"></span>
                                    </div>
                                    <div class="contact-field">
                                        <label><i class="fas fa-building"></i> Tel. Trabajo</label>
                                        <input type="tel" name="work_phone" 
                                               placeholder="999999999"
                                               pattern="[0-9]{7,9}"
                                               minlength="7" maxlength="9"
                                               title="Solo números, 7-9 dígitos"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)">
                                        <span class="field-hint">7-9 dígitos</span>
                                        <span class="field-error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════════════════════════════════════════ -->
                        <!-- COLUMNA DERECHA                                -->
                        <!-- ══════════════════════════════════════════════ -->
                        <div class="contact-form-col">
                            <!-- Información Laboral -->
                            <div class="contact-form-section">
                                <h4 class="contact-section-title">
                                    <i class="fas fa-briefcase"></i>
                                    Información Laboral
                                </h4>
                                <div class="contact-row contact-row-2">
                                    <div class="contact-field">
                                        <label>Empresa</label>
                                        <select name="company_id">
                                            <option value="">-- Sin empresa --</option>
                                            <?php if (!empty($empresas)): ?>
                                                <?php foreach ($empresas as $empresa): ?>
                                                    <option value="<?= $empresa['id']; ?>">
                                                        <?= htmlspecialchars($empresa['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="contact-field">
                                        <label>Cargo</label>
                                        <input type="text" name="job_title" 
                                               placeholder="Ej: Gerente de Ventas"
                                               pattern="[A-Za-záéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.]{0,100}"
                                               maxlength="100"
                                               title="Máximo 100 caracteres">
                                        <span class="field-error"></span>
                                    </div>
                                </div>
                                <div class="contact-row contact-row-2">
                                    <div class="contact-field">
                                        <label>Departamento</label>
                                        <input type="text" name="department" 
                                               placeholder="Ej: Comercial"
                                               pattern="[A-Za-záéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.]{0,100}"
                                               maxlength="100"
                                               title="Máximo 100 caracteres">
                                        <span class="field-error"></span>
                                    </div>
                                    <div class="contact-field">
                                        <label>Origen del Lead</label>
                                        <select name="lead_source">
                                            <option value="">-- Seleccionar --</option>
                                            <option value="website">Sitio Web</option>
                                            <option value="referral">Referido</option>
                                            <option value="social_media">Redes Sociales</option>
                                            <option value="cold_call">Llamada en Frío</option>
                                            <option value="event">Evento</option>
                                            <option value="other">Otro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Estado y Configuración -->
                            <div class="contact-form-section">
                                <h4 class="contact-section-title">
                                    <i class="fas fa-cog"></i>
                                    Estado y Configuración
                                </h4>
                                <div class="contact-row contact-row-3">
                                    <div class="contact-field">
                                        <label>Estado</label>
                                        <select name="status" required>
                                            <option value="active">Activo</option>
                                            <option value="inactive">Inactivo</option>
                                            <option value="do_not_contact">No contactar</option>
                                        </select>
                                    </div>
                                    <div class="contact-field">
                                        <label>Etapa del Ciclo</label>
                                        <select name="lifecycle_stage" required>
                                            <option value="lead">Lead</option>
                                            <option value="subscriber">Suscriptor</option>
                                            <option value="mql">MQL</option>
                                            <option value="sql">SQL</option>
                                            <option value="opportunity">Oportunidad</option>
                                            <option value="customer">Cliente</option>
                                            <option value="evangelist">Evangelista</option>
                                        </select>
                                    </div>
                                    <div class="contact-field">
                                        <label>Asignado a</label>
                                        <select name="owner_id">
                                            <option value="">-- Sin asignar --</option>
                                            <?php if (!empty($usuarios)): ?>
                                                <?php foreach ($usuarios as $user): ?>
                                                    <option value="<?= $user['id']; ?>">
                                                        <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="contact-row">
                                    <div class="contact-field">
                                        <label>Notas / Descripción</label>
                                        <textarea name="description" rows="4" 
                                                  placeholder="Información adicional sobre el contacto..."
                                                  maxlength="1000"></textarea>
                                        <span class="field-hint char-counter" data-max="1000">0/1000</span>
                                        <span class="field-error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-contact-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-add')">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Guardar Contacto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* ═══════════════════════════════════════════════════════════════════════════ */
/* MODAL CONTACTO - ESTILOS INDEPENDIENTES                                     */
/* ═══════════════════════════════════════════════════════════════════════════ */

.modal-contact {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 99999;
}

.modal-contact.active {
    display: block;
}

.modal-contact-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
}

.modal-contact-wrapper {
    position: fixed;
    inset: 0;
    overflow-y: auto;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 40px 20px;
}

.modal-contact-box {
    position: relative;
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 1200px;
    min-width: 900px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    animation: modalIn 0.3s ease;
}

@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(-20px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-contact-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
    border-radius: 12px 12px 0 0;
}

.modal-contact-title {
    font-size: 1.4rem;
    font-weight: 600;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
}

.modal-contact-title i {
    color: #10b981;
}

.modal-contact-close {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: #fff;
    color: #6b7280;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.15s;
}

.modal-contact-close:hover {
    background: #fee2e2;
    color: #dc2626;
}

.modal-contact-body {
    padding: 30px;
    max-height: calc(100vh - 250px);
    overflow-y: auto;
}

.modal-contact-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 30px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    border-radius: 0 0 12px 12px;
}

/* ═══════════════════════════════════════════════════════════════════════════ */
/* GRID DE 2 COLUMNAS                                                          */
/* ═══════════════════════════════════════════════════════════════════════════ */

.contact-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.contact-form-col {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* ═══════════════════════════════════════════════════════════════════════════ */
/* SECCIONES                                                                   */
/* ═══════════════════════════════════════════════════════════════════════════ */

.contact-form-section {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
}

.contact-section-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 16px 0;
    padding-bottom: 12px;
    border-bottom: 2px solid rgba(16, 185, 129, 0.3);
    display: flex;
    align-items: center;
    gap: 8px;
}

.contact-section-title i {
    color: #10b981;
}

/* ═══════════════════════════════════════════════════════════════════════════ */
/* FILAS Y CAMPOS                                                              */
/* ═══════════════════════════════════════════════════════════════════════════ */

.contact-row {
    display: grid;
    gap: 15px;
    margin-bottom: 15px;
}

.contact-row:last-child {
    margin-bottom: 0;
}

.contact-row-2 {
    grid-template-columns: 1fr 1fr;
}

.contact-row-3 {
    grid-template-columns: 1fr 1fr 1fr;
}

.contact-field {
    display: flex;
    flex-direction: column;
    position: relative;
}

.contact-field label {
    font-size: 0.8rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.contact-field label i {
    color: #9ca3af;
    font-size: 0.7rem;
}

.contact-field label .req {
    color: #dc2626;
}

.contact-field input,
.contact-field select,
.contact-field textarea {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.9rem;
    font-family: inherit;
    background: #fff;
    transition: all 0.15s;
    width: 100%;
    box-sizing: border-box;
}

.contact-field input:focus,
.contact-field select:focus,
.contact-field textarea:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
}

.contact-field input::placeholder,
.contact-field textarea::placeholder {
    color: #9ca3af;
}

.contact-field select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 10px center;
    background-repeat: no-repeat;
    background-size: 16px;
    padding-right: 36px;
}

.contact-field textarea {
    resize: vertical;
    min-height: 80px;
}

/* ═══════════════════════════════════════════════════════════════════════════ */
/* VALIDACIONES - ESTILOS                                                      */
/* ═══════════════════════════════════════════════════════════════════════════ */

.field-hint {
    font-size: 0.7rem;
    color: #9ca3af;
    margin-top: 4px;
}

.field-error {
    font-size: 0.75rem;
    color: #dc2626;
    margin-top: 4px;
    display: none;
    align-items: center;
    gap: 4px;
}

.field-error::before {
    content: "⚠";
}

.contact-field.has-error input,
.contact-field.has-error select,
.contact-field.has-error textarea {
    border-color: #dc2626;
    background-color: #fef2f2;
}

.contact-field.has-error input:focus,
.contact-field.has-error select:focus,
.contact-field.has-error textarea:focus {
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15);
}

.contact-field.has-error .field-error {
    display: flex;
}

.contact-field.is-valid input,
.contact-field.is-valid select,
.contact-field.is-valid textarea {
    border-color: #10b981;
    background-color: #f0fdf4;
}

.char-counter {
    text-align: right;
}

.char-counter.warning {
    color: #f59e0b;
}

.char-counter.danger {
    color: #dc2626;
}

/* ═══════════════════════════════════════════════════════════════════════════ */
/* BOTONES                                                                     */
/* ═══════════════════════════════════════════════════════════════════════════ */

.btn-cancel,
.btn-save {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 24px;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.15s;
    border: none;
    font-family: inherit;
}

.btn-cancel {
    background: #fff;
    color: #374151;
    border: 2px solid #d1d5db;
}

.btn-cancel:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.btn-save {
    background: #10b981;
    color: #fff;
}

.btn-save:hover:not(:disabled) {
    background: #059669;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-save:disabled {
    background: #9ca3af;
    cursor: not-allowed;
}

/* ═══════════════════════════════════════════════════════════════════════════ */
/* RESPONSIVE                                                                  */
/* ═══════════════════════════════════════════════════════════════════════════ */

@media (max-width: 1000px) {
    .modal-contact-box {
        min-width: auto;
        max-width: 95%;
    }
    
    .contact-form-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 600px) {
    .contact-row-2,
    .contact-row-3 {
        grid-template-columns: 1fr;
    }
    
    .modal-contact-body {
        padding: 20px;
    }
    
    .modal-contact-header,
    .modal-contact-footer {
        padding: 15px 20px;
    }
}
</style>

<script>
// ═══════════════════════════════════════════════════════════════════════════
// VALIDACIONES DEL FORMULARIO DE CONTACTO
// ═══════════════════════════════════════════════════════════════════════════

const ContactFormValidator = {
    rules: {
        first_name: {
            required: true,
            minLength: 2,
            maxLength: 50,
            pattern: /^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\s]+$/,
            message: 'Solo letras, mínimo 2 caracteres'
        },
        last_name: {
            required: true,
            minLength: 2,
            maxLength: 50,
            pattern: /^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\s]+$/,
            message: 'Solo letras, mínimo 2 caracteres'
        },
        middle_name: {
            required: false,
            maxLength: 50,
            pattern: /^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\s]*$/,
            message: 'Solo letras'
        },
        nickname: {
            required: false,
            maxLength: 30,
            pattern: /^[A-Za-záéíóúÁÉÍÓÚñÑüÜ0-9\s]*$/,
            message: 'Solo letras y números'
        },
        email: {
            required: false,
            pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
            message: 'Email inválido (ej: correo@ejemplo.com)'
        },
        secondary_email: {
            required: false,
            pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
            message: 'Email inválido'
        },
        phone: {
            required: false,
            minLength: 7,
            maxLength: 9,
            pattern: /^[0-9]{7,9}$/,
            message: 'Solo números, 7-9 dígitos'
        },
        mobile: {
            required: false,
            minLength: 9,
            maxLength: 9,
            pattern: /^[0-9]{9}$/,
            message: 'Solo números, exactamente 9 dígitos'
        },
        work_phone: {
            required: false,
            minLength: 7,
            maxLength: 9,
            pattern: /^[0-9]{7,9}$/,
            message: 'Solo números, 7-9 dígitos'
        },
        job_title: {
            required: false,
            maxLength: 100,
            message: 'Máximo 100 caracteres'
        },
        department: {
            required: false,
            maxLength: 100,
            message: 'Máximo 100 caracteres'
        },
        description: {
            required: false,
            maxLength: 1000,
            message: 'Máximo 1000 caracteres'
        }
    },

    validateField(field) {
        const name = field.name;
        const value = field.value.trim();
        const rule = this.rules[name];
        const fieldContainer = field.closest('.contact-field');
        const errorSpan = fieldContainer?.querySelector('.field-error');

        if (!rule) return true;

        // Limpiar estados previos
        fieldContainer?.classList.remove('has-error', 'is-valid');
        if (errorSpan) errorSpan.textContent = '';

        // Validar requerido
        if (rule.required && !value) {
            this.showError(fieldContainer, errorSpan, 'Este campo es obligatorio');
            return false;
        }

        // Si está vacío y no es requerido, es válido
        if (!value && !rule.required) {
            return true;
        }

        // Validar longitud mínima
        if (rule.minLength && value.length < rule.minLength) {
            this.showError(fieldContainer, errorSpan, `Mínimo ${rule.minLength} caracteres`);
            return false;
        }

        // Validar longitud máxima
        if (rule.maxLength && value.length > rule.maxLength) {
            this.showError(fieldContainer, errorSpan, `Máximo ${rule.maxLength} caracteres`);
            return false;
        }

        // Validar patrón
        if (rule.pattern && !rule.pattern.test(value)) {
            this.showError(fieldContainer, errorSpan, rule.message);
            return false;
        }

        // Campo válido
        if (value) {
            fieldContainer?.classList.add('is-valid');
        }
        return true;
    },

    showError(container, errorSpan, message) {
        container?.classList.add('has-error');
        if (errorSpan) errorSpan.textContent = message;
    },

    validateForm(form) {
        let isValid = true;
        const fields = form.querySelectorAll('input, select, textarea');
        let firstError = null;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
                if (!firstError) firstError = field;
            }
        });

        // Enfocar primer campo con error
        if (firstError) {
            firstError.focus();
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        return isValid;
    },

    init(form) {
        const fields = form.querySelectorAll('input, select, textarea');

        fields.forEach(field => {
            // Validar al salir del campo
            field.addEventListener('blur', () => this.validateField(field));

            // Validar mientras escribe (con debounce)
            let timeout;
            field.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => this.validateField(field), 300);
            });
        });

        // Contador de caracteres para textarea
        const textarea = form.querySelector('textarea[name="description"]');
        const charCounter = form.querySelector('.char-counter');
        if (textarea && charCounter) {
            const maxChars = parseInt(charCounter.dataset.max) || 1000;
            textarea.addEventListener('input', () => {
                const len = textarea.value.length;
                charCounter.textContent = `${len}/${maxChars}`;
                charCounter.classList.remove('warning', 'danger');
                if (len > maxChars * 0.9) charCounter.classList.add('danger');
                else if (len > maxChars * 0.7) charCounter.classList.add('warning');
            });
        }
    }
};

// ═══════════════════════════════════════════════════════════════════════════
// FUNCIONES DEL MODAL
// ═══════════════════════════════════════════════════════════════════════════

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Inicializar validaciones
        const form = modal.querySelector('form');
        if (form) ContactFormValidator.init(form);
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
            // Limpiar estados de validación
            form.querySelectorAll('.contact-field').forEach(f => {
                f.classList.remove('has-error', 'is-valid');
            });
            form.querySelectorAll('.field-error').forEach(e => {
                e.textContent = '';
            });
            const charCounter = form.querySelector('.char-counter');
            if (charCounter) charCounter.textContent = '0/1000';
        }
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const activeModal = document.querySelector('.modal-contact.active');
        if (activeModal) closeModal(activeModal.id);
    }
});

async function createContact(form) {
    // Validar formulario antes de enviar
    if (!ContactFormValidator.validateForm(form)) {
        showNotification('Por favor corrige los errores en el formulario', 'error');
        return;
    }

    const formData = new FormData(form);
    const submitBtn = form.querySelector('.btn-save');
    const originalText = submitBtn.innerHTML;
    
    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        
        const response = await fetch('actions/contactos/create.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            closeModal('modal-add');
            showNotification('Contacto creado exitosamente', 'success');
            if (typeof loadContacts === 'function') loadContacts();
        } else {
            showNotification(data.message || 'Error al crear el contacto', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error de conexión', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

function showNotification(message, type = 'info') {
    const existing = document.querySelector('.contact-notification');
    if (existing) existing.remove();
    
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    
    const notification = document.createElement('div');
    notification.className = `contact-notification contact-notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${icons[type] || 'info-circle'}"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 5000);
}
</script>

<style>
.contact-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 14px 18px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 999999;
    animation: notifIn 0.3s ease;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    font-size: 0.9rem;
    max-width: 400px;
}

@keyframes notifIn {
    from { opacity: 0; transform: translateX(100%); }
    to { opacity: 1; transform: translateX(0); }
}

.contact-notification-success {
    background: #d1fae5;
    color: #065f46;
    border-left: 4px solid #10b981;
}

.contact-notification-error {
    background: #fee2e2;
    color: #991b1b;
    border-left: 4px solid #dc2626;
}

.contact-notification-warning {
    background: #fef3c7;
    color: #92400e;
    border-left: 4px solid #f59e0b;
}

.contact-notification button {
    background: none;
    border: none;
    cursor: pointer;
    color: inherit;
    opacity: 0.7;
    padding: 4px;
    margin-left: 8px;
}

.contact-notification button:hover {
    opacity: 1;
}
</style>