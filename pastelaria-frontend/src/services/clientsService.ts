import { api } from 'boot/axios'
import type { Customer } from 'src/components/models'

export const fetchClients = async (): Promise<Customer[]> => {
  const response = await api.get('customers')
  return Array.isArray(response.data) ? response.data : []
}

export const createClient = async (clientData: Customer) => {
  return api.post('customers', clientData)
}

export const updateClient = async (id: number, clientData: Customer) => {
  return api.put(`customers/${id}`, clientData)
}

export const deleteClient = async (id: number) => {
  return api.delete(`customers/${id}`)
}
