import { api } from 'boot/axios'
import type { Product } from 'src/components/models'

export const fetchProducts = async (): Promise<Product[]> => {
  const response = await api.get('products')
  return Array.isArray(response.data) ? response.data : []
}

export const createProduct = async (formData: FormData) => {
  try {
    const response = await api.post('products', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Accept: 'application/json',
        Authorization: `Bearer ${localStorage.getItem('token')}`, // Adapte para pegar o token do localStorage ou store
      },
    })
    return response.data
  } catch (error) {
    console.log(error)
    throw new Error('Erro ao criar produto')
  }
}

export const updateProduct = async (id: number, formData: FormData) => {
  try {
    const response = await api.post(`products/${id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Accept: 'application/json',
        Authorization: `Bearer ${localStorage.getItem('token')}`, // Adapte para pegar o token do localStorage ou store
      },
    })
    return response.data
  } catch (error) {
    console.log(error)
    throw new Error('Erro ao atualizar produto')
  }
}

export const deleteProduct = async (id: number) => {
  return api.delete(`products/${id}`)
}
