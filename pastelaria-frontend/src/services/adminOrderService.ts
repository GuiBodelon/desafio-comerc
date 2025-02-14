import { api } from 'boot/axios'
import type { Order } from 'src/components/models'

export const fetchOrders = async (): Promise<Order[]> => {
  const response = await api.get('orders')
  return Array.isArray(response.data) ? response.data : []
}

export const createOrder = async (formData: FormData) => {
  try {
    const response = await api.post('orders', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Accept: 'application/json',
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })
    return response.data
  } catch (error) {
    console.log(error)
    throw new Error('Erro ao criar pedido')
  }
}

export const updateOrder = async (id: number, formData: FormData) => {
  try {
    const response = await api.post(`orders/${id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Accept: 'application/json',
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })
    return response.data
  } catch (error) {
    console.log(error)
    throw new Error('Erro ao atualizar pedido')
  }
}

export const deleteOrder = async (id: number) => {
  return api.delete(`orders/${id}`)
}
