import { Notify } from 'quasar'

// Função para exibir uma notificação de sucesso
export const showSuccessNotification = (message: string) => {
  Notify.create({
    type: 'positive',
    message,
    position: 'top',
    icon: 'check_circle',
  })
}

// Função para exibir uma notificação de erro
export const showErrorNotification = (message: string) => {
  Notify.create({
    type: 'negative',
    message,
    position: 'top',
    icon: 'error',
  })
}

// Função para exibir uma notificação de informação
export const showInfoNotification = (message: string) => {
  Notify.create({
    type: 'info',
    message,
    position: 'top',
    icon: 'info',
  })
}

// Função para exibir uma notificação com ações para o pedido
export const showWarningNotification = (message: string) => {
  Notify.create({
    type: 'warning',
    message,
    position: 'top',
    icon: 'warning',
  })
}

// Função para exibir a notificação "hourglass"
export const notificationHourglass = (message: string) => {
  // Criar a notificação e armazenar a referência
  const notification = Notify.create({
    message,
    color: 'blue',
    icon: 'hourglass_empty',
    position: 'top',
    timeout: 0, // Notificação persistente
    group: false,
  })

  // Retornar a referência da notificação (não é mais um ID)
  return notification
}

// Função para remover a notificação persistente
export const removeNotification = (notification: () => void) => {
  // Invocar a função retornada para remover a notificação
  notification()
}
