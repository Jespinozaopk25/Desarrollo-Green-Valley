/**
 * Manejador específico para la página de login
 * Este archivo maneja los eventos y la lógica específica del formulario de login
 */

document.addEventListener("DOMContentLoaded", () => {
  // Verificar que AuthManager esté disponible
  if (typeof AuthManager === "undefined") {
    console.error("AuthManager no está disponible. Asegúrate de cargar auth-functions.js primero.")
    return
  }

  // Inicializar el gestor de autenticación
  const authManager = new AuthManager()
  authManager.init("loading", "alert-container")

  // Elementos del DOM
  const loginForm = document.getElementById("loginForm")
  const usernameField = document.getElementById("username")
  const passwordField = document.getElementById("password")
  const rememberCheckbox = document.getElementById("remember")

  // Verificar si ya está logueado al cargar la página
  authManager.checkSession()

  // Manejar envío del formulario
  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault()
      await handleFormSubmit()
    })
  }
  // Manejar Enter en los campos
  ;[usernameField, passwordField].forEach((field) => {
    if (field) {
      field.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
          e.preventDefault()
          handleFormSubmit()
        }
      })
    }
  })

    // Limpiar alertas cuando el usuario empiece a escribir
    ;[usernameField, passwordField].forEach((field) => {
      if (field) {
        field.addEventListener("input", () => {
          authManager.clearAlert()
        })
      }
    })

  /**
   * Manejar el envío del formulario
   */
  async function handleFormSubmit() {
    const username = usernameField?.value.trim() || ""
    const password = passwordField?.value || ""
    const remember = rememberCheckbox?.checked || false

    console.log("Intentando login con:", { username, remember }) // Debug

    const success = await authManager.handleLogin(username, password, remember)

    if (!success) {
      // Limpiar contraseña en caso de error
      if (passwordField) {
        passwordField.value = ""
        passwordField.focus()
      }
    }
  }

  /**
   * Funciones adicionales para mejorar la UX
   */

  // Auto-focus en el primer campo vacío
  setTimeout(() => {
    if (usernameField && !usernameField.value) {
      usernameField.focus()
    } else if (passwordField && !passwordField.value) {
      passwordField.focus()
    }
  }, 100)

  // Mostrar/ocultar contraseña (si se agrega el botón)
  const togglePasswordBtn = document.getElementById("togglePassword")
  if (togglePasswordBtn && passwordField) {
    togglePasswordBtn.addEventListener("click", function () {
      const type = passwordField.getAttribute("type") === "password" ? "text" : "password"
      passwordField.setAttribute("type", type)

      // Cambiar icono del botón
      this.textContent = type === "password" ? "👁️" : "🙈"
    })
  }

  // Validación en tiempo real
  if (usernameField) {
    usernameField.addEventListener("blur", function () {
      const username = this.value.trim()
      if (username && username.length < 3) {
        authManager.showAlert("El usuario debe tener al menos 3 caracteres", "error")
      }
    })
  }

  if (passwordField) {
    passwordField.addEventListener("blur", function () {
      const password = this.value
      if (password && password.length < 6) {
        authManager.showAlert("La contraseña debe tener al menos 6 caracteres", "error")
      }
    })
  }

  // Prevenir múltiples envíos
  let isSubmitting = false
  loginForm?.addEventListener("submit", (e) => {
    if (isSubmitting) {
      e.preventDefault()
      return false
    }
    isSubmitting = true

    // Resetear después de 3 segundos
    setTimeout(() => {
      isSubmitting = false
    }, 3000)
  })
})
